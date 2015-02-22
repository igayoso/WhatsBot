<?php
	trait ModuleManagerExists
	{
		protected function ModuleExists($Key, $Name)
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