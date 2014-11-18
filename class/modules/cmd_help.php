<?php
	$O = $Utils->getOrigin($From);

	if(isset($Params[1]))
	{
		if($ModuleManager->ModuleExists($Params[1]))
		{
			$H = $ModuleManager->GetModuleHelp($Params[1]);

			if($H === false)
				$Whatsapp->SendMessage($O, 'There is no help for that module...');
			else
				$Whatsapp->SendMessage($O, $H);
		}
		else
			$Whatsapp->SendMessage($O, 'That module doesn\'t exists. Write !help to see list of available modules...');
	}
	else
	{
		$H = $ModuleManager->GetModules();
		$T = 'Available modules: ';

		foreach($H as $M)
			$T .= "{$M} ";

		$Whatsapp->SendMessage($O, $T);
	}