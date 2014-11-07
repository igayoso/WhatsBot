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

		public function ParseTextMessage($Me, $FromG, $FromU, $ID, $Type, $Time, $Name, $Text)
		{ // Testear si el módulo necesita argumentos o no con explode y strlen...
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
						$From,
						$Text,
						array
						(
							'me' => $Me,
							'id' => $ID,
							'type' => $Type,
							'time' => $Time,
							'name' => $Name
						)
					);

					if($R == false); // Error del eval
				}
				else
					$this->Whatsapp->sendMessage(($FromG != null) ? $FromG : $FromU, 'Ese módulo no existe...');
			}
			else
			{
				preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $Text, $URLs);
				$URLs = $URLs[0];

				if($URLs !== array())
				{
					foreach($URLs as $URL)
					{
						$P = parse_url($URL);

						if($P !== false)
						{
							$R = $this->ModuleManager->CallDomainPlainModule
							(
								$P['host'],
								$URL,
								$P
							);
						}
						else
						{

						}
					}
				}
				else
				{
					// Parse for AI?
				}

				/*if($URLs !== array())
				{
					foreach($URLs as $URL)
					{
						$Extension = explode('.', $URL);
						$Extension = end($Extension);

						if(in_array($Extension, array('jpg', 'png', 'gif'))) // Others
						{
							//if(filesize($URL) < 2097152)
							//{
							/*function remote_file_size($url){
	# Get all header information
	$data = get_headers($url, true);
	# Look up validity
	if (isset($data['Content-Length']))
		# Return file size
		return (int) $data['Content-Length'];
								$Filename = tempnam('.', 'tmp');
								$Filename2 = $Filename . '.' . $Extension;

								file_put_contents($Filename2, file_get_contents($URL));

								$this->Whatsapp->sendMessageImage(($FromG != null) ? $FromG : $FromU, $Filename2);

								if(is_file($Filename))
									unlink($Filename);
								if(is_file($Filename2))
									unlink($Filename2);
							//}

						}
						// OTHER MEDIA TYPES
					}
				}
				// Parse for SC URLs and others...*/
			}
		}
	}