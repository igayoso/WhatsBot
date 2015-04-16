<?php
	require_once 'Lib/_Loader.php';

	require_once 'ModuleManagerExists.php';
	require_once 'ModuleManagerGetter.php';
	require_once 'ModuleManagerLoader.php';

	class ModuleManagerCore
	{
		use ModuleManagerExists;
		use ModuleManagerGetter;
		use ModuleManagerLoader;

		private $Modules = array
		(
			'Command' => array(),
			'Domain' => array(),
			'Extension' => array(),
			'Media' => array()
		);
	}