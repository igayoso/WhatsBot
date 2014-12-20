<?php
	require_once 'StringParser.php';

	class WhatsBotParser
	{
		private $Whatsapp = null;
		private $ModuleManager = null;

		private $StringParser = null;

		public function __construct(WhatsappBridge &$WhatsappBridge, ModuleManager &$ModuleManager)
		{
			$this->Whatsapp = &$WhatsappBridge;
			$this->ModuleManager = &$ModuleManager;

			$this->StringParser = new StringParser();
		}

		public function ParseTextMessage($Me, $FromGroup, $FromUser, $ID, $Type, $Time, $Name, $Text) // Testear si el módulo necesita argumentos o no con explode y strlen...
		{
			$From = Utils::MakeFrom($FromGroup, $FromUser);

			if($Text[0] == '!')
			{
				$Parsed = substr($Text, 1);
				$Parsed = explode(' ', $Parsed);

				if($this->ModuleManager->ModuleExists($Parsed[0])) // test if module is only for admins
				{
					$Response = $this->ModuleManager->CallModule
					(
						$Parsed[0],
						$Parsed,

						$Me,
						$ID,
						$Time,
						$From,
						$Name,
						$Text
					);

					if($Response === false)
						$this->Whatsapp->SendMessage(Utils::GetFrom($From), 'Internal error... If you\'re the owner, ¡see the logs!');
				}
				else
					$this->Whatsapp->SendMessage(Utils::GetFrom($From), 'That module doesn\'t exists...');
			}
			else
			{
				$URLs = Utils::GetURLs($Text);

				if($URLs !== false)
				{
					foreach($URLs as $URL)
					{
						$PURL = parse_url($URL); // Change to $ParsedURL

						if($PURL !== false)
						{
							if($this->ModuleManager->DomainModuleExists($PURL['host']))
							{
								$R = $this->ModuleManager->CallDomainModule
								(
									$PURL['host'],

									$PURL,
									$URL,

									$Me,
									$ID,
									$Time,
									$From,
									$Name,
									$Text
								);
							}
							else // else or not?
							{
								$Extension = pathinfo($URL, PATHINFO_EXTENSION);

								if(!empty($Extension) && $this->ModuleManager->ExtensionModuleExists($Extension))
								{
									$R = $this->ModuleManager->CallExtensionModule
									(
										$Extension,

										$Me,
										$From,
										$ID,
										$Type,
										$Time,
										$Name,
										$Text,

										$URL,
										$PURL
									);
								}
							}
						}
						else
						{
							// SendMessage with help if is PV?
						}
					}
				}
				else
				{
					$Flags;

					$Parsed = $this->StringParser->Parse($Text, $Flags);

					if($Parsed !== false)
					{
						if($FromGroup === null)
						{
							if($this->ModuleManager->ParserModuleExists($Parsed[0][0]))
							{
								$Response = $this->ModuleManager->CallParserModule
								(
									$Parsed[0][0],

									$Me,
									$From,
									$ID,
									$Time,
									$Name,
									$Text,

									$Parsed[0],
									$Parsed[1]
								);
							}
							else
								; // Send some message
						}
						else
							; // Send some message
					}

					// Parse for AI?
				}
			}
		}

		public function ParseMediaMessage($Me, $From, $ID, $Type, $Subtype, $Time, $Name, Array $Data) // download data instead passing url
		{
			if($this->ModuleManager->MediaModuleExists($Subtype))
				$this->ModuleManager->CallMediaModule
				(
					$Subtype,

					$Me,
					$From,
					$ID,
					$Type,
					$Time,
					$Name,

					$Data
				);
		}
	}