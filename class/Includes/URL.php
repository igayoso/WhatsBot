<?php
	class URL
	{
		public static function ParseFor($String)
		{
			preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $String, $URLs);
			$URLs = $URLs[0];

			if($URLs)
				return $URLs;

			return false;
		}

		public static function Parse($URL, $Component)
		{
			return parse_url($URL, $Component);
		}
	}