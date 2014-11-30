<?php
	$Whatsapp->SendMessage(Utils::GetFrom($From), Utils::GetText($ModuleName, $Text, 'You must write something...'));