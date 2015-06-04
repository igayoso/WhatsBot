<?php
	require_once 'Lib/_Loader.php';

	require_once 'WhatsBot.php';
	require_once 'WhatsApp.php';

	class ThreadManager
	{
		private $Threads = array();

		private $Enabled = false;

		private $WhatsBot = null;
		private $WhatsApp = null;

		public function __construct(WhatsBot $WhatsBot, WhatsApp $WhatsApp)
		{
			$this->WhatsBot = $WhatsBot;
			$this->WhatsApp = $WhatsApp;
		}

		public function LoadThreads()
		{
			$Config = Config::Get('Threads');

			if(is_array($Config) && isset($Config['Enabled']) && isset($Config['Threads']) && is_array($Config['Threads']))
			{
				if($Config['Enabled'])
				{
					Std::Out();
					Std::Out('[Info] [Threads] Loading');

					if(extension_loaded('pthreads'))
					{
						$this->Enabled = true;

						require_once 'Thread.php';

						$Loaded = array();

						foreach($Config['Threads'] as $Thread)
							$Loaded['Thread'] = $this->LoadThread($Thread);

						Std::Out('[Info] [Threads] Ready!');

						return $Loaded;
					}
					else
						Std::Out("[Warning] [Threads] Can't load threads. PThreads extension is not installed");
				}
			}
			else
			{
				Std::Out();
				Std::Out('[Warning] [Threads] Config error');
			}

			return false;
		}

		private function LoadThread($Name)
		{
			$this->Threads[$Name] = new WhatsBotThread($Name);

			$Loaded = $this->Threads[$Name]->IsLoaded();

			if(!$Loaded)
				$this->UnloadThread($Name);

			return $Loaded;
		}

		private function UnloadThread($Name)
		{
			if(isset($this->Threads[$Name]))
			{
				unset($this->Threads[$Name]);

				return true;
			}

			return false;
		}

		public function StartThreads()
		{
			if($this->Enabled)
			{
				Std::Out();
				Std::Out('[Info] [Threads] Starting');
			}

			$Threads = array_keys($this->Threads);

			foreach($Threads as $Thread)
				$this->StartThread($Thread, false);

			if($this->Enabled)
				Std::Out('[Info] [Threads] Running!');
		}

		public function StartThread($Name, $Show = true)
		{
			if($Show)
			{
				Std::Out();
				Std::Out("[Info] [Threads] Starting {$Name}");
			}

			if(!empty($this->Threads[$Name]) && $this->Threads[$Name] instanceof WhatsBotThread)
			{
				$this->Threads[$Name]->Start(PTHREADS_INHERIT_ALL | PTHREADS_ALLOW_HEADERS | PTHREADS_ALLOW_GLOBALS);

				if($Show)
					Std::Out("[Info] [Threads] {$Name} started");

				return true;
			}

			if($Show)
				Std::Out("[Warning] [Threads] Can't start {$Name}. That thread doesn't exists");

			return false;
		}

		public function StopThreads($Code = 'Exiting...')
		{
			Std::Out();
			Std::Out('[Info] [Threads] Stopping');

			$Threads = array_keys($this->Threads);

			foreach($Threads as $Thread)
				$this->StopThread($Thread, $Code);

			Std::Out('[Info] [Threads] Stopped!');
		}

		public function StopThread($Name, $Code = 'Exiting...')
		{
			if(isset($this->Threads[$Name]))
			{
				$this->Threads[$Name]->Stop($Code);

				return true;
			}

			Std::Out("[Warning] [Threads] Can't stop {$Name}. That thread doesn't exists");

			return false;
		}

		public function ExecuteTasks()
		{
			foreach($this->Threads as $Name => $Thread)
			{
				$Tasks = $Thread->GetTasks();

				foreach($Tasks as $Task)
				{
					if($Task[0] === WHATSBOT_TASK)
						$Object = $this->WhatsBot;
					elseif($Task[0] === WHATSAPP_TASK)
						$Object = $this->WhatsApp;
					else
						$Object = null;

					if(is_object($Object) && is_string($Task[1]) && !empty($Task[1]) && is_array($Task[2]))
					{
						if(method_exists($Object, $Task[1]) && is_callable(array($Object, $Task[1])))
						{
							$Thread->SetReturn($Task[1], call_user_func_array(array($Object, $Task[1]), $Task[2]));

							continue;
						}
					}

					Std::Out();
					Std::Out("[Warning] [Threads] Can't execute task " . var_export($Object, true) . "->{$Task[1]} (From {$Name})");
				}
			}
		}
	}