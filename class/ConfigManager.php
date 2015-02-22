<?php
	require_once 'Includes/Json.php';

	class ConfigManager
	{
		private $Config = array();

		public function __construct()
		{
			if(is_dir('../config'))
			{
				$Files = array_values(array_filter(scandir('../config'), function($Path)
				{
					return !is_dir($Path) && pathinfo($Path, PATHINFO_EXTENSION) === 'json';
				}));

				foreach($Files as $File)
				{
					$Data = Json::Read("../config/{$File}");

					if($Data !== false)
						$this->Config[substr($File, 0, strlen($File) - 5)] = $Data;
				}
			}
			else
				throw new ConfigException("No such directory 'config'");
		}

		public function Reload()
		{
			$this->__construct();
		}

		public function Get($Filename)
		{
			if(isset($this->Config[$Filename]))
				return $this->Config[$Filename];

			return false;
		}
	}

	class ConfigException extends Exception
	{ }