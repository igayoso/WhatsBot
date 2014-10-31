<?php
	class ModuleManager
	{
		private $WhatsBot = null;
		private $Parser = null;

		private $Modules = null;

		public function __construct(WhatsBotCore &$WB)
		{
			$this->WhatsBot = &$WB;

			$this->Modules = array();
		}

		public function LoadModules()
		{
			// Read list and load;
		}

		public function LoadModule($Name) // Test if already is loaded
		{
			if(is_file("class/modules/{$Name}.mdl.json"))
			{
				$Data = file_get_contents("class/modules/{$Name}.mdl.json");
				$Data = json_decode($Data, true);

				$this->Modules[$Name] = array
				(
					'version' => $Data['version'],
					'code' => $Data['code']
				);
			}
		}

		public function ReloadModule($Name)
		{

		}

		public function UpdateModule($Name)
		{

		}

		public function CallModule($Name, $Params, $Original, $Info)
		{
			if(isset($this->Modules[$Name]))
			{
				try
				{
					eval($this->Modules[$Name]['code']);
				}
				catch (Exception $E)
				{
					var_dump($E); // Do something
				}
			}
			else
				return false;
		}
	}