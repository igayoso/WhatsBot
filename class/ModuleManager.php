<?php
	require_once 'Lib/_Loader.php';

	require_once 'ModuleManagerCore.php';
	require_once 'ModuleManagerCaller.php';

	require_once 'WhatsBot.php';
	require_once 'WhatsApp.php';

	class ModuleManager extends ModuleManagerCore
	{
		use ModuleManagerCaller;

		private $WhatsBot = null;
		private $WhatsApp = null;

		public function __construct(WhatsBot $WhatsBot, WhatsApp $WhatsApp)
		{
			$this->WhatsBot = $WhatsBot;
			$this->WhatsApp = $WhatsApp;
		}
	}