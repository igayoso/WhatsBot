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
					return !is_dir($Path) && pathinfo($Path, PATHINFO_EXTENSION) === 'json';
				}));

				foreach($Files as $File)
				{
					$Data = Json::Read(self::$Path . '/' . $File);

					if($Data !== false)
						self::$Config[substr($File, 0, strlen($File) - 5)] = $Data;
				}

				Std::Out('[INFO] [CONFIG] Ready!');
			}
			else
				throw new ConfigException('No such directory ' . self::$Path);
		}

		public static function Reload()
		{
			return self::Load(true);
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
	}