<?php
	require_once 'ModuleManagerLoad.php';
	require_once 'ModuleManagerExists.php';
	require_once 'ModuleManagerGet.php';

	class ModuleManagerCore
	{
		use ModuleManagerLoad;
		use ModuleManagerExists;
		use ModuleManagerGet;

		private $Modules = array
		(
			'command' => array(),
			'domain' => array(),
			'extension' => array(),
			'multimedia' => array()
		);
	}

	/* To do. 
	 * Make this with interfaces
	 * 
	 * GetModules
	 * GetModuleData
	 * LoadModules() => return loaded modules
	 */