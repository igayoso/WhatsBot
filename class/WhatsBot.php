<?php
	require_once 'whatsapi/whatsprot.class.php';
	require_once 'WhatsBotListener.php';
	require_once 'WhatsBotParser.php';
	require_once 'ModuleManager.php';
	require_once 'WhatsBotCaller.php';
	require_once 'WhatsappBridge.php';

	final class WhatsBot
	{
		private $Whatsapp = null;
		private $Password = null;

		private $Listener = null;

		private $Parser = null;
		private $ModuleManager = null;
		private $Caller = null;
		private $Bridge = null;

		public function __construct($Debug = false)
		{
			$Config = file_get_contents('config/WhatsBot.json'); // use getConfig or getJson
			$Config = json_decode($Config, true);


			$this->Password = $Config['whatsapp'][2];

			$this->Whatsapp = new WhatsProt
			(
				$Config['whatsapp'][0],
				$Config['whatsapp'][1],
				$Config['whatsapp'][3],
				$Debug
			);

			$this->Bridge = new WhatsappBridge($this->Whatsapp);

			$this->Caller = new WhatsBotCaller($this->ModuleManager, $this->Bridge); // No interesa que lo inicializemos después, está pasado por referencia

			$this->ModuleManager = new ModuleManager($this->Caller);
			$this->ModuleManager->LoadModules();

			$this->Parser = new WhatsBotParser($this->Bridge, $this->ModuleManager);

			$this->Listener = new WhatsBotListener
			(
				$this->Whatsapp,
				$Config['whatsapp'][2],
				$this->Parser
			);

			$this->Whatsapp->eventManager()->setDebug($Debug);
			$this->Whatsapp->eventManager()->bindClass($this->Listener);
		}

		public function Listen()
		{
			echo 'Connecting...' . PHP_EOL;

			$this->Connect();

			echo 'Listening...' . PHP_EOL;

			$StartTime = time();

			while(true)
			{
				$this->Whatsapp->pollMessage();

				if(time() >= $StartTime + 30)
				{
					$this->Whatsapp->sendPresence('active');
					$this->Whatsapp->sendPing();

					$StartTime = time();
				}
			}
		}

		private function Connect()
		{
			$this->Whatsapp->connect();
			$this->Whatsapp->loginWithPassword($this->Password);
		}
	}

	/* To do: 
	 * Make an parser for modules (With https://github.com/nikic/PHP-Parser ?)
	 * Flood detection / protection
	 */