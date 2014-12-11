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

		final protected function AddTask($Type, $Name)
		{
			/*
			 * array_slice(func_get_args(), 2): zend_mm_heap corrupted
			 */

			$Params = func_get_args();
			array_shift($Params);
			array_shift($Params);

			self::$Tasks[] = array($Type, $Name, $Params);
		}

		final protected function SendMessage($To, $Message, $ID = null)
		{
			$this->AddTask(WHATSBOT_WHATSAPP_TASK, 'SendMessage', $To, $Message, $ID);
		}
	}