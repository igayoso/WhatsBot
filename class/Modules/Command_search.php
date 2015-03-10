<?php
	$Text = Command::GetText($ModuleName, $Text);

	if($Text !== false)
	{
		$Config = Config::Get('GoogleSearch');

		if(empty($Config))
			Std::Out('[WARNING] [Command::Search] Empty config file');

		$Large = !empty($Config['large']) ? true : false;
		$Key = !empty($Config['key']) ? $Config['key'] : null;

		$UserID = str_rot13(md5(sha1($User)));

		$Data = Google::Search($Text, $Large, $UserID, $Key);

		if($Data != false)
		{
			$Message = $Lang('message:search_results', $Data['count'], $Data['time']);
			$Pattern = $Lang('message:result_pattern');

			if($Message === false)
				return array(WARNING_LANG_ERROR, 'message:search_results');
			if($Pattern === false)
				return array(WARNING_LANG_ERROR, 'message:result_pattern');

			foreach($Data['results'] as $Result)
				$Message .= sprintf($Pattern, $Result['url'], $Result['title']);

			$WhatsApp->SendRawMessage($From, $Message);
		}
		else
			$WhatsApp->SendMessage($From, 'message:cant_connect');
	}
	else
		return SEND_USAGE;