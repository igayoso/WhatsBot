<?php
	require_once 'Thread.php';

	const WHATSBOT = 1;

	class ThreadWhatsBotTasks
	{
		private $TaskManager = null;

		public function __construct(WhatsBotThread $TaskManager)
		{
			$this->TaskManager = $TaskManager;
		}

		public function Connect($Show = true)
		{ $this->TaskManager->AddTask(WHATSBOT, 'Start', func_get_args()); }

		public function GetStartTime()
		{ $this->TaskManager->AddTask(WHATSBOT, 'GetStartTime', func_get_args()); }

		public function _Exit($Code)
		{ $this->TaskManager->AddTask(WHATSBOT, '_Exit', func_get_args()); }
	}