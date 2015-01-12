<?php
	class Google
	{
		public static function ConvertCurrency($Amount, $From, $To, $DecimalPlaces = 2)
		{
			$Data = Utils::GetRemoteFile("https://www.google.com/finance/converter?a={$Amount}&from={$From}&to={$To}");

			if($Data !== false)
			{
				preg_match("/<span class=bld>(.*)<\/span>/", $Data, $Converted);
				$Converted = preg_replace("/[^0-9.]/", '', $Converted[1]);

				return round($Converted, $DecimalPlaces);
			}

			return false;
		}

		public static function Translate($From, $To, $Text) // From Google Translate PHP class, v2.0.3 by Levan Velijanashvili. http://stichoza.com/
		{
			$Text = rawurlencode($Text);

			$Result = Utils::GetRemoteFile("http://translate.google.com/translate_a/t?client=t&text={$Text}&hl=en&sl={$From}&tl={$To}&ie=UTF-8&oe=UTF-8&multires=1&otf=1&pc=1&trs=1&ssel=3&tsel=6&sc=1");

			if($Result !== false)
			{
				$Result = preg_replace('!,+!', ',', $Result);
				$Result = str_replace('[,', '[', $Result);

				$Result = json_decode($Result, true);

				if(!empty($Result[0]))
				{
					$Translated = '';

					foreach($Result[0] as $Results)
						$Translated .= $Results[0];

					return $Translated;
				}
			}

			return false;
		}
	}