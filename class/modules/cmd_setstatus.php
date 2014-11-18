<?php
	$O = $Utils->getOrigin($From);
	$P = $Utils->getParams($ModuleName, $Text, false);

	if($P !== false)
	{
		$R = $Whatsapp->SetStatus($P);

		if($R === false)
			return false;
		else
			$Whatsapp->SendMessage($O, "Status setted to: {$P}");
	}
	else
		$Whatsapp->SendMessage($O, 'You must write something...');