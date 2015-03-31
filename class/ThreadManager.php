<?php
	require_once 'WhatsApp.php';
	require_once 'ModuleManager.php';

	class ThreadManager
	{
		private $Started = false;
		private $Threads = array();

		private $WhatsApp = null;
		private $ModuleManager = null;

		public function __construct(WhatsApp $WhatsApp, ModuleManager $ModuleManager)
		{
			$this->WhatsApp = $WhatsApp;
			$this->ModuleManager = $ModuleManager;
		}

		public function LoadThreads()
		{

			$Config = Config::Get('Threads');

			if(isset($Config['enabled']) && isset($Config['threads']) && is_array($Config['threads']))
			{
				if($Config['enabled'])
				{
					Std::Out();
					Std::Out('[INFO] [THREADS] Loading');

					if(extension_loaded('pthreads'))
					{
						require_once 'ThreadModel.php';

						foreach($Config['threads'] as $Thread)
							$this->LoadThread($Thread);

						Std::Out('[INFO] [THREADS] Ready!');

						return true;
					}

					Std::Out("[WARNING] [THREADS] Can't load threads");
					Std::Out('If you want to use threads, you have to install PThreads extension');
					Std::Out('See http://php.net/manual/en/pthreads.installation.php');
					Std::Out('Windows builds: http://windows.php.net/downloads/pecl/releases/pthreads/');
					Std::Out('You can disable threads by setting config/Threads.json[enabled] to false');
				}
			}
			else
			{
				Std::Out();
				Std::Out('[WARNING] [THREADS] Config error');
			}

			return false;
		}

		private function LoadThread($Name)
		{
			$Path = "clas/Threads/{$Name}.php";

			if(basename(dirname(realpath($Path))) === 'Threads' && is_readable($Path))
			{
				include_once $Path;

				$Class = "Thread_{$Name}";

				if(class_exists($Class))
				{
					$this->Threads[$Name] = new $Class($this->WhatsApp, $this->ModuleManager);

					if($this->Threads[$Name] instanceof WhatsBotThread)
						return true;

					unset($this->Threads[$Name]);

					Std::Out("[WARNING] [THREADS] {$Class} must be inherited from WhatsBotThread to work");
				}
				else
					Std::Out("[WARNING] [THREADS] Thread's class {$Name} doesn't exists. That class, at " . realpath($Path) . ", must be named {$Class}");
			}
			else
				Std::Out("[WARNING] [THREADS] Can't load thread {$Name}. It is not in Threads folder or isn't readable");

			return false;
		}

		public function StartThreads()
		{
			if(!empty($this->Threads))
			{
				if(!$this->Started)
				{
					Std::Out();
					Std::Out('[INFO] [THREADS] Starting');

					foreach($this->Threads as $Thread)
						$Thread->Start(PTHREADS_INHERIT_ALL | PTHREADS_ALLOW_HEADERS | PTHREADS_ALLOW_GLOBALS);

					Std::Out('[INFO] [THREADS] Ready!');

					$this->Started = true;
				}
			}

			return true;
		}
	}