<?php
	trait WhatsBotThread
	{
		private static $Tasks = array();

		final public function GetTasks()
		{
			$this->lock();

			$Tasks = self::$Tasks;
			self::$Tasks = array();

			$this->unlock();

			return $Tasks;
		}

		final protected function AddTask($Name)
		{
			$Params = func_get_args();
			array_shift($Params);

			$this->lock();

			self::$Tasks[] = array($Name, $Params);

			$this->unlock();
		}

		final protected function SendMessage($To, $Message, $ID = null)
		{
			$this->AddTask('SendMessage', $To, $Message, $ID);
		}
	}