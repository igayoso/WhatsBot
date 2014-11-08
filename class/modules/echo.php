<?php
	$Whatsapp->SendMessage($Utils->getOrigin($From), $Utils->getParams('echo', $Text, 'Debes ingresar un texto...'));