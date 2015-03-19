<?php
	class Command
	{
		public static function GetText($ModuleName, $Text, $Else = false, Array $Params = array())
		{
			foreach($Params as $Param)
				$ModuleName .= " {$Param}";

			$Text = substr($Text, strlen($ModuleName) + 2);

			return $Text === false ? $Else : $Text;
		}
	}