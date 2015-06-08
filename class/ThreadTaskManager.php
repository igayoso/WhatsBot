<?php
	require_once 'ThreadWhatsBotTasks.php';
	require_once 'ThreadWhatsAppTasks.php';
	require_once 'ThreadModuleManagerTasks.php';

	trait ThreadTaskManager
	{
		private $Tasks = 'a:0:{}'; // serialized empty array, beacuse pthreads nullifies non-simple data types
		private $Return = 'a:0:{}';

		private $WhatsBot = null;
		private $WhatsApp = null;
		private $ModuleManager = null;

		private function LoadTaskManager()
		{
			require_once 'ThreadWhatsBotTasks.php';
			require_once 'ThreadWhatsAppTasks.php';
			require_once 'ThreadModuleManagerTasks.php';

			$this->WhatsBot = new ThreadWhatsBotTasks($this);
			$this->WhatsApp = new ThreadWhatsAppTasks($this);
			$this->ModuleManager = new ThreadModuleManagerTasks($this);
		}

		public function GetTasks()
		{
			$this->Lock();

			$Tasks = unserialize($this->Tasks);
			$this->Tasks = 'a:0:{}';

			$this->Unlock();

			return $Tasks;
		}

		public function AddTask($Type, $Method, Array $Params = array())
		{
			$this->Lock();

			$Tasks = unserialize($this->Tasks);
			$Tasks[] = array($Type, $Method, $Params);
			$this->Tasks = serialize($Tasks);

			$this->Unlock();
		}

		public function SetReturn($Type, $Name, $Value)
		{ // Use with task type?
			$this->Lock();

			$Return = unserialize($this->Return);
			$Return[$Type][$Name] = $Value;
			$this->Return = serialize($Return);

			$this->Unlock();
		}

		private function WaitFor($Type, $Name, $Unset = true)
		{
			if($Unset)
			{
				$this->Lock();

				$Return = unserialize($this->Return);
				unset($Return[$Type][$Name]);
				$this->Return = serialize($Return);

				$this->Unlock();
			}

			while(true)
			{
				$this->Lock();

				$Return = unserialize($this->Return);

				if(isset($Return[$Type][$Name]))
				{
					$Value = $Return[$Type][$Name];
					unset($Return[$Type][$Name]);
					$this->Return = serialize($Return);

					$this->Unlock();

					return $Value;
				}

				$this->Unlock();

				sleep(1);
			}
		}
	}