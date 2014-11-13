<?php
	require_once 'Utils.php';

	class ModuleManager
	{
		private $Caller = null;
		private $Utils = null;

		private $Modules = array();
		private $DomainModules = array();
		private $ExtModules = array();

		public function __construct(WhatsBotCaller &$Caller)
		{
			$this->Caller = &$Caller;
			$this->Utils = new Utils();
		}

		public function LoadModules() // devolver lista de modulos cargados
		{
			$Modules = $this->Utils->getJson('config/Modules.json');

			if($Modules !== false)
			{
				$Commands = $Modules['modules']['commands'];
				$Domains = $Modules['modules']['domains'];
				$Extensions = $Modules['modules']['exts'];

				foreach($Commands as $Command)
					$this->LoadModule($Command);

				foreach($Domains as $Domain)
					$this->LoadDomainModule($Domain);

				foreach($Extensions as $Extension)
					;//$this->LoadExtensionModule($Extension);
			}
		}

		private function LoadModule($Name) // public for !load or !reload
		{
			$JsonFile = "class/modules/cmd_{$Name}.json";
			$PHPFile = "class/modules/cmd_{$Name}.php";

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

		private function LoadDomainModule($Name)
		{
			$Filename = "class/modules/domain_{$Name}.php";

			if(is_file($Filename))
			{
				$this->DomainModules[strtolower($Name)] = array
				(
					// version,
					'file' => $Filename
				);

				return true;
			}

			return false;
		}

		private function LoadExtensionModule($Name)
		{
		}

		private function LoadMediaModules($Name)
		{
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

			return false;
		}

		public function CallDomainModule($ModuleName, $ParsedURL, $URL, $Me, $ID, $Time, $From, $Name, $Text)
		{
			$ModuleName = strtolower($ModuleName);

			if(isset($this->DomainModules[$ModuleName]))
				return $this->Caller->CallDomainModule
				(
					$ModuleName,
					$this->DomainModules[$ModuleName]['file'],

					$ParsedURL,
					$URL,

					$Me,
					$ID,
					$Time,
					$From,
					$Name,
					$Text
				);

			return false;
		}

		public function CallExtensionModule($Params)
		{
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