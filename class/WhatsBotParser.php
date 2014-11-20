<?php
	class WhatsBotParser
	{
		private $Whatsapp = null;
		private $ModuleManager = null;

		public function __construct(WhatsappBridge &$WhatsappBridge, ModuleManager &$ModuleManager)
		{
			$this->Whatsapp = &$WhatsappBridge;
			$this->ModuleManager = &$ModuleManager;
		}

		public function ParseTextMessage($Me, $FromGroup, $FromUser, $ID, $Type, $Time, $Name, $Text) // Testear si el módulo necesita argumentos o no con explode y strlen...
		{
			$From = Utils::MakeFrom($FromGroup, $FromUser);

			if($Text[0] == '!')
			{
				$Parsed = substr($Text, 1);
				$Parsed = explode(' ', $Parsed);

				if($this->ModuleManager->ModuleExists($Parsed[0]))
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
						$this->Whatsapp->SendMessage($this->Utils->getOrigin($From), 'Internal error... If you\'re the owner, ¡see the logs!');
				}
				else
					$this->Whatsapp->SendMessage($this->Utils->getOrigin($From), 'That module doesn\'t exists...');
			}
			else
			{
				$URLs = Utils::GetURLs($Text);

				if($URLs !== false)
				{
					foreach($URLs as $URL)
					{
						$PURL = parse_url($URL);

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
							///*else*/if($ExtensionModuleExists)...
						}
						else
						{
							// SendMessage with help if is PV?
						}
					}
				}
				else
				{
					// Parse for AI?
				}
			}
		}
	}

