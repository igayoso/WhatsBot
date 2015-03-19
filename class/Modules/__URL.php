<?php
	$Source = file_get_contents($URL);

	if($Source !== false)
	{
		if(preg_match('/<title>(.*?)<\/title>/i', $Source, $Matches))
			return $WhatsApp->SendMessage($From, 'message:title', $Matches[1]);
		else
			return $WhatsApp->SendMessage($From, 'message:cant_parse');
	}
	else
		return $WhatsApp->SendMessage($From, 'message:cant_get_source');
