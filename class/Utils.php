<?php
	class Utils
	{
		public function makeFrom($FromG, $FromU)
		{
			if($FromG != null)
				$From = array
				(
					'from' => 'group',
					'g' => $FromG,
					'u' => $FromU
				);
			else
				$From = array
				(
					'from' => 'privmsg',
					'u' => $FromU
				);

			return $From;
		}

		public function getOrigin($FromData)
		{
			if($FromData['from'] == 'group')
				return $FromData['g'];
			else
				return $FromData['u'];
		}

		public function getParams($Module, $Original, $Else) // Tal vez llamar a la ayuda en lugar de devolver $Else
		{
			$Length = strlen($Module) + 2; // Example '! echo $PARAMS' 1 + 4 + 1 - $PARAMS

			$D = substr($Original, $Length);

			return ($D !== false) ? $D : $Else; // Agregar constante con la respuesta específica de !help para este comando
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