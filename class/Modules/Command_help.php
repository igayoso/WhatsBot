<?php
	if(empty($Params[1]))
	{
		$Modules = $ModuleManager->GetModules('Command');

		if(is_array($Modules))
		{
			$Message = $Lang('message:available_list');
			$Pattern = $Lang('message:list_pattern');

			if($Message === false)
				return array(WARNING_LANG_ERROR, 'message:available_list');
			if($Pattern === false)
				return array(WARNING_LANG_ERROR, 'message:list_pattern');

			$Count = count($Modules);
			for($i = 0; $i < $Count; $i++)
				$Message .= sprintf($Pattern, $Modules[$i]);

			$WhatsApp->SendRawMessage($From, $Message);
		}
		else
			return WARNING_GET_ERROR;
	}
	else
	{
		if($ModuleManager->CommandModuleExists($Params[1]))
		{
			$Lang = new Lang("Command_{$Params[1]}");

			$Help = $Lang('help');
			$Usage = $Lang('usage');

			if($Help === false)
				return array(WARNING_LANG_ERROR, 'help');
			if($Usage === false)
				return array(WARNING_LANG_ERROR, 'usage');

			$WhatsApp->SendRawMessage($From, "Command::{$Params[1]}: {$Help}");
			$WhatsApp->SendRawMessage($From, $Usage);
		}
		else
			return WARNING_NOT_LOADED;
	}