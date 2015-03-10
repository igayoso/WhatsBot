<?php
	if(!empty($Params[1]) && !empty($Params[2]))
	{
		// To do: !exchange list

		if(empty($Params[3]))
			$Params[3] = 1;
		if(empty($Params[4]))
			$Params[4] = 2;

		$Result = Google::ConvertCurrency($Params[1], $Params[2], $Params[3], $Params[4]);

		if($Result != false)
			$WhatsApp->SendMessage($From, 'message:converted', $Result['Amount'], $Result['From'], $Result['Converted'], $Result['To']);
		else
			$WhatsApp->SendMessage($From, 'message:cant_connect');
	}
	else
		return SEND_USAGE;