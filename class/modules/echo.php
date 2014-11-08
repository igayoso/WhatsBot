<?php
	$Whatsapp->SendMessage($Utils->getOrigin($From), $Utils->GetParams('echo', $Text, 'Debes ingresar un texto...'));