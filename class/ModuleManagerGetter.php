<?php
	require_once 'Others/Std.php';

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

		protected function GetModule($Key, $Name)
		{
			$Name = strtolower($Name);

			if($this->ModuleExists($Key, $Name))
				return $this->Modules[$Key][$Name];

			Std::Out("[WARNING] [MODULES] Trying to get not loaded module. {$Key}::{$Name}");

			return false;
		}


		public function GetCommandModule($Name)
		{
			return $this->GetModule('Command', $Name);
		}

		public function GetDomainModule($Name)
		{
			return $this->GetModule('Domain', $Name);
		}

		public function GetExtensionModule($Name)
		{
			return $this->GetModule('Extension', $Name);
		}

		public function GetMediaModule($Name)
		{
			return $this->GetModule('Media', $Name);
		}
	}