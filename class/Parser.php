<?php
	require_once 'WhatsApp.php';

	require_once 'ModuleManager.php';

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

		public function ParseTextMessage($Me, $FromData, $ID, $Type, $Time, $Name, $Text)
		{
			if(!empty($Text) && $Text[0] === $Char)
			{
				$this->ParseCommandMessage($Me, $FromData, $ID, $Type, $Time, $Name, $Text);
			}

			// Parser

			// URLs

			// Send help if is pv ?

			// AI ?
		}

		// Test if module is only available for admins

		private function ParseCommandMessage($Me, $FromData, $ID, $Type, $Time, $Name, $Text)
		{
			$Parsed = explode(' ', substr($Text, 1));

			if(!empty($Parsed[0]))
			{
				$Response = $this->ModuleManager->CallCommandModule
				(
					$Parsed[0],

					$Me,
					$FromData,
					$ID,
					$Type,
					$Time,
					$Text,
					$Parsed
				);

				// $this->Send();

				return $Response;
			}
		}
	}