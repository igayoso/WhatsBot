<?php
	require_once 'ThreadModel.php';

	class ThreadManager
	{
		private $Threads = array();

		private $Whatsapp = null;
		private $ModuleManager = null;

		public function __construct(WhatsappBridge &$Whatsapp, ModuleManager &$ModuleManager)
		{
			$this->Whatsapp = &$Whatsapp;
			$this->ModuleManager = &$ModuleManager;
		}

		public function LoadThreads()
		{
			$Threads = Utils::GetJson('config/Threads.json');

			if($Threads !== false)
			{
				foreach($Threads as $Thread)
					$this->LoadThread($Thread);

				foreach($this->Threads as $Name => $Thread)
					$this->StartThread($Name);
			}
		}

		public function LoadThread($Name)
		{
			$Filename = "class/threads/{$Name}.php";

			if(is_file($Filename) && is_readable($Filename))
			{
				include $Filename; // once?

				$ClassName = "Thread_{$Name}";

				$this->Threads[$Name] = new $ClassName($this->Whatsapp, $this->ModuleManager);

				if(!($this->Threads[$Name] instanceof WhatsBotThread))
				{
					Utils::Write('Class "' . get_class($this->Threads[$Name]) . '" must be inherited from WhatsBotThread to work...');
					Utils::WriteNewLine();

					unset($this->Threads[$Name]);
				}
			}
		}

		public function StartThread($Name)
		{
			$this->Threads[$Name]->start(PTHREADS_ALLOW_GLOBALS);
		}

		public function ExecuteTasks()
		{
			foreach($this->Threads as $Thread)
			{
				$Tasks = $Thread->GetTasks();

				foreach($Tasks as $Task)
					if(method_exists($this->Whatsapp, $Task[0]) && is_callable(array($this->Whatsapp, $Task[0])))
						call_user_func_array(array($this->Whatsapp, $Task[0]), $Task[1]);
			}
		}
	}