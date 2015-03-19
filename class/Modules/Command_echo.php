<?php
	$Message = Command::GetText($ModuleName, $Text);

	if($Message !== false)
		$WhatsApp->SendRawMessage($From, $Message);
	else
		return SEND_USAGE;