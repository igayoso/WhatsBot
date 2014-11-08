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

		private $ModuleManager = null;
		private $Parser = null;
		private $Caller = null;
		private $Bridge = null;

		public function __construct($Debug = false)
		{
			$Config = file_get_contents('config/WhatsBot.json');
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
			$this->ModuleManager->LoadIncludes();
			$this->ModuleManager->LoadModules();
			//$this->ModuleManager->LoadPlainModules();

			$this->Parser = new WhatsBotParser($this->Bridge, $this->ModuleManager);

			$this->Listener = new WhatsBotListener
			(
				$this->Whatsapp,
				$Config['whatsapp'][2],
				$this->Parser
			);

			$this->Whatsapp->eventManager()->addEventListener($this->Listener);
		}

		public function Listen()
		{
			echo 'Listening...'; // . NL

			$i = 0;

			while(true)
			{
				if($i == 30)
				{
					$this->disconnect();
					$this->connect();
					$i = 0;
				}

				$this->Whatsapp->pollMessage();

				$i++;
			}
		}

		private function connect()
		{
			$this->Whatsapp->connect();
			$this->Whatsapp->loginWithPassword($this->Password);
		}

		private function disconnect() // Where is security? xD
		{
			$this->Whatsapp->disconnect();
		}
	}

	/* To do: 
	 * Make an parser for modules (With https://github.com/nikic/PHP-Parser ?)
	 */