<?php
	const WHATSBOT_TASK = 1;

	trait ThreadWhatsBotTasks
	{
		private function Connect($Show = true)
		{ $this->AddTask(WHATSBOT_TASK, 'Start', func_get_args()); }

		private function GetStartTime()
		{ $this->AddTask(WHATSBOT_TASK, 'GetStartTime', func_get_args()); }

		private function _Exit($Code)
		{ $this->AddTask(WHATSBOT_TASK, '_Exit', func_get_args()); }
	}