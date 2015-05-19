<?php
	require_once 'Lib/_Loader.php';

	require_once 'Module.php';

	trait ModuleManagerGetter
	{
		public function GetKeys()
		{
			return array_keys($this->Modules);
		}

		public function GetModules($Key)
		{
			if($this->KeyExists($Key))
				return array_keys($this->Modules[$Key]);

			return false;
		}

		public function GetModule($Key, $Name, $ShowWarn = true)
		{
			$Name = strtolower($Name);

			if($this->ModuleExists($Key, $Name, $ShowWarn) === Module::LOADED)
				return $this->Modules[$Key][$Name];

			if($ShowWarn)
				Std::Out("[WARNING] [MODULES] Trying to get not loaded module. {$Key}::{$Name}");

			return Module::NOT_LOADED;
		}

		abstract public function KeyExists($Key);
		abstract public function ModuleExists($Key, $Name, $ShowWarn = true);
	}