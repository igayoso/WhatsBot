<?php
	abstract class WhatsBotThread extends Thread
	{
		private static $Tasks = array();

		final public function GetTasks()
		{
			$Tasks = self::$Tasks;
			self::$Tasks = array();

			return $Tasks;
		}

		final protected function AddTask($Name)
		{
			$Params = func_get_args();
			array_shift($Params);

			self::$Tasks[] = array($Name, $Params);
		}

		final protected function SendMessage($To, $Message, $ID = null)
		{
			$this->AddTask('SendMessage', $To, $Message, $ID);
		}
	}