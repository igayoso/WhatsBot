<?php
	require_once dirname(__FILE__) . '/_Loader.php';

	class Data
	{
		private static $FileManager = null;

		public static function Init($Directory = 'data')
		{
			if(empty(self::$FileManager))
				self::$FileManager = new FileManager($Directory);
		}

		public static function Get($Filename, $JsonIfEmptyExtension = true, $ShowWarning = true, Array $Directories = array())
		{
			self::Init();

			$Extension = self::$FileManager->GetExtension($Filename);

			if(empty($Extension) && $JsonIfEmptyExtension)
				$Filename .= '.' . ($Extension = 'json');

			$Extension = strtolower($Extension);

			if($Extension === 'json')
				return self::$FileManager->GetJson($Filename, $Directories, $ShowWarning);
			else
				return self::$FileManager->Get($Filename, $Directories, $ShowWarning);
		}

		public static function Set($Filename, $Data = array(), $Json = false, $ShowWarning = true, Array $Directories = array())
		{
			self::Init();

			$Extension = self::$FileManager->GetExtension($Filename);

			if(empty($Extension) && (is_array($Data) || $Json))
				$Filename .= '.' . ($Extension = 'json');

			$Extension = strtolower($Extension);

			if($Extension === 'json')
				return self::$FileManager->SetJson($Filename, $Data, $Directories, $ShowWarning);
			else
				return self::$FileManager->Set($Filename, $Data, false, $Directories, $ShowWarning);
		}
	}