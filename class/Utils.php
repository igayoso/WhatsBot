<?php
	class Utils
	{
		public static function GetJson($Filename)
		{
			if(is_file($Filename) && is_readable($Filename))
			{
				$Data = file_get_contents($Filename);

				if($Data !== false)
				{
					$Data = json_decode($Data, true);

					if($Data !== false)
						return $Data;
				}
			}

			return false;
		}

		public static function MakeFrom($FromGroup, $FromUser)
		{
			if($FromGroup != null)
				$From = array
				(
					'from' => 'group',
					'g' => $FromGroup,
					'u' => $FromUser
				);
			else
				$From = array
				(
					'from' => 'pv',
					'u' => $FromUser
				);

			return $From;
		}

		public static function GetFrom($FromData)
		{
			if($FromData['from'] == 'group')
				return $FromData['g'];
			else
				return $FromData['u'];
		}

		/*public static function IsAdmin($From)
		{
			if(is_array($From))
				$From = Utils::GetNumberFromJID($From['u'], false);
			else if(substr_count($From, '@') > 0)
				$From = Utils::GetNumberFromJID($From, false);

			if($From !== false)
			{
				$Admins = Utils::GetJson('config/WhatsBot.json');

				if(isset($Admins['whatsapp']['admins']))
					return in_array($From, $Admins['whatsapp']['admins']);
			}

			return false;
		}*/

		public static function GetNumberFromJID($JID, $Group = false) // Delete group function?
		{
			return substr($JID, 0, strpos($JID, ($Group) ? '-' : '@'));
		}

		public static function GetGroupIDFromJID($JID)
		{

		}

		public static function GetText($ModuleName, $OriginalText, $Else = false)
		{
			$Text = substr($OriginalText, strlen($ModuleName) + 2);

			return ($Text !== false) ? $Text : $Else;
		}

		public static function GetURLs($Text)
		{
			preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $Text, $URLs);
			$URLs = $URLs[0];

			if($URLs !== array())
				return $URLs;

			return false;
		}

		// GetRemoteFile($URL, $SucessHeader = 200, $ParseURL = true);

		public static function Write($Text, $WithNewLine = true)
		{
			fwrite(SDOUT, $Text . ($WithNewLine) ? PHP_EOL : '');
		}

		/*public static function getConfig($Key)
		{
			$Data = file_get_contents('config/WhatsBot.json');
			$Data = json_decode($Data, true);

			return (isset($Data[$Key])) ? $Data[$Key] : false;
		}

		public static function isGroup($From)
		{
			return substr($From, -strlen('@g.us')) === '@g.us';
		}*/
	}