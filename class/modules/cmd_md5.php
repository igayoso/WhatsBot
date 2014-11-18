<?php
	$T = $Utils->getParams($ModuleName, $Text, false);
	$Whatsapp->SendMessage($Utils->getOrigin($From), ($T !== false) ? md5($T) : 'You must write something...');