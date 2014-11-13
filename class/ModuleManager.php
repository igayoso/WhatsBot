<?php
	require_once 'Utils.php';

	class ModuleManager
	{
		private $Caller = null;
		private $Utils = null;

		private $Modules = array();
		private $PlainModules = array
		(
			'domains' => array(),
			'exts' => array()
		);

		public function __construct(WhatsBotCaller &$Caller) //WhatsProt &$Whatsapp)
		{
			$this->Caller = &$Caller;
			$this->Utils = new Utils();
		}

		public function LoadModules() // devolver lista de modulos cargados
		{
			$Modules = $this->Utils->getJson('config/Modules.json');

			if($Modules !== false)
			{
				if(isset($Modules['modules']['commands']))
				{
					$Modules = $Modules['modules']['commands'];

					foreach($Modules as $Module)
						$this->LoadModule($Module);

					return true;
				}
			}

			return false;
		}

		private function LoadModule($Name) // public for !load or !reload
		{
			/*
			 * Por qué no cargar el code desde un php y luego hacer eval? Porque eval tira error con <?php. Entonces, hacemos un include cada vez que queramos llamar al módulo
			 */

			$JsonFile = "class/modules/{$Name}.json";
			$PHPFile = "class/modules/{$Name}.php";

			if(is_file($JsonFile) && is_file($PHPFile))
			{
				$Data = $this->Utils->getJson($JsonFile);

				$this->Modules[strtolower($Name)] = array
				(
					'help' => (isset($Data['help'])) ? $Data['help'] : null,
					'version' => $Data['version'],
					'file' => $PHPFile
				);

				return true;
			}

			return false;
		}

		public function CallModule($ModuleName, $Params, $Me, $ID, $Time, $From, $Name, $Text)
		{
			$ModuleName = strtolower($ModuleName);

			if(isset($this->Modules[$ModuleName]))
				return $this->Caller->CallModule
				(
					$ModuleName,
					$this->Modules[$ModuleName]['file'],
					$Params,

					$Me,
					$ID,
					$Time,
					$From,
					$Name,
					$Text
				);
			else
				return false;
		}

		

		public function LoadPlainModules()
		{
			$Modules = file_get_contents('config/Modules.json');
			$Modules = json_decode($Modules, true)['modules']['plain'];

			$Domains = $Modules['domains'];
			$Extensions = $Modules['exts'];

			foreach($Domains as $Domain)
				$this->LoadDomainPlainModule($Domain);

			//foreach($Extensions as $Extension)
			//	$this->LoadExtensionPlainModule($Extension);
		}

		private function LoadDomainPlainModule($Name)
		{
			$Filename = "class/modules/plain/domain_{$Name}.json";

			if(is_file($Filename))
			{
				$Data = file_get_contents($Filename);
				$Data = json_decode($Data, true);

				$this->PlainModules['domains'][$Name] = array
				(
					'version' => $Data['version'],
					'code' => $Data['code']
				);

				return true;
			}

			return false;
		}

		public function CallDomainPlainModule($Name, $From, $Data, $URL, $ParsedURL) // add info and change order
		{
			if(isset($this->PlainModules['domains'][strtolower($Name)])) // Only one strtolower
				return $this->Caller->CallDomainPlainModule
				(
					$this->PlainModules['domains'][strtolower($Name)]['code'],
					$From,
					$Data,
					$URL,
					$ParsedURL
				);
			else
				return false;
		}

		public function LoadIncludes() // están disponibles fuera del ambito local? D:
		{
			$Includes = $this->Utils->getJson('config/Modules.json');

			if(isset($Includes['includes']))
			{
				$Includes = $Includes['includes'];

				foreach($Includes as $Include)
					$this->LoadInclude($Include);

				return true;
			}

			return false;
		}

		private function LoadInclude($Path)
		{
			if(is_file($Path))
			{
				include($Path);

				return true;
			}

			return false;
		}

		public function ModuleExists($Name)
		{
			return isset($this->Modules[strtolower($Name)]);
		}

		public function GetModules()
		{
			return array_keys($this->Modules);
		}

		public function GetModuleHelp($Name)
		{
			$Name = strtolower($Name);

			if(isset($this->Modules[$Name]) && isset($this->Modules[$Name]['help']) && $this->Modules[$Name]['help'] != null)
				return $this->Modules[$Name]['help'];

			return false;
		}
	}

	/*
	 * To do: 
	 * 
	 * UpdateModules
	 * UpdateModule
	 * 
	 * GetModule_* (all)
	 * GetModuleCode
	 * GetModuleVersion
	 * 
	 * LoadDomain/ExtensionsModules instead LoadPlainModules?
	 * 
	 * Retornar modulos e includes cargados, como array
	 * 
	 * Buscar strtolowers olvidados xD
	 */