<?php
	require_once dirname(__FILE__) . '/../Lib/_Loader.php';

	require_once dirname(__FILE__) . '/../WhatsBot.php';

	require_once dirname(__FILE__) . '/../WhatsApp.php';

	require_once dirname(__FILE__) . '/../WhatsApp/TextMessage.php';

	require_once dirname(__FILE__) . '/../WhatsApp/AudioMessage.php';
	require_once dirname(__FILE__) . '/../WhatsApp/ImageMessage.php';
	require_once dirname(__FILE__) . '/../WhatsApp/LocationMessage.php';
	require_once dirname(__FILE__) . '/../WhatsApp/VideoMessage.php';
	require_once dirname(__FILE__) . '/../WhatsApp/vCardMessage.php';

	require_once dirname(__FILE__) . '/../Parser.php';

	require_once dirname(__FILE__) . '/../ModuleManager.php';
	require_once dirname(__FILE__) . '/../ThreadManager.php';

	abstract class WhatsBotListenerCore
	{
		protected $WhatsBot = null;

		protected $WhatsApp = null;

		protected $Parser = null;

		protected $ModuleManager = null;
		protected $ThreadManager = null;

		public function __construct(WhatsBot $WhatsBot, WhatsApp $WhatsApp, WhatsBotParser $Parser, ModuleManager $ModuleManager, ThreadManager $ThreadManager)
		{
			$this->WhatsBot = $WhatsBot;
			$this->WhatsApp = $WhatsApp;
			$this->Parser = $Parser;
			$this->ModuleManager = $ModuleManager;
			$this->ThreadManager = $ThreadManager;

			$this->Load();
		}

		abstract protected function Load();
	}