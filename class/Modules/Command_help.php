<?php
	if(empty($Params[1]))
	{
		$Modules = $ModuleManager->GetModules('Command'); // sort?

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
			$Module = $ModuleManager->GetCommandModule($Params[1]);

			if($Module !== false)
			{
				$this->WhatsApp->SetLangSection("Command_{$Module['File']}");

				$WhatsApp->SendMessage($From, 'help', array('Command::' . ucfirst(strtolower($Params[1])) . ': '));
				$WhatsApp->SendMessage($From, 'usage');
			}
			else
				return WARNING_GET_ERROR;
		}
		else
			return WARNING_NOT_LOADED;
	}