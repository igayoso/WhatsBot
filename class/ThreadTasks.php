<?php
	trait ThreadTasks
	{
		private static $Tasks = array();

		public function GetTasks()
		{
			$this->Lock();

			$Tasks = self::$Tasks;
			self::$Tasks = array();

			$this->Unlock();

			return $Tasks;
		}

		private function AddTask($Type, $Method, Array $Params = array())
		{
			$this->Lock();

			self::$Tasks[$this->Name][] = array($Type, $Method, $Params);

			$this->Unlock();
		}
	}