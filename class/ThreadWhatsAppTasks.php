<?php
	const WHATSAPP_TASK = 1;

	trait ThreadWhatsAppTasks
	{
		# User

		private function SetStatus($Message)
		{ $this->AddTask(WHATSAPP_TASK, 'SetStatus', array($Message)); }

		private function SetProfilePicture($Path)
		{ $this->AddTask(WHATSAPP_TASK, 'SetProfilePicture', array($Path)); }

		# Messages

		private function SetLangSection($Section)
		{ $this->AddTask(WHATSAPP_TASK, 'SetLangSection', array($Section)); }

		private function SendMessage($To, $Key, $Pre = null)
		{ $this->AddTask(WHATSAPP_TASK, 'SendMessage', array(func_get_args())); }

		private function SendLangError($To, $Key, Array $Params = array())
		{ $this->AddTask(WHATSAPP_TASK, 'SendLangError', array($To, $Key, $Params)); }

		private function SendRawMessage($To, $Message)
		{ $this->AddTask(WHATSAPP_TASK, 'SendRawMessage', array($To, $Message)); }

		private function SendAudio($To, $Path, $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ $this->AddTask(WHATSAPP_TASK, 'SendAudio', array($To, $Path, $StoreURLMedia, $Size, $Hash)); }

		private function SendImage($To, $Path, $Caption = '', $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ $this->AddTask(WHATSAPP_TASK, 'SendImage', array($To, $Path, $StoreURLMedia, $Size, $Hash, $Caption)); }

		private function SendVideo($To, $Path, $Caption = '', $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ $this->AddTask(WHATSAPP_TASK, 'SendVideo', array($To, $Path, $StoreURLMedia, $Size, $Hash, $Caption)); }
	}