<?php
	require_once 'WhatsBotExceptions.php';
	
	require_once 'whatsapi/whatsprot.class.php';
	require_once 'WhatsApp.php';

	require_once 'Listener.php';
	require_once 'Parser.php';

	require_once 'ModuleManager.php';

	require_once 'IncludeManager.php';


	require_once 'ConfigManager.php';


	class WhatsBot
	{
		private $WhatsProt = null;
		private $WhatsApp = null;

		private $Listener = null;
		private $Parser = null;

		private $ModuleManager = null;

		private $IncludeManager = null;


		private $Debug = false;


		public function __construct($Debug = false)
		{
			$this->IncludeManager = new IncludeManager;
			$this->IncludeManager->LoadIncludes();


			$this->Debug = $Debug;


			$Config = Config::Get('WhatsBot');

			if(!empty($Config['WhatsApp']['Username']) && !empty($Config['WhatsApp']['Nickname']))
			{
				# WhatsApp

				$this->WhatsProt = new WhatsProt($Config['WhatsApp']['Username'], $Config['WhatsApp']['Nickname'], $Debug);

				$this->WhatsApp = new WhatsApp($this->WhatsProt);

				# WhatsBot

				$this->ModuleManager = new ModuleManager($this->WhatsApp);

				$this->Parser = new WhatsBotParser($this->WhatsApp, $this->ModuleManager);
				
				$this->Listener = new WhatsBotListener($this->WhatsApp, $this->Parser);

				# Load

				$this->ModuleManager->LoadModules();
				// ThreadManager (into Start())

				# Bind Event Listener

				$this->WhatsApp->EventManager()->BindListener($this->Listener);
			}
			else
				throw new WhatsBotException("You have to setup the config file config/WhatsBot.json");
		}

		public function Start()
		{
			$Config = Config::Get('WhatsBot');

			if(!empty($Config['WhatsApp']['Password']))
			{
				$this->WhatsApp->Connect();

				$this->WhatsApp->LoginWithPassword($Config['WhatsApp']['Password']);
			}
			else
				throw new WhatsBotException("You have to add the password to config/WhatsBot.json");
		}

		public function Listen()
		{
			$StartTime = time();

			while(true)
			{
				if(!$this->WhatsApp->IsConnected())
					$this->Start();

				$this->WhatsApp->PollMessage();

				if(time() >= $StartTime + 60)
				{
					$this->WhatsApp->SendPing();

					$StartTime = time();
				}
			}
		}
	}

	/* 
	 * To do: 
	 * 
	 * Flood protection
	 * https://github.com/mgp25/WhatsAPI-Official/issues/164#issuecomment-64821350
	 *  - Sync before send message. Sync if not in array. If disconnect reset array
	 * Fix #28 reconnect issue
	 * CLI only
	 * Implement WhatsApp workflow (https://github.com/mgp25/WhatsAPI-Official/wiki/WhatsAPI-Documentation#whatsapp-workflow)
	 * 
	 *****
	 * 
	 * Ideas: 
	 * 
	 * Detect lang from country code
	 * 
	 *****
	 * Questions: 
	 * 
	 * Send presence?
	 */