<?php
	require_once 'ThreadTaskManager.php';

	const WHATSAPP_TASK = 2;

	class ThreadWhatsAppTasks
	{
		private $TaskManager = null;

		public function __construct(WhatsBotThread $TaskManager)
		{
			$this->TaskManager = $TaskManager;
		}

		# User

		public function SetStatus($Message)
		{ $this->TaskManager->AddTask(WHATSAPP_TASK, 'SetStatus', func_get_args()); }

		public function SetProfilePicture($Path)
		{ $this->TaskManager->AddTask(WHATSAPP_TASK, 'SetProfilePicture', func_get_args()); }

		# Messages

		public function SetLangSection($Section)
		{ $this->TaskManager->AddTask(WHATSAPP_TASK, 'SetLangSection', func_get_args()); }

		public function SendMessage($To, $Key, $Pre = null)
		{ $this->TaskManager->AddTask(WHATSAPP_TASK, 'SendMessage', func_get_args()); }

		public function SendLangError($To, $Key, Array $Params = array())
		{ $this->TaskManager->AddTask(WHATSAPP_TASK, 'SendLangError', func_get_args()); }

		public function SendRawMessage($To, $Message)
		{ $this->TaskManager->AddTask(WHATSAPP_TASK, 'SendRawMessage', func_get_args()); }

		public function SendAudio($To, $Path, $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ $this->TaskManager->AddTask(WHATSAPP_TASK, 'SendAudio', func_get_args()); }

		public function SendImage($To, $Path, $Caption = '', $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ $this->TaskManager->AddTask(WHATSAPP_TASK, 'SendImage', func_get_args()); }

		public function SendVideo($To, $Path, $Caption = '', $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ $this->TaskManager->AddTask(WHATSAPP_TASK, 'SendVideo', func_get_args()); }
	}