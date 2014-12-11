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

				$this->Threads[$Name][0] = new $ClassName();
				$this->Threads[$Name][1] = false;

				if(!($this->Threads[$Name][0] instanceof Thread))
				{
					Utils::Write('Class "' . get_class($this->Threads[$Name][0]) . '" must be inherited from Thread to work...');
					Utils::WriteNewLine();

					unset($this->Threads[$Name]);
				}
				elseif(!in_array('WhatsBotThread', class_uses($this->Threads[$Name][0])))
				{
					Utils::Write('Class "' . get_class($this->Threads[$Name][0]) . '" must use WhatsBotThread (trait). The thread will be loaded, but Whatsapp related functions will be not available...');
					Utils::WriteNewLine();
				}
				else
					$this->Threads[$Name][1] = true;
			}
		}

		public function StartThread($Name)
		{
			$this->Threads[$Name][0]->start(PTHREADS_ALLOW_GLOBALS | PTHREADS_INHERIT_ALL);
		}

		public function ExecuteTasks()
		{
			foreach($this->Threads as $Thread)
			{
				if($Thread[1])
				{
					$Tasks = $Thread[0]->GetTasks();

					foreach($Tasks as $Task)
						if(method_exists($this->Whatsapp, $Task[0]) && is_callable(array($this->Whatsapp, $Task[0]))) // Protect __construct && if not empty Task[0]
							call_user_func_array(array($this->Whatsapp, $Task[0]), $Task[1]);
				}
			}
		}
	}