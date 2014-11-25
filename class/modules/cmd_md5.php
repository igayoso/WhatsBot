<?php
	$Text = Utils::GetText($ModuleName, $Text);

	$Whatsapp->SendMessage(Utils::GetFrom($From), ($Text !== false) ? md5($Text) : 'You must write something...');