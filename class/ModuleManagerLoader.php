<?php
	require_once 'ConfigManager.php';

	require_once 'Others/Json.php';

	trait ModuleManagerLoader
	{
		// LoadModules() => return loaded modules
		public function LoadModules()
		{
			$Modules = Config::Get('Modules');

			if($Modules !== false)
			{
				$Keys = array_keys($Modules);

				foreach($Keys as $Key)
					foreach($Modules[$Key] as $Module)
						$this->LoadModule($Key, $Module);

				return true;
			}

			return false;
		}

		private function LoadModule($Key, $Name)
		{
			if($this->KeyExists($Key))
			{
				$Path = "class/Modules/{$Key}_{$Name}";

				$JPath = "{$Path}.json";
				$PPath = "{$Path}.php";

				if(basename(dirname(realpath($JPath))) === 'Modules')
				{
					$Json = Json::Read($JPath); // Show errors

					if($Json !== false && is_readable($PPath))
					{
						$this->Modules[$Key][strtolower($Name)] = array
						(
							'Data' => $Json,
							'File' => $PPath
						);

						return true;
					}
				}
			}

			return false;
		}

		public function LoadCommandModule($Name)
		{
			return $this->LoadModule('Command', $Name);
		}

		public function LoadDomainModule($Name)
		{
			return $this->LoadModule('Domain', $Name);
		}

		public function LoadExtensionModule($Name)
		{
			return $this->LoadModule('Extension', $Name);
		}

		public function LoadMediaModule($Name)
		{
			return $this->LoadModule('Media', $Name);
		}
	}