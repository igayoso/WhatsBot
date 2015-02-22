<?php
	require_once 'whatsapi/whatsprot.class.php';
	require_once 'Whatsapp.php';

	require_once 'Listener.php';
	require_once 'Parser.php';

	require_once 'ModuleManager.php';


	require_once 'ConfigManager.php';


	class WhatsBot
	{
		private $WP = null;
		private $Whatsapp = null;

		private $Listener = null;
		private $Parser = null;

		private $ModuleManager = null;


		private $Debug = false;


		public function __construct($Debug = false)
		{
			$this->Debug = $Debug;


			$Config = Config::Get('WhatsBot');

			if(!empty($Config['whatsapp']['username']) && !empty($Config['whatsapp']['nickname']));
			{
				# Whatsapp

				$this->WP = new WhatsProt($Config['whatsapp']['username'], null, $Config['whatsapp']['nickname'], $Debug);

				$this->Whatsapp = new Whatsapp($this->WP);

				# WhatsBot

				$this->ModuleManager = new ModuleManager($this->Whatsapp);

				$this->Parser = new WhatsBotParser($this->Whatsapp, $this->ModuleManager);
				
				$this->Listener = new WhatsBotListener($this->Whatsapp, $this->Parser);

				# Load

				// IncludeManager
				$this->ModuleManager->Load();
				// ThreadManager

				# Bind Event Listener

				$this->Whatsapp->EventManager()->SetDebug($Debug);
				$this->Whatsapp->EventManager()->BindListener($this->Listener);
			}
			else
				throw new WhatsBotException('You have to setup the config file WhatsBot.json');
		}

		public function Start()
		{
			$Config = Config::Get('WhatsBot');

			if(!empty($Config['whatsapp']['password']))
			{
				$this->Whatsapp->Connect();

				$this->Whatsapp->LoginWithPassword($Config['whatsapp']['password']);
			}
			else
				throw new WhatsBotException('You have to add the password to config/WhatsBot.json');
		}

		public function Listen()
		{
			$StartTime = time();

			while(true)
			{
				if(!$this->Whatsapp->IsConnected())
					$this->Start();

				$this->Whatsapp->PollMessage();

				if(time() >= $StartTime + 60)
				{
					$this->Whatsapp->SendPing();

					$StartTime = time();
				}
			}
		}
	}
	
	class WhatsBotException extends Exception
	{ }

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



	