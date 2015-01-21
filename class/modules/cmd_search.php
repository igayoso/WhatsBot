<?php
	$To = Utils::GetFrom($From);
	$Text = Utils::GetText($ModuleName, $Text);

	if($Text !== false)
	{
		$Config = Utils::GetJson('config/GoogleSearch.json');

		$Large = !empty($Config['large']) ? true : false;
		$Key = !empty($Config['key']) ? $Config['key'] : null;

		$UserID = md5(Utils::GetNumberFromJID($From['u']));

		$Data = Google::Search($Text, $Large, $UserID, $Key);

		if($Data !== false)
		{
			$Message = "{$Data['count']} results in {$Data['time']} seconds: \n";

			$Count = count($Data['results']) - 1;
			for($i = 0; $i < $Count; $i++)
				$Message .= "{$Data['results'][$i]['url']} => {$Data['results'][$i]['title']}\n";
			$Message .= "{$Data['results'][$i]['url']} => {$Data['results'][$i]['title']}";

			$Whatsapp->SendMessage($To, $Message);
		}
		else
			return false;
	}
	else
		$Whatsapp->SendMessage($To, 'You must write something to search...');
