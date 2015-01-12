<?php
	$From = Utils::GetFrom($From);

	if(!empty($Params[1]) && !empty($Params[2]) && $Text = substr($Text, strlen("!{$ModuleName} {$Params[1]} {$Params[2]} "))) // If params are setted (fromlang and tolang)
	{
		$Result = Google::Translate($Params[1], $Params[2], $Text);

		if($Result !== false)
			$Whatsapp->SendMessage($From, "In {$Params[2]}, \"{$Text}\" means \"{$Result}\"...");
		else
			return false;
	}
	else // Else send usage
		$Whatsapp->SendMessage($From, 'Usage: !translate <language from> <language to> <text>. Example: !translate en es Hello'); // ModuleManager->GetHelp() ?