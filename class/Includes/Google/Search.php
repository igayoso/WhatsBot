<?php
	require_once 'class/Others/Std.php';
	require_once 'class/Others/Unirest.php';

	trait GoogleSearch
	{
		public static function Search($Text, $Large = false, $UserID = null, $Key = null)
		{
			try
			{
				$Text = urlencode($Text);

				if(strlen($UserID) > 14)
					$UserID = substr($UserID, 0, 14);

				$UserID = urlencode($UserID);

				$URL = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q={$Text}"; // https?

				if($Large)
					$URL .= '&rsz=large';
				if(!empty($UserID))
					$URL .= "&quotaUser={$UserID}";
				if(!empty($Key))
					$URL .= "&key={$Key}";

				$Data = Unirest\Request::get($URL);

				if($Data->code === 200)
				{
					$Data = json_decode($Data->raw_body, true);

					if($Data !== null)
					{
						$Return = array
						(
							'count' => $Data['responseData']['cursor']['estimatedResultCount'],
							'time' => $Data['responseData']['cursor']['searchResultTime'],
							'results' => array()
						);

						foreach($Data['responseData']['results'] as $Result)
						{
							$Return['results'][] = array
							(
								'url' => $Result['unescapedUrl'],
								'title' => $Result['titleNoFormatting'],
								'content' => $Result['content']
							);
						}

						return $Return;
					}
				}
				else
					Std::Out("[WARNING] [API Google::Search] Response code {$Data->code}. Request to {$URL}");

				return false;
			}
			catch(Exception $Exception)
			{
				Std::Out('[WARNING] [API Google::Search] Exception: ' . $Exception->getMessage());

				return false;
			}
		}
	}