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
		private $Password = null;

		private $Listener = null;
		private $Parser = null;

		private $ModuleManager = null;

		private $Debug = false;
		private $StartTime = null;

		private $Exit = false;

		public function __construct($Debug = false)
		{
			$this->Debug = (bool)$Debug;

			Std::Out();
			Std::Out('[Info] [WhatsBot] Loading... Debug = ' . var_export($this->Debug, true));

			Config::Load();
			LoadLibs();

			$Config = Config::Get('WhatsBot');

			if(!empty($Config['WhatsApp']['Username']) && !empty($Config['WhatsApp']['Nickname']) && !empty($Config['WhatsApp']['Password']))
			{
				$this->Password = $Config['WhatsApp']['Password'];

				# WhatsApp

				Std::Out();
				Std::Out("[Info] [WhatsBot] I'm {$Config['WhatsApp']['Nickname']} ({$Config['WhatsApp']['Username']})");

				$this->WhatsProt = new WhatsProt($Config['WhatsApp']['Username'], $Config['WhatsApp']['Nickname'], $this->Debug);

				$this->WhatsApp = new WhatsApp($this->WhatsProt);

				# WhatsBot

				$this->ModuleManager = new ModuleManager($this, $this->WhatsApp);

				$this->Parser = new WhatsBotParser($this->WhatsApp, $this->ModuleManager);

				$this->Listener = new WhatsBotListener($this->WhatsApp, $this->Parser);

				# Load

				$this->ModuleManager->LoadModules();

				# Binding

				Std::Out();

				$this->WhatsApp->EventManager()->BindListener($this->Listener, 'WhatsBotListener');
			}
			else
				throw new Exception('You have to setup the config file config/WhatsBot.json');
		}

		private function Connect($Show = true, $Retries = 3) // Don't hardcode D:
		{
			if($Show)
				Std::Out();

			for($i = 1; $i <= $Retries; $i++)
			{
				try
				{
					$this->WhatsApp->Disconnect();

					if($Show === true)
						Std::Out('[Info] [WhatsBot] Connecting');

					$this->WhatsApp->Connect();

					if($Show === true)
						Std::Out('[Info] [WhatsBot] Logging in');

					$this->WhatsApp->LoginWithPassword($this->Password);

					if($Show)
					{
						Std::Out();
						Std::Out('[Info] [WhatsBot] Ready!');
					}

					return true;
				}
				catch(Exception $Exception)
				{
					$Class = get_class($Exception);

					if($Class === 'ConnectionException')
						$Message = 'Connection error (' . $Exception->GetMessage() . ')';
					elseif($Class === 'LoginFailureException')
						$Message = 'Login failure';
					else
						$Message = 'Unknown exception while connecting (' . $Exception->GetMessage() . ')';

					if($Show === true)
						Std::Out();

					$Show = 1;

					Std::Out("[Warning] [WhatsBot] {$Message}. Retry {$i}/{$Retries}...");

					// Log trace?
				}
			}

			if($Class === 'ConnectionException')
				$this->_Exit('Connection error');
			elseif($Class === 'LoginFailureException')
				$this->_Exit('Login failure');
			else
				$this->_Exit("{$Class} exception");

			return false;
		}

		public function Start()
		{
			return $this->Connect();
		}

		public function Listen()
		{
			if($this->Exit !== false)
			{
				Std::Out();
				Std::Out("[Info] [WhatsBot] Exiting ({$this->Exit})...");

				return $this->Exit;
			}

			$this->StartTime = time();

			Std::Out();
			Std::Out("[Info] [WhatsBot] Start time is {$this->StartTime}");

			Std::Out();
			Std::Out('[Info] [WhatsBot] Listening...');

			while(true)
			{
				$Time = time();

				while(time() < $Time + 60)
				{
					if($this->Exit !== false)
					{
						Std::Out();
						Std::Out("[Info] [WhatsBot] Exiting ({$this->Exit})...");

						return $this->Exit;
					}

					$this->WhatsApp->PollMessage();
				}

				$this->Connect(false);
			}
		}

		public function GetStartTime()
		{ return $this->StartTime; }

		public function _Exit($Code)
		{ $this->Exit = $Code; }
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