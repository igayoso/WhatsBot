<?php
	require_once 'WhatsApp.php';

	require_once 'ModuleManager.php';

	require_once 'Lang.php';

	require_once 'Others/URL.php';
	require_once 'Others/Path.php';

	class WhatsBotParser
	{
		private $WhatsApp = null;

		private $ModuleManager = null;

		private $Char = null;

		public function __construct(WhatsApp $WhatsApp, ModuleManager $ModuleManager, $Char = '!')
		{
			$this->WhatsApp = $WhatsApp;
			$this->ModuleManager = $ModuleManager;

			$this->Char = $Char;
		}

		public function ParseTextMessage($Me, $From, $User, $ID, $Type, $Time, $Name, $Text)
		{
			if(!empty($Text) && $Text[0] === $this->Char)
			{
				$this->ParseCommandMessage($Me, $From, $User, $ID, $Type, $Time, $Name, $Text);
			}
			elseif(is_array($URLs = URL::ParseFor($Text)))
			{
				foreach($URLs as $URL)
				{
					$this->ParseURLMessage($Me, $From, $User, $ID, $Type, $Time, $Name, $Text, $URL);
				}
			}

			// Parser

			// Send help if is pv ?

			// AI ?
		}

		// Test if module is only available for admins

		private function ParseCommandMessage($Me, $From, $User, $ID, $Type, $Time, $Name, $Text)
		{
			$Parsed = explode(' ', substr($Text, 1));

			if(!empty($Parsed[0]))
			{
				$Response = $this->ModuleManager->CallCommandModule
				(
					$Parsed[0],

					$Me,
					$From,
					$User,
					$ID,
					$Type,
					$Time,
					$Name,
					$Text,
					$Parsed
				);

				return $this->SendResponse($From, $Response);
			}

			return false;
		}

		private function ParseURLMessage($Me, $From, $User, $ID, $Type, $Time, $Name, $Text, $URL)
		{
			$Domain = URL::Parse($URL, PHP_URL_HOST);
			$Extension = Path::GetExtension($URL);

			if($Domain !== false)
			{
				if($this->ModuleManager->DomainModuleExists($Domain))
				{
					$Response = $this->ModuleManager->CallDomainModule
					(
						$Domain,

						$Me,
						$From,
						$User,
						$ID,
						$Type,
						$Time,
						$Name,
						$Text,

						$URL
					);

					return $this->SendResponse($From, $Response);
				}
				elseif($Extension !== false && $this->ModuleManager->ExtensionModuleExists($Extension))
				{
					$Response = $this->ModuleManager->CallExtensionModule
					(
						$Extension,

						$Me,
						$From,
						$User,
						$ID,
						$Type,
						$Time,
						$Name,
						$Text,

						$URL
					);

					return $this->SendResponse($From, $Response);
				}
			}

			return false;
		}

		public function ParseMediaMessage($Me, $From, $User, $ID, $Type, $Time, $Name, Array $Data)
		{
			$Response = $this->ModuleManager->CallMediaModule
			(
				$Type,

				$Me,
				$From,
				$User,
				$ID,
				$Time,
				$Name,
				$Data
			);

			return $this->SendResponse($From, $Response);
		}


		private function SendResponse($To, $Code)
		{
			$Lang = new Lang('Main');

			switch($Code)
			{
				case -1:
					$Message = $Lang('message:not_loaded_module');

					if($Message !== false)
						$Message = "That module doesn't exists...";

					$this->WhatsApp->SendMessage($To, $Message);
					break;
				case -2:
				case -3:
				case false:
					$Message = $Lang('message:internal_error');

					if($Message !== false)
						$Message = 'Internal error...';

					$this->WhatsApp->SendMessage($To, $Message);
					break;
			}
		}
	}