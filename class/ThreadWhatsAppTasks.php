<?php
	const WHATSAPP_TASK = 1;

	trait ThreadWhatsAppTasks
	{
		# User

		private function SetStatus($Message)
		{ $this->AddTask(WHATSAPP_TASK, 'SetStatus', func_get_args()); }

		private function SetProfilePicture($Path)
		{ $this->AddTask(WHATSAPP_TASK, 'SetProfilePicture', func_get_args()); }

		# Messages

		private function SetLangSection($Section)
		{ $this->AddTask(WHATSAPP_TASK, 'SetLangSection', func_get_args()); }

		private function SendMessage($To, $Key, $Pre = null)
		{ $this->AddTask(WHATSAPP_TASK, 'SendMessage', func_get_args()); }

		private function SendLangError($To, $Key, Array $Params = array())
		{ $this->AddTask(WHATSAPP_TASK, 'SendLangError', func_get_args()); }

		private function SendRawMessage($To, $Message)
		{ $this->AddTask(WHATSAPP_TASK, 'SendRawMessage', func_get_args()); }

		private function SendAudio($To, $Path, $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ $this->AddTask(WHATSAPP_TASK, 'SendAudio', func_get_args()); }

		private function SendImage($To, $Path, $Caption = '', $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ $this->AddTask(WHATSAPP_TASK, 'SendImage', func_get_args()); }

		private function SendVideo($To, $Path, $Caption = '', $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ $this->AddTask(WHATSAPP_TASK, 'SendVideo', func_get_args()); }
	}