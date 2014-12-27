<?php
	class Wikipedia
	{
		private static $Endpoint = 'http://es.wikipedia.org/w/api.php';

		public static function Search($Title)
		{
			$Title = urlencode($Title);

			$URL = static::$Endpoint;
			$URL .= "?action=query&list=search&srwhat=text&format=json&srsearch={$Title}&continue=";

			$Data = Utils::GetRemoteJson($URL);

			if($Data !== false)
			{
				$Data = $Data['query']['search'];

				if(!empty($Data))
				{
					$Data = $Data[0];

					return array
					(
						'title' => $Data['title'],
						'content' => $Data['snippet']
					);
				}
			}

			return false;
		}
	}