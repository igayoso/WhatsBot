<?php
	require_once 'Lib/_Loader.php';

	trait ModuleManagerLoader
	{
		public function LoadModules()
		{
			Std::Out();
			Std::Out('[INFO] [MODULES] Loading');

			$Modules = Config::Get('Modules');

			if(is_array($Modules))
			{
				$Keys = array_keys($Modules);

				// $Loaded = array();

				foreach($Keys as $Key)
					foreach($Modules[$Key] as $Module)
						$this->LoadModule($Key, $Module); // $Loaded[$Key][$Module] = $this->LoadModule($Key, $Module) => illegal offset type if $Module is alias (array)

				Std::Out('[INFO] [MODULES] Ready!'); // ($N loaded modules)

				return true; // return $Loaded;
			}

			Std::Out('[WARNING] [MODULES] Config file is not an array');

			return false;
		}

		private function LoadModule($Key, $Name)
		{
			if(is_array($Name))
			{
				if(!empty($Name[0]) && !empty($Name[1]))
				{
					$Filename = $Name[1];
					$Name = $Name[0];
				}
				else
					return false;
			}
			else
				$Filename = $Name;

			if($this->KeyExists($Key))
			{
				$JPath = "class/Modules/{$Key}_{$Filename}.json";
				$PPath = "class/Modules/{$Key}_{$Filename}.php";

				if(basename(dirname(realpath($JPath))) === 'Modules')
				{
					$Json = Json::Decode($JPath);

					if($Json !== false)
					{
						if(is_readable($PPath))
						{
							// Lint

							$this->Modules[$Key][strtolower($Name)] = array
							(
								'Path' => $PPath,
								'File' => $Filename,
								'Data' => $Json
							);

							return true;
						}
						else
							Std::Out("[WARNING] [MODULES] Can't load {$Key}::{$Name}. PHP file is not readable");
					}
					else
						Std::Out("[WARNING] [MODULES] Can't load {$Key}::{$Name}. Json file is not readable");
				}
				else
					Std::Out("[WARNING] [MODULES] Can't load {$Key}::{$Name}. It is not in Modules folder");
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

		abstract public function KeyExists($Key);
	}