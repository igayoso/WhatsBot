<?php
	require_once 'class/Others/Std.php';
	require_once 'class/Others/Unirest.php';

	trait GoogleCurrency
	{
		public static function ConvertCurrency($From, $To, $Amount = 1, $DecimalPlaces = 2)
		{
			try
			{
				$From = strtoupper($From);
				$To = strtoupper($To);
				$Amount = intval($Amount);

				$Data = Unirest\Request::get("https://www.google.com/finance/converter?a={$Amount}&from={$From}&to={$To}");

				if($Data->code === 200)
				{
					preg_match("/<span class=bld>(.*)<\/span>/", $Data->raw_body, $Converted);

					if(!empty($Converted[1]))
					{
						$Converted = preg_replace("/[^0-9.]/", '', $Converted[1]);
						
						return array
						(
							'From' => $From,
							'To' => $To,
							'Amount' => $Amount,
							'Converted' => round($Converted, $DecimalPlaces),
							'ConvertedWithoutRound' => $Converted,
						);
					}
				}

				return false;
			}
			catch(Exception $Exception)
			{
				Std::Out('[WARNING] [API Google::Currency] Exception: ' . $Exception->getMessage());

				return false;
			}
		}
	}