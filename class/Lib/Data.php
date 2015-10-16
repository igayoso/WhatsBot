<?php
	require_once '_Loader.php';

	class Data
	{
		private static $Directory = 'data';

		private static function CreateDirectory()
		{
			if(!is_dir(self::$Directory))
				mkdir(self::$Directory);
		}

		public static function Get($FileName, $JsonIfEmpty = true)
		{
			self::CreateDirectory();

			$Extension = self::GetExtension($FileName);

			if(empty($Extension) && $JsonIfEmpty)
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
			else
				Std::Out("[Warning] [Data] {$Path} is not readable");
		}

		public static function Set($FileName, $Data = array(), $Json = false)
		{
			self::CreateDirectory();

			$Extension = self::GetExtension($FileName);

			if(is_array($Data) || $Json)
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

					if($Written = $ToWrite)
						return true;

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