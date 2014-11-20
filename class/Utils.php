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

		public static function GetURLs($Text)
		{
			preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $Text, $URLs);
			$URLs = $URLs[0];

			if($URLs !== array())
				return $URLs;

			return false;
		}





/*
// To do. 
fwrite stdin


		public static function getConfig($Key)
		{
			$Data = file_get_contents('config/WhatsBot.json');
			$Data = json_decode($Data, true);

			return (isset($Data[$Key])) ? $Data[$Key] : false;
		}



		public static function getOrigin($FromData)
		{
			if($FromData['from'] == 'group')
				return $FromData['g'];
			else
				return $FromData['u'];
		}

		public static function getParams($Module, $Original, $Else) // Tal vez llamar a la ayuda en lugar de devolver $Else
		{
			$Length = strlen($Module) + 2; // Example '! echo $PARAMS' 1 + 4 + 1 - $PARAMS

			$D = substr($Original, $Length);

			return ($D !== false) ? $D : $Else; // Agregar constante con la respuesta específica de !help para este comando
		}

		public static function isGroup($From)
		{
			return substr($From, -strlen('@g.us')) === '@g.us';
		}

		public static function isAdmin($From)
		{
			$Admins = file_get_contents('config/WhatsBot.json');
			$Admins = json_decode($Admins, true)['whatsapp'][4];

			if(in_array($From, $Admins))
				return true;

			return false;
		}

		public static function GetNumberFromJID($JID)
		{
			return substr($JID, 0, strpos($JID, '@'));
		}

		public static function getNumberFromPrivateMessage($From)
		{
			//return str_replace(search, replace, subject)
			//n@s.whatsapp.net
			//n-g@g.us
		}

		public static function getNumberFromGroup($From)
		{

		}*/
	}