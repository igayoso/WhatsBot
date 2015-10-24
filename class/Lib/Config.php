<?php
	require_once __DIR__ . '/_Loader.php';

	class Config
	{
		private static $FileManager = null;

		private static $Config = array();

		public static function Init($Directory = 'config')
		{
			if(empty(self::$FileManager))
			{
				self::$FileManager = new FileManager($Directory);

				self::Load();
			}
		}

		private static function Load($Reloading = false)
		{
			Std::Out();
			Std::Out('[Info] [Config] ' . ($Reloading ? 'Reloading' : 'Loading'));

			$DirectoryLength = strlen(self::$FileManager->GetDirectory());

			$Files = array_map(function($Filename) use($DirectoryLength) { return substr($Filename, $DirectoryLength + 1); }, self::$FileManager->Glob('*.json'));

			foreach($Files as $File)
				self::LoadFile($File);

			Std::Out('[Info] [Config] Ready!');

			return true;
		}

		private static function LoadFile($Filename)
		{
			$Filename = self::AppendJsonExtension($Filename);

			$Data = self::$FileManager->GetJson($Filename);

			if($Data !== false)
				return self::$Config[self::$FileManager->GetFilename($Filename)] = $Data;

			return false;
		}

		public static function Reload($Filename = null)
		{
			self::Init();

			if(empty($Filename))
				return self::Load(true);

			return self::LoadFile($Filename);
		}

		public static function Get($Filename, $ShowWarning = true, $Throw = false)
		{
			self::Init();

			if(isset(self::$Config[$Filename]))
				return self::$Config[$Filename];

			if($ShowWarning)
				Std::Out('[Warning] [Config] No such file ' . self::$FileManager->GetDirectory() . "/{$Filename}.json");

			if($Throw)
				throw new Exception('No such file ' . self::$FileManager->GetDirectory() . "/{$Filename}.json");

			return false;
		}

		public static function Set($Filename, $Data, $JsonOptions = JSON_PRETTY_PRINT)
		{
			self::Init();

			self::$FileManager->SetJsonOptions($JsonOptions);

			return self::$FileManager->SetJson(self::AppendJsonExtension($Filename), $Data) && self::Reload($Filename);
		}

		private static function AppendJsonExtension($Filename)
		{
			if(substr($Filename, strlen($Filename) - 5) !== '.json')
				$Filename .= '.json';

			return $Filename;
		}
	}