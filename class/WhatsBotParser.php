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
					// Only send if is pv ?

					// Put data into Modules.json, with module's name (Update ModuleManager)

					$Data = Utils::GetJson('config/Parser.json');

					$Replace = $Data['replace'];
					$Data = $Data['data'];

					$String = strtolower($Text);
					$String = str_replace($Replace[0], $Replace[1], $String);

					$Flags = array
					(
						'question' => false
					);

					$String = str_replace('?', '', $String, $Count);
					if($Count)
						$Flags['question'] = true;

					$Splitted = explode(' ', $String);

					$Action = $this->GetAction($Data, $Splitted);

					if($Action !== false)
					{
						$Object = $this->GetObject($Data, $Splitted, $Action[0], $Action[2]);

						// Do something C:
					}

					// Parse for AI?
				}
			}
		}

		protected function GetAction(Array $Data, Array $Splitted)
		{
			foreach($Data as $Action => $D)
				foreach($Splitted as $Offset => $Word)
					if(in_array($Word, $D[0]))
						return array($Action, $Word, $Offset);

			return false;
		}

		protected function GetObject(Array $Data, Array $Splitted, $Action, $Offset)
		{
			$Splitted = array_slice($Splitted, $Offset + 1, null, true);

			foreach($Data[$Action][1] as $Object => $Alias)
				foreach($Splitted as $Offset => $Word)
					if(in_array($Word, $Alias))
						return array($Object, $Word, $Offset);

			return false;
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

