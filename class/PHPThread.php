<?php
	require_once dirname(__FILE__) . '/Lib/_Loader.php';

	require_once dirname(__FILE__) . '/Thread.php';

	class PHPThread extends WhatsBotThread
	{
		protected $PathExtension = 'php';
		protected $Extension = false;

		protected function _Load()
		{ return self::LOADED; }

		protected function Execute()
		{
			$Lang = new Lang("Thread_{$this->Name}");

			$WhatsBot = $this->WhatsBot;
			$WhatsApp = $this->WhatsApp;
			$EventManager = $this->EventManager;
			$ModuleManager = $this->ModuleManager;
			$ThreadManager = $this->ThreadManager;

			while(!$this->Stop && is_readable($this->XPath))
				include($this->XPath);
		}
	}