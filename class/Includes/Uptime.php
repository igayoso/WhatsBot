<?php
	function GetTimeDiff($Start, $Now)
	{
		$Diff = $Now - $Start;

		$Days = 0;
		$Hours = 0;
		$Minutes = 0;

		while($Diff > 86400) // 60 * 60 * 24
		{
			$Days++;
			$Diff -= 86400;
		}

		while($Diff > 3600) // 60 * 60
		{
			$Hours++;
			$Diff -= 3600;
		}

		while($Diff > 60)
		{
			$Minutes++;
			$Diff -= 60;
		}

		return array($Days, $Hours, $Minutes, $Diff);
	}