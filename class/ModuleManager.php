<?php
	class ModuleManager
	{
		private $Caller = null;

		private $Modules = null;

		public function __construct(WhatsBotCaller &$Caller) //WhatsProt &$Whatsapp)
		{
			$this->Caller = &$Caller;

			$this->Modules = array();
		}

		public function LoadModules()
		{
			$Modules = file_get_contents('config/Modules.json');
			$Modules = json_decode($Modules, true)['modules'];

			foreach($Modules as $Module)
			{
				$this->Load($Module);
			}
		}

		public function LoadIncludes()
		{
			$Includes = file_get_contents('config/Modules.json');
			$Includes = json_decode($Includes, true)['includes'];

			foreach($Includes as $Include)
			{
				include($Include);
			}
		}

		private function Load($Name)
		{
			$Filename = "class/modules/{$Name}.json";

			if(is_file($Filename))
			{
				$Data = file_get_contents($Filename);
				$Data = json_decode($Data, true);

				$this->Modules[$Name] = array
				(
					'version' => $Data['version'],
					'code' => $Data['code']
				);
			}
		}

		private function UpdateModule($Name)
		{

		}

		public function CallModule($Name, $Params, $From, $Original, $Data)
		{
			if(isset($this->Modules[strtolower($Name)]))
				return $this->Caller->CallModule($this->Modules[strtolower($Name)]['code'], $Params, $From, $Original, $Data);
			else
				return false;
		}
	}