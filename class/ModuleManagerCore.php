<?php
	class ModuleManagerCore
	{
		private $Modules = array
		(
			'command' => array(),
			'domain' => array(),
			'extension' => array(),
			'multimedia' => array()
		);

		public function LoadModules()
		{
			$Modules = Config::Get('Modules');

			if($Modules !== false)
			{
				$Keys = array_keys($Modules)

				foreach($Keys as $Key)
					foreach($Modules[$Key] as $Module)
						$this->LoadModule($Key, $Modules[$Key]);

				return true;
			}

			return false;
		}

		private function LoadModule($Key, $Name)
		{
			if(in_array($Key, array_keys($this->Modules)))
			{
				$Path = "class/Modules/{$Key}_{$Name}";

				$JPath = "{$Path}.json";
				$PPath = "{$Path}.php";

				if(basename(realpath($JPath)) === 'Modules')
				{
					$Json = Json::Read($JPath);

					if($Json !== false && is_readable($PPath))
					{
						$this->Modules[$Key][strtolower($Name)] = array
						(
							'data' => $Json,
							'file' => $PPath
						);

						return true;
					}
				}
			}

			return false;
		}

		public function LoadCommandModule($Name)
		{
			return $this->LoadModule('command', $Name);
		}

		public function LoadDomainModule($Name)
		{
			return $this->LoadModule('domain', $Name);
		}

		public function LoadExtensionModule($Name)
		{
			return $this->LoadModule('extension', $Name);
		}

		public function LoadMultiMediaModule($Name)
		{
			return $this->LoadModule('multimedia', $Name);
		}


		private function ModuleExists($Key, $Name)
		{
			return !empty($this->Modules[$Key][strtolower($Name)]);
		}


		public function CommandModuleExists($Name)
		{
			return $this->ModuleExists('command', $Name);
		}

		public function DomainModuleExists($Name)
		{
			return $this->ModuleExists('domain', $Name);
		}

		public function ExtensionModuleExists($Name)
		{
			return $this->ModuleExists('extension', $Name);
		}

		public function MultiMediaModuleExists($Name)
		{
			return $this->ModuleExists('multimedia');
		}
	}

	/* To do. 
	 * Make this with interfaces
	 * 
	 * GetModules
	 * GetModuleData
	 * LoadModules() => return loaded modules
	 */