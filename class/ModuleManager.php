<?php
	require_once __DIR__ . '/Lib/_Loader.php';

	require_once __DIR__ . '/ModuleManagerExists.php';
	require_once __DIR__ . '/ModuleManagerGetter.php';
	require_once __DIR__ . '/ModuleManagerLoader.php';

	require_once __DIR__ . '/WhatsBot.php';
	require_once __DIR__ . '/WhatsApp.php';

	class ModuleManager
	{
		use ModuleManagerExists;
		use ModuleManagerGetter;
		use ModuleManagerLoader;

		private $WhatsBot = null;
		private $WhatsApp = null;

		private $Modules = array
		( // What if we don't create this ? Traits should be protected (getter/setter?)
			// Avoid this, load it all dinamically from Modules.json
			'Command' => array(),
			'Domain' => array(),
			'Extension' => array(),
			'Media' => array(),
			'Event' => array()
		);

		public function __construct(WhatsBot $WhatsBot, WhatsApp $WhatsApp)
		{
			$this->WhatsBot = $WhatsBot;
			$this->WhatsApp = $WhatsApp;
		}
	}