<?php
	$From = Utils::GetFrom($From);

	if(!empty($Params[1]) && !empty($Params[2])) // If params are setted (fromcur and tocur)
	{
		if(empty($Params[3])) // If there isn't any amount, set it to 1.
			$Params[3] = 1;

		$FromCur = strtoupper($Params[1]);
		$ToCur = strtoupper($Params[2]);
		$Amount = intval($Params[3]);

		$Result = Google::ConvertCurrency($Amount, $FromCur, $ToCur, 2);

		if($Result !== false) // If not error
			$Whatsapp->SendMessage($From, "As per current exchange rate: {$Amount} {$FromCur} = {$Result} {$ToCur}. ");
		else // Else (Return false will send an "Internal error" message
			return false;
	}
	else // Else send usage
		$Whatsapp->SendMessage($From, 'Usage: !exchange <convert from> <convert to> <amount>. Example: !exchange usd eur 2');