<?php
	$From = $Utils->getOrigin($From);
	$Params = $Utils->getParams($ModuleName, $Text, false);

	if($Params !== false)
	{
		$Json = $Utils->getJson('config/GoogleSearch.json');

		$Q = urlencode($Params);

		$UserID = $Utils->GetNumberFromJID($From);
		if(strlen($UserID) > 14)
			$UserID = substr($UserID, 0, 14);
		$UserID = urlencode($UserID);

		$RequestURL = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q={$Q}&quotaUser={$UserID}";
		if(!empty($Json['key']))
			$RequestURL .= "&key={$Json['key']}";
		if(!empty($Json['large']))
			$RequestURL .= '&rsz=large';

		$Data = file_get_contents($RequestURL);
		$Data = json_decode($Data, true);

		if($Data['responseStatus'] == 200)
		{
			$Data = $Data['responseData'];

			$Message = "{$Data['cursor']['estimatedResultCount']} results in {$Data['cursor']['searchResultTime']} seconds: \n";

			$Count = count($Data['results']) - 1;

			for($i = 0; $i < $Count; $i++)
				$Message .= "{$Data['results'][$i]['unescapedUrl']} => {$Data['results'][$i]['titleNoFormatting']}\n";

			$Message .= "{$Data['results'][$i]['unescapedUrl']} => {$Data['results'][$i]['titleNoFormatting']}";

			$Whatsapp->SendMessage($From, $Message);
		}
		else
			$Whatsapp->SendMessage($From, 'Internal error...');
	}
	else
		$Whatsapp->SendMessage($From, 'You must write something to search...');