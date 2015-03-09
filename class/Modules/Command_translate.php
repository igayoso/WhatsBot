<?php
	if(!empty($Params[1]) && !empty($Params[2]) && ($Text = substr($Text, strlen("!{$ModuleName} {$Params[1]} {$Params[2]} "))) !== false)
	{
		$Result = Google::Translate($Params[1], $Params[2], $Text);

		if($Result !== false)
			$WhatsApp->SendMessage($From, 'message:translated', $Params[2], $Text, $Result);
		else
			$WhatsApp->SendMessage($From, 'message:cant_connect');
	}
	else
		return SEND_USAGE;