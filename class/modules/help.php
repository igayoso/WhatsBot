<?php
	$O = $Utils->getOrigin($From);

	if(isset($Params[1]))
	{
		if($ModuleManager->ModuleExists($Params[1]))
		{
			$H = $ModuleManager->GetModuleHelp($Params[1]);

			if($H === false)
				$Whatsapp->SendMessage($O, 'No hay informacón sobre ese módulo...');
			else
				$Whatsapp->SendMessage($O, $H);
		}
		else
			$Whatsapp->SendMessage($O, 'Ese módulo no existe. Escribe !help para ver una lista de módulos disponibles...');
	}
	else
	{
		$H = $ModuleManager->GetModules();
		$T = 'Módulos disponibles: ';

		foreach($H as $M)
			$T .= "{$M} ";

		$Whatsapp->SendMessage($O, $T);
	}