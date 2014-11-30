<?php
	$To = Utils::GetFrom($From);
	$Text = Utils::GetText($ModuleName, $Text);

	if($Text !== false)
	{
		$Json = Utils::GetJson('config/GoogleSearch.json');

		$Q = urlencode($Text);

		$UserID = Utils::GetNumberFromJID($From['u']);
		if(strlen($UserID) > 14)
			$UserID = substr($UserID, 0, 14);
		$UserID = urlencode($UserID);

		$RequestURL = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q={$Q}&quotaUser={$UserID}";
		if(!empty($Json['key']))
			$RequestURL .= "&key={$Json['key']}";
		if(!empty($Json['large']))
			$RequestURL .= '&rsz=large';

		$Data = file_get_contents($RequestURL); // if false then return false
		$Data = json_decode($Data, true);

		if($Data['responseStatus'] == 200)
		{
			$Data = $Data['responseData'];

			$Message = "{$Data['cursor']['estimatedResultCount']} results in {$Data['cursor']['searchResultTime']} seconds: \n";

			$Count = count($Data['results']) - 1;

			for($i = 0; $i < $Count; $i++)
				$Message .= "{$Data['results'][$i]['unescapedUrl']} => {$Data['results'][$i]['titleNoFormatting']}\n";

			$Message .= "{$Data['results'][$i]['unescapedUrl']} => {$Data['results'][$i]['titleNoFormatting']}";

			$Whatsapp->SendMessage($To, $Message);
		}
		else
			return false;
	}
	else
		$Whatsapp->SendMessage($To, 'You must write something to search...');
