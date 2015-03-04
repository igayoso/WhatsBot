<?php
	require_once 'WhatsApp.php';

	require_once 'ModuleManager.php';

	require_once 'Others/URL.php';

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

			// URLs

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

				// $this->Send();

				return $Response;
			}

			return false;
		}

		private function ParseURLMessage($Me, $From, $User, $ID, $Type, $Time, $Name, $Text, $URL)
		{
			$Domain = URL::Parse($URL, PHP_URL_HOST);
			// Extension;

			if($Domain !== false) // || Exception
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
				}
				// Else if exists extension module

				// $this->Send();
			}

			return false;
		}
	}