<?php
	class Utils
	{
		public function getOrigin($FromData)
		{
			if($FromData['from'] == 'group')
				return $FromData['g'];
			else
				return $FromData['u'];
		}

		public function isGroup($From)
		{
			return substr($From, -strlen('@g.us')) === '@g.us';
		}

		public function isAdmin($From)
		{
			$Admins = file_get_contents('config/WhatsBot.json');
			$Admins = json_decode($Admins, true)['whatsapp'][4];

			if(in_array($From, $Admins))
				return true;

			return false;
		}

		public function getNumberFromPrivateMessage($From)
		{
			//return str_replace(search, replace, subject)
			//n@s.whatsapp.net
			//n-g@g.us
		}

		public function getNumberFromGroup($From)
		{

		}
	}