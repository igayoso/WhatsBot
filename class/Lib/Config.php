<?php
	require_once '_Loader.php';

	class Config
	{
		private static $Config = array();

		public static function Load($Reloading = false)
		{
			$Reloading = $Reloading ? 'Rel' : 'L';

			Std::Out("[INFO] [CONFIG] {$Reloading}oading");

			if(is_dir('config'))
			{
				$Files = array_values(array_filter(scandir('config'), 'Config::IsConfigFile'));

				foreach($Files as $File)
					self::LoadFile($File);

				Std::Out('[INFO] [CONFIG] Ready!', 2);

				return true;
			}

			throw new Exception('No such config directory');
		}

		private function LoadFile($File)
		{
			if(substr($File, strlen($File) - 5) !== '.json')
				$File .= '.json';

			$Data = Json::Decode("config/{$File}");

			if(is_array($Data))
				return self::$Config[substr($File, 0, strlen($File) - 5)] = $Data;

			return false;
		}

		public static function Reload($File = null)
		{
			if(empty($File))
				return self::Load(true);

			return self::LoadFile($File);
		}


		public static function Get($Filename, $Throw = false)
		{
			if(isset(self::$Config[$Filename]))
				return self::$Config[$Filename];

			Std::Out("[WARNING] [CONFIG] No such file config/{$Filename}.json. Try to Config::Load()");

			if($Throw)
				throw new Exception("No such file config/{$Filename}.json");

			return false;
		}

		public static function Set($Filename, $Data, $Options = JSON_PRETTY_PRINT)
		{
			$Path = "config/{$Filename}.json";

			$Return = Json::Encode($Path, $Data, $Options);

			self::LoadFile($Filename);

			return $Return;
		}


		public static function IsConfigFile($Path)
		{
			return !is_dir($Path) && pathinfo($Path, PATHINFO_EXTENSION) === 'json';
		}
	}