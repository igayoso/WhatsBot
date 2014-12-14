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

		final protected function SendAudio($To, $Filepath, $StoreURLMedia = false, $Filesize = 0, $Filehash = '')
		{
			$this->AddTask(WHATSBOT_WHATSAPP_TASK, 'SendAudioMessage', $To, $Filepath, $StoreURLMedia = false, $Filesize = 0, $Filehash = '');
		}

		final protected function SendImage($To, $Filepath, $Caption = '', $StoreURLMedia = false, $Filesize = 0, $Filehash = '')
		{
			$this->AddTask(WHATSBOT_WHATSAPP_TASK, 'SendImageMessage', $To, $Filepath, $Caption = '', $StoreURLMedia = false, $Filesize = 0, $Filehash = '');
		}

		final protected function SendMessage($To, $Message, $ID = null)
		{
			$this->AddTask(WHATSBOT_WHATSAPP_TASK, 'SendMessage', $To, $Message, $ID);
		}

		final protected function SendVCard($To, $Name, $VCard)
		{
			$this->AddTask(WHATSBOT_WHATSAPP_TASK, 'SendVCard', $To, $Name, $VCard);
		}

		final protected function SendVideo($To, $Filepath, $Caption = '', $StoreURLMedia = false, $Filesize = 0, $Filehash = '')
		{
			$this->AddTask(WHATSBOT_WHATSAPP_TASK, 'SendVideoMessage', $To, $Filepath, $Caption = '', $StoreURLMedia = false, $Filesize = 0, $Filehash = '');
		}


		final protected function SetStatus($Status)
		{
			$this->AddTask(WHATSBOT_WHATSAPP_TASK, 'SetStatus', $Status);
		}
	}