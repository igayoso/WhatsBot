<?php
	require_once 'ConfigManager.php';

	require_once 'Others/Std.php';

	class Admin
	{
		public static function GetList()
		{
			return Config::Get('Admins');
		}

		public static function Is($Number)
		{
			$Number = self::GetNumber($Number);

			$Admins = Config::Get('Admins');

			if($Admins !== false)
				return in_array($Number, self::GetNumbersFromList($Admins));

			return false;
		}

		public static function Add($Number, $Nickname = null)
		{
			$Admins = Config::Get('Admins');

			if($Admins !== false)
			{
				if(!in_array($Number, self::GetNumbersFromList($Admins)))
				{
					$Admins[] = array($Number, $Nickname);

					$Saved = Config::Set('Admins', $Admins);

					if($Saved)
						Std::Out("[INFO] [ADMIN] {$Number}" . (empty($Nickname) ? null : ":{$Nickname}") . " added");
					else
						Std::Out("[WARNING] [ADMIN] Error while trying to add {$Number}" . (empty($Nickname) ? null : ":{$Nickname}"));

					return $Saved;
				}
				else
				{
					Std::Out("[INFO] [ADMIN] {$Number}" . (empty($Nickname) ? null : ":{$Nickname}") . " already exists in admin list");

					return 1;
				}
			}
			else
			{
				$Saved = Config::Set('Admins', array(array($Number, $Nickname)));

				if($Saved)
					Std::Out("[INFO] [ADMIN] {$Number}" . (empty($Nickname) ? null : ":{$Nickname}") . " added");
				else
					Std::Out("[WARNING] [ADMIN] Error while trying to add {$Number}" . (empty($Nickname) ? null : ":{$Nickname}"));

				return $Saved;
			}
		}

		// Delete (Index key | Nickname)

		private static function GetNumber($Number)
		{
			$Pos = strpos($Number, '@');

			if($Pos !== false)
				return substr($Number, 0, $Pos);

			return $Number;
		}

		private static function GetNumbersFromList($Admins)
		{
			$Function = function($Admin)
			{ return $Admin[0]; };

			return array_map($Function, $Admins);
		}
	}

	// Why array_map instead array_column?
	// Array_colum is only available in PHP >= 5.5.0