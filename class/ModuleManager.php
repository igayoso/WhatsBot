<?php
	class ModuleManager
	{
		private $Whatsapp = null;

		private $Modules = null;

		public function __construct(WhatsProt &$Whatsapp)
		{
			$this->Whatsapp = &$Whatsapp;

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

		private function Load($Name)
		{ // Test if module is already loaded
			$Filename = "modules/{$Name}.json";

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

		public function CallModule($Name, $Params, $Original, $Data)
		{
			if(isset($this->Modules[$Name]))
				eval($this->Modules[$Name]['code']);
			else
				return false;
		}
	}