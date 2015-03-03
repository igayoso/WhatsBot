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
			return $this->ModuleExists('Command', $Name);
		}
	}