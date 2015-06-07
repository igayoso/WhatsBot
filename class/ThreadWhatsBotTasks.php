<?php
	require_once 'ThreadTaskManager.php';

	const WHATSBOT_TASK = 1;

	class ThreadWhatsBotTasks
	{
		private $TaskManager = null;

		public function __construct(WhatsBotThread $TaskManager)
		{
			$this->TaskManager = $TaskManager;
		}

		public function Connect($Show = true)
		{ $this->TaskManager->AddTask(WHATSBOT_TASK, 'Start', func_get_args()); }

		public function GetStartTime()
		{ $this->TaskManager->AddTask(WHATSBOT_TASK, 'GetStartTime', func_get_args()); }

		public function _Exit($Code)
		{ $this->TaskManager->AddTask(WHATSBOT_TASK, '_Exit', func_get_args()); }
	}