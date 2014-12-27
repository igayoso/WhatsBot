<?php
	$Whatsapp->SendMessage(Utils::GetFrom($From), Utils::GetText($ModuleName, $Text, $Lang('notext')));