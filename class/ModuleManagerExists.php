<?php
	require_once 'Others/Std.php';

	trait ModuleManagerExists
	{
		private function KeyExists($Key)
		{
			$Exists = in_array($Key, array_keys($this->Modules));

			if(!$Exists)
				Std::Out("[WARNING] [MODULES] Key {$Key} doesn't exists");
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