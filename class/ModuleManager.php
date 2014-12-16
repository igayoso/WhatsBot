<?php
	require_once 'Utils.php';

	class ModuleManager
	{
		private $Caller = null;

		private $Modules = array();
		private $DomainModules = array();
		private $ExtModules = array();
		private $MediaModules = array();
		private $ParserModules = array();

		public function __construct(WhatsBotCaller &$Caller)
		{
			$this->Caller = &$Caller;
		}

		public function LoadModules() // devolver lista de modulos cargados
		{
			$Modules = Utils::GetJson('config/Modules.json');

			if($Modules !== false)
			{
				$Commands = $Modules['commands'];
				$Domains = $Modules['domains'];
				$Extensions = $Modules['exts'];
				$Medias = $Modules['media'];
				$Parsers = array_keys($Modules['parser']);

				foreach($Commands as $Command)
					$this->LoadModule($Command);

				foreach($Domains as $Domain)
					$this->LoadDomainModule($Domain);

				foreach($Extensions as $Extension)
					$this->LoadExtensionModule($Extension);

				foreach($Medias as $Media)
					$this->LoadMediaModule($Media);

				foreach($Parsers as $Parser)
					$this->LoadParserModule($Parser);
			}
		}


		private function LoadModule($Name) // public for !load or !reload
		{
			$JsonFile = "class/modules/cmd_{$Name}.json";
			$PHPFile = "class/modules/cmd_{$Name}.php";

			if(is_file($JsonFile) && is_file($PHPFile) && is_readable($JsonFile) && is_readable($PHPFile))
			{
				$Data = Utils::GetJson($JsonFile);

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

			if(is_file($Filename) && is_readable($Filename))
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
			$Filename = "class/modules/ext_{$Name}.php";

			if(is_file($Filename) && is_readable($Filename))
			{
				$this->ExtModules[strtolower($Name)] = array
				(
					// version,
					'file' => $Filename
				);

				return true;
			}

			return false;
		}

		private function LoadMediaModule($Name)
		{
			$Filename = "class/modules/media_{$Name}.php";

			if(is_file($Filename) && is_readable($Filename))
			{
				$this->MediaModules[strtolower($Name)] = array
				(
					// version,
					'file' => $Filename
				);

				return true;
			}

			return false;
		}

		private function LoadParserModule($Name)
		{
			$Filename = "class/modules/parser_{$Name}.php";

			if(is_file($Filename) && is_readable($Filename))
			{
				$this->ParserModules[strtolower($Name)] = array
				(
					// version,
					'file' => $Filename
				);

				return true;
			}

			return false;
		}


		public function CallModule($ModuleName, $Params, $Me, $ID, $Time, $From, $Name, $Text) // cambiar orden
		{
			$ModuleName = strtolower($ModuleName);

			if(isset($this->Modules[$ModuleName])) // use exists()
				return $this->Caller->CallModule // cambiar orden
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

			return null;
		}

		public function CallDomainModule($ModuleName, $ParsedURL, $URL, $Me, $ID, $Time, $From, $Name, $Text) // cambiar orden
		{
			$ModuleName = strtolower($ModuleName);

			if(isset($this->DomainModules[$ModuleName])) // use exists()
				return $this->Caller->CallDomainModule // cambiar orden
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

			return null;
		}

		public function CallExtensionModule($ModuleName, $Me, $From, $ID, $Type, $Time, $Name, $Text, $URL, $ParsedURL)
		{
			$ModuleName = strtolower($ModuleName);

			if($this->ExtensionModuleExists($ModuleName))
				return $this->Caller->CallExtensionModule
				(
					$ModuleName,
					$this->ExtModules[$ModuleName]['file'],

					$Me,
					$From,
					$ID,
					$Type,
					$Time,
					$Name,
					$Text,

					$URL,
					$ParsedURL
				);

			return null;
		}

		public function CallMediaModule($ModuleName, $Me, $From, $ID, $Type, $Time, $Name, Array $Data) // carga automatica? if is file then load & exec without loadmodules() & modules[media]
		{
			$ModuleName = strtolower($ModuleName);

			if(isset($this->MediaModules[$ModuleName])) // use exists()
				return $this->Caller->CallMediaModule
				(
					$ModuleName,
					$this->MediaModules[$ModuleName]['file'],

					$Me,
					$From,
					$ID,
					$Type,
					$Time,
					$Name,

					$Data
				);

			return null;
		}

		public function CallParserModule($ModuleName, $Me, $From, $ID, $Time, $Name, $Text, $Action, $Object)
		{
			$ModuleName = strtolower($ModuleName);

			if(isset($this->ParserModules[$ModuleName])) // use exists()
				return $this->Caller->CallParserModule
				(
					$ModuleName,
					$this->ParserModules[$ModuleName]['file'],

					$Me,
					$From,
					$ID,
					$Time,
					$Name,
					$Text,

					$Action,
					$Object
				);

			return null;
		}

		public function ModuleExists($Name)
		{
			return isset($this->Modules[strtolower($Name)]);
		}

		public function DomainModuleExists($Name)
		{
			return isset($this->DomainModules[strtolower($Name)]);
		}

		public function ExtensionModuleExists($Name)
		{
			return isset($this->ExtModules[strtolower($Name)]);
		}

		public function MediaModuleExists($Name)
		{
			return isset($this->MediaModules[strtolower($Name)]);
		}

		public function ParserModuleExists($Name)
		{
			return isset($this->ParserModules[strtolower($Name)]);
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

		public function LoadIncludes() // Return list of included files
		{
			$Includes = Utils::GetJson('config/Includes.json');

			foreach($Includes as $Include)
			{
				if(is_string($Include))
					include_once "class/includes/{$Include}";
				else
				{
					$Path = "class/includes/{$Include[0]}";

					if(empty($Include[1]))
						include_once $Path;
					else
						require_once $Path;
				}
			}
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
	 * 
	 * Remake includes system
	 */