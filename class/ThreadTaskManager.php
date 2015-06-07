<?php
	require_once 'ThreadWhatsBotTasks.php';
	require_once 'ThreadWhatsAppTasks.php';

	trait ThreadTaskManager
	{
		private $Tasks = 'a:0:{}'; // serialized empty array, beacuse pthreads nullifies non-simple data types
		private $Return = 'a:0:{}';

		private $WhatsBot = null;
		private $WhatsApp = null;

		private function LoadTaskManager()
		{
			require_once 'ThreadWhatsBotTasks.php';
			require_once 'ThreadWhatsAppTasks.php';

			$this->WhatsBot = new ThreadWhatsBotTasks($this);
			$this->WhatsApp = new ThreadWhatsAppTasks($this);
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

		public function SetReturn($Name, $Value)
		{
			$this->Lock();

			$Return = unserialize($this->Return);
			$Return[$Name] = $Value;
			$this->Return = serialize($Return);

			$this->Unlock();
		}

		private function WaitForReturn($Name)
		{
			while(true)
			{
				$this->Lock();

				$Return = unserialize($this->Return);

				if(isset($Return[$Name]))
				{
					$Value = $Return[$Name];
					unset($Return[$Name]);
					$this->Return = serialize($Return);

					$this->Unlock();

					return $Value;
				}

				$this->Unlock();

				sleep(1);
			}
		}
	}