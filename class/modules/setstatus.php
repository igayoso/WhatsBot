<?php
	$O = $Utils->getOrigin($From);
	$P = $Utils->getParams($ModuleName, $Text, false);

	if($P !== false)
	{
		$R = $Whatsapp->SetStatus($P);

		if($R === false)
			return false;
		else
			$Whatsapp->SendMessage($O, "Estado seteado a: {$P}");
	}
	else
		$Whatsapp->SendMessage($O, 'Debes ingresar un texto...');