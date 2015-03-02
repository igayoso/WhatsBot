<?php
	require_once 'Lang.php';
	require_once 'WhatsBotExceptions.php';
	
	require_once 'whatsapi/whatsprot.class.php';
	require_once 'WhatsApp.php';

	require_once 'Listener.php';
	require_once 'Parser.php';

	require_once 'ModuleManager.php';


	require_once 'ConfigManager.php';


	class WhatsBot
	{
		private $WhatsProt = null;
		private $WhatsApp = null;

		private $Listener = null;
		private $Parser = null;

		private $ModuleManager = null;


		private $Lang = null;


		private $Debug = false;


		public function __construct($Debug = false)
		{
			$this->Lang = new Lang('Main');

			$this->Debug = $Debug;


			$Config = Config::Get('WhatsBot');

			if(!empty($Config['WhatsApp']['Username']) && !empty($Config['WhatsApp']['Nickname']))
			{
				# WhatsApp

				$this->WhatsProt = new WhatsProt($Config['WhatsApp']['Username'], null, $Config['WhatsApp']['Nickname'], $Debug);

				$this->WhatsApp = new WhatsApp($this->WhatsProt);

				# WhatsBot

				$this->ModuleManager = new ModuleManager($this->WhatsApp);

				$this->Parser = new WhatsBotParser($this->WhatsApp, $this->ModuleManager);
				
				$this->Listener = new WhatsBotListener($this->WhatsApp, $this->Parser);

				# Load

				// IncludeManager
				$this->ModuleManager->LoadModules();
				// ThreadManager

				# Bind Event Listener

				$this->WhatsApp->EventManager()->BindListener($this->Listener);
			}
			else
				throw new WhatsBotException($this->Lang->Get('exception:config_empty', 'WhatsBot.json'));
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
				throw new WhatsBotException($this->Lang->Get('exception:config_empty_password', 'WhatsBot.json'));
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

	/* To do: 
	 * Make an parser for modules (With https://github.com/nikic/PHP-Parser ?)
	 * Flood detection / protection
	 * 
	 * https://github.com/mgp25/WhatsAPI-Official/issues/164#issuecomment-64790667
	 * Add syncing before send message (Array with numbers synceds? [IF DISCONNECT?])
	 * 
	 * Implement? https://github.com/mgp25/WhatsAPI-Official/issues/169
	 * 
	 * Only CLI use
	 * 
	 * Delete references? http://php.net/manual/es/language.oop5.references.php
	 */

	/* To do (new-structure): 
	 * Fix Utils::IsAdmin
	 * Fix !setstatus
	 * Test /soundcloud/
	 * Test !search (updated)
	 */

	/*
	 * Implement: https://github.com/mgp25/WhatsAPI-Official/wiki/WhatsAPI-Documentation#whatsapp-workflow
	 */