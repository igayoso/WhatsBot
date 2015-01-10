<?php
	class Google
	{
		public static function ConvertCurrency($Amount, $From, $To, $DecimalPlaces = 0)
		{
			$URL = "https://www.google.com/finance/converter?a={$Amount}&from={$From}&to={$To}";

			$Data = Utils::GetRemoteFile($URL);

			if($Data !== false)
			{
				preg_match("/<span class=bld>(.*)<\/span>/", $Data, $Converted);
				$Converted = preg_replace("/[^0-9.]/", '', $Converted[1]);

				return round($Converted, $DecimalPlaces);
			}

			return false;
		}
	}