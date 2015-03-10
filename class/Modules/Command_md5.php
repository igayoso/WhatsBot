<?php
	$Text = Command::GetText($ModuleName, $Text);

	if($Text !== false)
		$WhatsApp->SendMessage($From, 'message:md5', $Text, md5($Text));
	else
		return SEND_USAGE;