<?php
	require_once '_Loader.php';

	class Data
	{
		private static $Directory = 'data';

		public static function CreateDirectory($Directory = null)
		{
			if(empty($Directory))
				$Path = self::$Directory;
			else
				$Path = self::GetPath($Directory);

			if(!is_dir($Path))
				return mkdir($Path);

			return true;
		}

		public static function Get($FileName, $JsonIfEmptyExtension = true, $ShowWarning = true)
		{
			self::CreateDirectory();

			$Extension = self::GetExtension($FileName);

			if(empty($Extension) && $JsonIfEmptyExtension)
			{
				$FileName .= '.json';
				$Extension = 'json';
			}

			$Path = self::GetPath($FileName);

			if(is_readable($Path))
			{
				switch(strtolower($Extension))
				{
					case 'json':
						$Json = Json::Decode($Path);

						if($Json !== false)
							return $Json;
						break;
					default:
						return file_get_contents($Path);
						break;
				}
			}
			elseif($ShowWarning)
				Std::Out("[Warning] [Data] {$Path} is not readable");
		}

		public static function Set($FileName, $Data = array(), $Json = false, $ShowWarning = true)
		{
			self::CreateDirectory();

			$Extension = self::GetExtension($FileName);

			if(empty($Extension) && (is_array($Data) || $Json))
			{
				$FileName .= '.json';
				$Extension = 'json';
			}

			$Path = self::GetPath($FileName);

			switch(strtolower($Extension))
			{
				case 'json':
					return Json::Encode($Path, $Data);
					break;
				default:
					$ToWrite = strlen($Data);
					$Written = file_put_contents($Path, $Data);

					if($Written === $ToWrite)
						return true;

					if($ShowWarning)
						Std::Out("[Warning] [Data] {$Path} : {$Written} bytes written of {$ToWrite}");
					break;
			}

			return false;
		}

		private static function GetPath($FileName)
		{ return self::$Directory . DIRECTORY_SEPARATOR . $FileName; }

		private static function GetExtension($FileName)
		{ return pathinfo($FileName, PATHINFO_EXTENSION); }
	}