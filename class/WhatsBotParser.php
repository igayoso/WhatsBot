<?php
	require_once 'ModuleManager.php';

	class WhatsBotParser
	{
		private $ModuleManager = null;

		public function __construct(ModuleManager &$MM)
		{
			$this->ModuleManager = &$MM;
		}

		public function ParseTextMessage($Me, $From, $ID, $Type, $Time, $Name, $Text)
		{
			if($Text[0] == '!')
			{
				$Parsed = trim($Text, '!');
				$Parsed = explode(' ', $Parsed);

				$this->ModuleManager->CallModule
				(
					$Parsed[0],
					$Parsed,
					$Text,
					array
					(
						'me' => $Me,
						'from' => $From,
						'id' => $ID,
						'type' => $Type,
						'time' => $Time,
						'name' => $Name
					)
				);
			}
			else
			{
				// Parse for SC URLs and others...
			}
		}

		public function Utils_isGroup($From)
		{

		}

		public function Utils_getUserFromGroup($From)
		{

		}
	}