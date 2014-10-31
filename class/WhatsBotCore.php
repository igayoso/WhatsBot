<?php // PREVENT FOR NON CLI USE
	require_once 'Catcher.php';
	require_once 'Logger.php';

	require_once 'DB/Controller.php';

	require_once 'whatsapi/events/WhatsAppEventListenerBase.php';
	require_once 'whatsapi/whatsprot.class.php';

	require_once 'WhatsBotParser.php';

	class WhatsBotCore extends WhatsAppEventListenerBase
	{
		protected $L = null;
		protected $C = null;

		protected $DB = null;

		protected $Admins = null;// igual /\

		private $Whatsapp = null;
		private $Password = null;

		protected $Parser = null; // private? testear si puedo acceder desde los modulos
		private $ModuleManager = null; // protected? igual /\


		public function __construct($DBH, $DBU, $DBP, $DBD, $WPU, $WPI, $WPP, $WPN, $WPD = false)
		{
			$this->L = new Logger();
			$this->C = new Catcher($this->L);

			$this->DB = new DBController($DBH, $DBU, $DBP, $DBD, $this->L, $this->C);

			$this->loadConfig();

			$this->Whatsapp = new WhatsProt($WPU, $WPI, $WPN, $WPD);
			$this->Whatsapp->eventManager()->addEventListener($this);

			$this->Password = $WPP;
			$this->connect(); // IF CAN CONNECT();


			$this->ModuleManager = new ModuleManager($this);
			$this->Parser = new WhatsBotParser($this->ModuleManager);

			$this->ModuleManager->LoadModule('echo');
			//$this->ModuleManager->LoadModule('shutdown'); El mensajes se ve como no leido y se vuelve a ejecutar
		}

		public function Listen()
		{
			$i = 0;

			while(true)
			{
				if($i == 30)
				{
					$this->disconnect();
					$this->connect();
				}

				$this->Whatsapp->pollMessage();

				$i++;
			}
		}

		public function connect()
		{
			$this->Whatsapp->connect();
			$this->Whatsapp->loginWithPassword($this->Password);
		}

		private function disconnect() // Where is security? xD
		{
			$this->Whatsapp->disconnect();
		}


		public function sendMessage($To, $Message)
		{
			return $this->Whatsapp->sendMessage($To, $Message);
		}

		public function loadConfig()
		{
			$Data = file_get_contents('class/config/config.json');
			$Data = json_decode($Data, true);

			$this->Admins = $Data['admins'];
		}


		public function Utils_isAdmin($From) // FromG, etc... Ver ese tema
		{
			$From = str_replace('@s.whatsapp.net', '', $From);
			return in_array($From, $this->Admins);
		}

		//get admins, get config, add adm, del adm
	}