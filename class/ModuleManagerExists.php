<?php
	require_once 'Lib/_Loader.php';

	trait ModuleManagerExists
	{
		private function KeyExists($Key)
		{
			$Exists = in_array($Key, $this->GetKeys());

			if(!$Exists)
				Std::Out("[WARNING] [MODULES] Key {$Key} doesn't exists");

			return $Exists;
		}

		protected function ModuleExists($Key, $Name)
		{
			return !empty($this->Modules[$Key][strtolower($Name)]);
		}


		public function CommandModuleExists($Name)
		{
			return $this->ModuleExists('Command', $Name);
		}

		public function DomainModuleExists($Name)
		{
			return $this->ModuleExists('Domain', $Name);
		}

		public function ExtensionModuleExists($Name)
		{
			return $this->ModuleExists('Extension', $Name);
		}

		public function MediaModuleExists($Name)
		{
			return $this->ModuleExists('Media', $Name);
		}
	}