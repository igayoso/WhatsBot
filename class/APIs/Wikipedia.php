<?php
	class Wikipedia
	{
		private $Endpoint = 'http://es.wikipedia.org/w/api.php'; // In json?

		public function Search($Title)
		{
			$Title = urlencode($Title);

			$URL = "{$this->Endpoint}?action=query&list=search&srwhat=text&format=json&srsearch={$Title}&continue=";

			$Data = file_get_contents($URL);
			$Data = json_decode($Data, true);

			$Data = $Data['query']['search'];

			if($Data === array())
				return false;

			$Data = $Data[0];

			return array
			(
				'title' => $Data['title'],
				'content' => $Data['snippet']
			);
		}
	}