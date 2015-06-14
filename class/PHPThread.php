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

			$Path = "class/Threads/{$this->Name}.php";

			$WhatsBot = $this->WhatsBot;
			$WhatsApp = $this->WhatsApp;
			$EventManager = $this->EventManager;
			$ModuleManager = $this->ModuleManager;
			$ThreadManager = $this->ThreadManager;

			while(!$this->Stop && is_readable($Path))
				include($Path);

			Std::Out();
			Std::Out("[Info] [Threads] {$this->Name} stopped ($this->Stop)");
		}
	}