<?php
	class WhatsBotParser
	{
		private $Whatsapp = null;
		private $ModuleManager = null;

		private $Utils = null;

		public function __construct(WhatsappBridge &$WhatsappBridge, ModuleManager &$ModuleManager)
		{
			$this->Whatsapp = &$WhatsappBridge;
			$this->ModuleManager = &$ModuleManager;

			$this->Utils = new Utils();
		}

		public function ParseTextMessage($Me, $FromG, $FromU, $ID, $Type, $Time, $Name, $Text) // Testear si el módulo necesita argumentos o no con explode y strlen...
		{
			$From = $this->Utils->makeFrom($FromG, $FromU);

			if($Text[0] == '!')
			{
				$Parsed = substr($Text, 1);
				$Parsed = explode(' ', $Parsed);

				if($this->ModuleManager->ModuleExists($Parsed[0]))
				{
					$R = $this->ModuleManager->CallModule
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

					if($R === false)
						$this->Whatsapp->SendMessage($this->Utils->getOrigin($From), 'Ha ocurido un error interno. Si eres el administrador del bot, ¡revisa los logs!');
				}
				else
					$this->Whatsapp->SendMessage($this->Utils->getOrigin($From), 'Ese módulo no existe...');
			}
			else
			{
				preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $Text, $URLs); // strtolower URL?
				$URLs = $URLs[0];

				if($URLs !== array())
				{
					foreach($URLs as $URL)
					{
						$PURL = parse_url($URL);

						if($PURL !== false)
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

