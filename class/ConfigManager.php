<?php
	require_once 'Lang.php';

	require_once 'ConfigManagerExceptions.php';

	require_once 'Includes/Json.php';

	class Config
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
				throw new ConfigException((new Lang('ConfigManager'))->Get('exception:no_such_directory', self::$Path));
		}

		public static function Reload()
		{
			return self::Load();
		}

		public static function Get($Filename, $Throw = true)
		{
			if(isset(self::$Config[$Filename]))
				return self::$Config[$Filename];

			if($Throw)
				throw new ConfigException((new Lang('ConfigManager'))->Get('exception:cant_read_file', $Filename));

			return false;
		}
	}

	Config::Load();