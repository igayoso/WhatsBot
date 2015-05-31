<?php
	require_once 'Lib/_Loader.php';

	require_once 'WhatsAPI/whatsprot.class.php';

	require_once 'WhatsApp.php';

	require_once 'Listener.php';

	require_once 'Parser.php';

	require_once 'ModuleManager.php';

	class WhatsBot
	{
		private $WhatsProt = null;
		private $WhatsApp = null;

		private $Listener = null;
		private $Parser = null;

		private $ModuleManager = null;

		private $Debug = false;
		private $StartTime = null;

		public function __construct($Debug = false)
		{
			$this->Debug = (bool)$Debug;

			Std::Out();
			Std::Out('[Info] [WhatsBot] Loading. Debug = ' . var_export($this->Debug, true));

			Config::Load();
			LoadLibs();

			$Config = Config::Get('WhatsBot');

			if(!empty($Config['WhatsApp']['Username']) && !empty($Config['WhatsApp']['Nickname']) && !empty($Config['WhatsApp']['Password']))
			{
				# WhatsApp

				Std::Out();
				Std::Out("[Info] [WhatsBot] I'm {$Config['WhatsApp']['Nickname']} ({$Config['WhatsApp']['Username']})");

				$this->WhatsProt = new WhatsProt($Config['WhatsApp']['Username'], $Config['WhatsApp']['Nickname'], $this->Debug);

				$this->WhatsApp = new WhatsApp($this->WhatsProt);

				# WhatsBot

				$this->ModuleManager = new ModuleManager($this, $this->WhatsApp);

				$this->Parser = new WhatsBotParser($this->WhatsApp, $this->ModuleManager);

				$this->Listener = new WhatsBotListener($this->WhatsApp, $this->Parser, $Config['WhatsApp']['Password']);

				# Load

				$this->ModuleManager->LoadModules();

				# Binding

				Std::Out();

				$this->WhatsApp->EventManager()->BindListener($this->Listener, 'WhatsBotListener');
			}
			else
				throw new Exception('You have to setup the config file config/WhatsBot.json');
		}

		public function Start()
		{
			Std::Out();
			Std::Out('[Info] [WhatsBot] Connecting');

			if($this->WhatsApp->Connect())
			{
				Std::Out();
				Std::Out('[Info] [WhatsBot] Ready!');

				return true;
			}

			Std::Out();
			Std::Out('[Warning] [WhatsBot] Connection error');

			return false;
		}

		public function Listen()
		{
			$this->StartTime = time();

			Std::Out();
			Std::Out("[Info] [WhatsBot] Start time is {$this->StartTime}");

			Std::Out();
			Std::Out('[Info] [WhatsBot] Listening...');

			while(true)
			{
				$Time = time();

				$this->WhatsApp->Disconnect();
				$this->WhatsApp->Connect();

				while(time() < $Time + 60)
					$this->WhatsApp->PollMessage();
			}
		}

		public function GetStartTime()
		{ return $this->StartTime; }
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