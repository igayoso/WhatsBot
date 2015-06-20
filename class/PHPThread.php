<?php
	require_once 'Lib/_Loader.php';

	require_once 'Thread.php';

	class PHPThread extends WhatsBotThread
	{
		protected $PathExtension = 'php';
		protected $Extension = false;

		protected function _Load()
		{ return self::LOADED; }

		protected function Execute()
		{
			$LangSection = "Thread_{$this->Name}";
			$Lang = new Lang($LangSection);

			$WhatsBot = $this->WhatsBot;
			$WhatsApp = $this->WhatsApp;
			$EventManager = $this->EventManager;
			$ModuleManager = $this->ModuleManager;
			$ThreadManager = $this->ThreadManager;

			while(!$this->Stop && is_readable($this->XPath))
				include($this->XPath);
		}
	}