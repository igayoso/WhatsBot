<?php
	class Command
	{
		public static function GetText($ModuleName, $Text, $Else = false)
		{
			$Text = substr($Text, strlen($ModuleName) + 2);

			return $Text === false ? $Else : $Text;
		}
	}