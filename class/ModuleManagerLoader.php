<?php
	require_once 'ConfigManager.php';

	require_once 'Others/Std.php';
	require_once 'Others/Json.php';

	trait ModuleManagerLoader
	{
		// LoadModules() => return loaded modules
		public function LoadModules()
		{
			Std::Out('[INFO] [MODULES] Loading');

			$Modules = Config::Get('Modules');

			if(is_array($Modules))
			{ // Show number of loaded modules
				$Keys = array_keys($Modules);

				foreach($Keys as $Key)
					foreach($Modules[$Key] as $Module)
						$this->LoadModule($Key, $Module);

				Std::Out('[INFO] [MODULES] Loaded');

				return true;
			}

			Std::Out('[WARNING] [MODULES] Config file is not an array');

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

					if($Json !== false)
					{
						if(is_readable($PPath)) // Lint
						{
							$this->Modules[$Key][strtolower($Name)] = array
							(
								'Data' => $Json,
								'File' => $PPath
							);

							return true;
						}
						else
							Std::Out("[INFO] [MODULES] Can't load {$Key}::{$Name}. PHP file doesn't exists");
					}
					else
						Std::Out("[INFO] [MODULES] Can't load {$Key}::{$Name}. Json file is not readable");
				}
				else
					Std::Out("[INFO] [MODULES] Can't load {$Key}::{$Name}. It is not in Modules folder");
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