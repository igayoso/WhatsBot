<?php
	require_once 'class/Others/Std.php';
	require_once 'class/Others/Unirest.php';

	trait GoogleTranslate
	{
		public static function Translate($From, $To, $Text) // Rewrited from Google Translate PHP class, v2.0.3 by Levan Velijanashvili. http://stichoza.com/
		{
			try
			{
				$Text = rawurlencode($Text);

				$URL = "http://translate.google.com/translate_a/t?client=t&text={$Text}&hl=en&sl={$From}&tl={$To}&ie=UTF-8&oe=UTF-8&multires=1&otf=1&pc=1&trs=1&ssel=3&tsel=6&sc=1";
				
				$Data = Unirest\Request::get($URL); // https?
				
				if($Data->code === 200)
				{
					$Result = preg_replace('!,+!', ',', $Data->raw_body);
					$Result = str_replace('[,', '[', $Result);
					$Result = json_decode($Result, true);

					if(!empty($Result[0]))
					{
						$Translated = '';

						foreach($Result[0] as $Results)
							$Translated .= $Results[0];

						return $Translated;
					}

					// else?
				}
				else
					Std::Out("[WARNING] [API Google::Translate] Response code {$Data->code}. Request to {$URL}");

				return false;
			}
			catch(Exception $Exception)
			{
				Std::Out('[WARNING] [API Google::Translate] Exception: ' . $Exception->getMessage());

				return false;
			}
		}
	}