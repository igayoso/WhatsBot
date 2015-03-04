<?php
	require_once 'ModuleManagerExists.php';
	require_once 'ModuleManagerLoader.php';
	require_once 'ModuleManagerGetter.php';

	class ModuleManagerCore
	{
		use ModuleManagerExists;
		use ModuleManagerLoader;
		use ModuleManagerGetter;

		private $Modules = array
		(
			'Command' => array(),
			'Domain' => array(),
			'Extension' => array()
		);
	}