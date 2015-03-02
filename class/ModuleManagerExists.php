<?php
	trait ModuleManagerExists
	{
		private function KeyExists($Key)
		{
			return in_array($Key, array_keys($this->Modules));
		}

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