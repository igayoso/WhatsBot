<?php
	require_once 'Includes/Json.php';

	class ConfigManager
	{
		private static $Path = 'config';

		private static $Config = array();

		public static function Load()
		{
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
			}
			else
				throw new ConfigException("No such directory 'config'");
		}

		public static function Reload()
		{
			self::Load();
		}

		public static function Get($Filename)
		{
			if(isset(self::$Config[$Filename]))
				return self::$Config[$Filename];

			return false;
		}
	}

	class ConfigException extends Exception
	{ }

	ConfigManager::Load();