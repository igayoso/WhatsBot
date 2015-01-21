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

		public static function Translate($From, $To, $Text) // Rewrited from Google Translate PHP class, v2.0.3 by Levan Velijanashvili. http://stichoza.com/
		{
			$Text = rawurlencode($Text);

			$Result = Utils::GetRemoteFile("http://translate.google.com/translate_a/t?client=t&text={$Text}&hl=en&sl={$From}&tl={$To}&ie=UTF-8&oe=UTF-8&multires=1&otf=1&pc=1&trs=1&ssel=3&tsel=6&sc=1"); // Https?

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

		public static function Search($Text, $Large = false, $UserID = null, $Key = null)
		{
			$Text = urlencode($Text);

			if(strlen($UserID) > 14)
				$UserID = substr($UserID, 0, 14);

			$UserID = urlencode($UserID);

			$URL = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q={$Text}"; // Https?

			if(!empty($Large))
				$URL .= '&rsz=large';
			if(!empty($UserID))
				$URL .= "&quotaUser={$UserID}";
			if(!empty($Key))
				$URL .= "&key={$Key}";

			$Data = Utils::GetRemoteJson($URL);

			if($Data !== false && $Data['responseStatus'] == 200)
			{
				$Return = array
				(
					'count' => $Data['responseData']['cursor']['estimatedResultCount'],
					'time' => $Data['responseData']['cursor']['searchResultTime'],
					'results' => array()
				);

				foreach($Data['responseData']['results'] as $Result)
				{
					$Return['results'][] = array
					(
						'url' => $Result['unescapedUrl'],
						'title' => $Result['titleNoFormatting'],
						'content' => $Result['content']
					);
				}
				return $Return;
			}

			return false;
		}
	}