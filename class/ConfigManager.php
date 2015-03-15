<?php
	require_once 'ConfigManagerExceptions.php';

	require_once 'Others/Std.php';
	require_once 'Others/Json.php';

	class Config
	{
		private static $Path = 'config';

		private static $Config = array();

		public static function Load($Reloading = false)
		{
			$Reloading = $Reloading ? 'Rel' : 'L';

			Std::Out();
			Std::Out("[INFO] [CONFIG] {$Reloading}oading");

			if(is_dir(self::$Path))
			{
				$Files = array_values(array_filter(scandir(self::$Path), function($Path)
				{
					return !is_dir($Path) && pathinfo($Path, PATHINFO_EXTENSION) === 'json'; // Use Path::
				}));

				foreach($Files as $File)
					self::LoadConfig($File);

				Std::Out('[INFO] [CONFIG] Ready!');
			}
			else
				throw new ConfigException('No such directory ' . self::$Path);
		}

		private static function LoadConfig($File)
		{
			if(substr($File, strlen($File) - 5) !== '.json')
				$File .= '.json';

			$Data = Json::Read(self::$Path . DIRECTORY_SEPARATOR . $File);

			if($Data !== false)
			{
				self::$Config[substr($File, 0, strlen($File) - 5)] = $Data;

				return true;
			}

			return false;
		}

		public static function Reload($File = null)
		{
			if(empty($File))
				return self::Load(true);
			else
				return self::LoadConfig($File);
		}

		public static function Get($Filename, $Throw = false)
		{
			if(isset(self::$Config[$Filename]))
				return self::$Config[$Filename];

			Std::Out("[WARNING] [CONFIG] No such file config/{$Filename}.json. Try to Config::Load()");

			if($Throw)
				throw new ConfigException("No such file config/{$Filename}.json");

			return false;
		}

		public static function Set($Filename, $Data)
		{
			$Path = self::$Path . DIRECTORY_SEPARATOR . $Filename . '.json';

			$Return = Json::Write($Path, $Data);

			self::LoadConfig($Filename);

			return $Return;
		}
	}