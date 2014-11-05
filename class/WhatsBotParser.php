<?php
	class WhatsBotParser
	{
		private $Whatsapp = null;
		private $ModuleManager = null;

		public function __construct(WhatsProt &$Whatsapp, ModuleManager &$ModuleManager)
		{
			$this->Whatsapp = &$Whatsapp;
			$this->ModuleManager = &$ModuleManager;
		}

		public function ParseTextMessage($Me, $FromG, $FromU, $ID, $Type, $Time, $Name, $Text)
		{
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
				$Parsed = trim($Text, '!'); // Use substr to only delete first char (!)
				$Parsed = explode(' ', $Parsed);

				$R = $this->ModuleManager->CallModule
				(
					$Parsed[0],
					$Parsed,
					$Text,
					array
					(
						'me' => $Me,
						'from' => $From
						'id' => $ID,
						'type' => $Type,
						'time' => $Time,
						'name' => $Name
					)
				);

				if($R == false) // moduleExists() - False es error del eval, en todo caso tambie del isset(Module), pero podemo evitar esto testeando adicionalmente...
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
		return (int) $data['Content-Length'];*/
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
				// Parse for SC URLs and others...
			}
		}
	}