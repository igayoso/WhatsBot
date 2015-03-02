<?php
	trait ModuleManagerGetter
	{
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

			return false;
		}


		public function GetCommandModule($Name)
		{
			return $this->GetModule('command', $Name);
		}

		public function GetDomainModule($Name)
		{
			return $this->GetModule('domain', $Name);
		}

		public function GetExtensionModule($Name)
		{
			return $this->GetModule('extension', $Name);
		}

		public function GetMultiMediaModule($Name)
		{
			return $this->GetModule('multimedia', $Name);
		}
	}