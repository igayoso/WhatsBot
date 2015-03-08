<?php
	require_once 'Lang.php';

	require_once 'ConfigManagerExceptions.php';

	require_once 'Others/Std.php';
	require_once 'Others/Json.php';

	class Config
	{
		private static $Path = 'config';

		private static $Config = array();

		private static $Lang = null;

		public static function Load()
		{
			self::$Lang = new Lang('ConfigManager');

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
				throw new ConfigException(self::$Lang->Get('exception:no_such_directory', self::$Path));
		}

		public static function Reload()
		{
			return self::Load();
		}

		public static function Get($Filename, $Throw = false)
		{
			if(isset(self::$Config[$Filename]))
				return self::$Config[$Filename];

			Std::Out(self::$Lang->Get('error:no_such_file', $Filename));

			if($Throw)
				throw new ConfigException(self::$Lang->Get('exception:no_such_file', $Filename));

			return false;
		}
	}

	Config::Load();