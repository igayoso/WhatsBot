<?php
	require_once 'ModuleManagerCore.php';
	require_once 'ModuleManagerCaller.php';

	require_once 'WhatsApp.php';

	class ModuleManager extends ModuleManagerCore
	{
		use ModuleManagerCaller;

		private $WhatsApp = null;

		public function __construct(WhatsApp $WhatsApp)
		{
			$this->WhatsApp = $WhatsApp;
		}
	}