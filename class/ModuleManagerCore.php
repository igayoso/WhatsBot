<?php
	require_once 'ModuleManagerExists.php';
	require_once 'ModuleManagerLoader.php';
	require_once 'ModuleManagerGetter.php';


	const WARNING_NOT_LOADED = -1;
	const WARNING_GET_ERROR = -2;
	const WARNING_NOT_FILE = -3;


	class ModuleManagerCore
	{
		use ModuleManagerExists;
		use ModuleManagerLoader;
		use ModuleManagerGetter;

		private $Modules = array
		(
			'Command' => array(),
			'Domain' => array(),
			'Extension' => array(),
			'Media' => array()
		);
	}