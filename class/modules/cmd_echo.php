<?php
	$Whatsapp->SendMessage($Utils->getOrigin($From), $Utils->getParams('echo', $Text, 'You must write something...'));