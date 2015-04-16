<?php
	require_once '_Loader.php';

	class Regex
	{
		const URL = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#i';

		public static function MatchAll($Pattern, $Subject)
		{
			preg_match_all($Pattern, $Subject, $Matches);

			return $Matches[0];
		}
	}