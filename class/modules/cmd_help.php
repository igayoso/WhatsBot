<?php
	$From = Utils::GetFrom($From);

	if(isset($Params[1]))
	{
		if($ModuleManager->ModuleExists($Params[1]))
		{
			$Help = $ModuleManager->GetModuleHelp($Params[1]);

			if($Help !== false)
				$Whatsapp->SendMessage($From, $Help);
			else
				$Whatsapp->SendMessage($From, 'There is no help for that module...');
		}
		else
			$Whatsapp->SendMessage($From, 'That module doesn\'t exists. Write !help to see list of available modules...');
	}
	else
	{
		$Modules = $ModuleManager->GetModules();

		$Text = 'Available modules: ';

		foreach($Modules as $Module)
			$Text .= "{$Module} "; // Delete last space

		$Whatsapp->SendMessage($From, $Text);
	}