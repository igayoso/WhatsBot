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

				$this->SendResponse($From, $Response);

				return $Response;
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

					$this->SendResponse($From, $Response);

					return $Response;
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

					$this->SendResponse($From, $Response);

					return $Response;
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

			if($Response !== WARNING_NOT_LOADED)
				$this->SendResponse($From, $Response);

			return $Response;
		}


		private function SendResponse($To, $Code)
		{
			if($Code === INTERNAL_ERROR || $Code === WARNING_GET_ERROR || $Code === WARNING_NOT_FILE)
			{
				$this->WhatsApp->SetLangSection('Main');

				$this->WhatsApp->SendMessage($To, 'message:internal_error');
			}
			elseif($Code === WARNING_NOT_LOADED)
			{
				$this->WhatsApp->SetLangSection('Main');
				
				$this->WhatsApp->SendMessage($To, 'message:module_not_loaded');
			}
			elseif($Code === SEND_USAGE)
			{
				$this->WhatsApp->SendMessage($To, 'usage');
			}
			elseif(!empty($Code[0]) && $Code[0] === WARNING_LANG_ERROR)
			{
				$this->WhatsApp->SendLangError($To, $Code[1]);
			}
		}
	}