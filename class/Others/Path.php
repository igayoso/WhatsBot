<?php
	class Path
	{
		public static function GetExtension($Path)
		{
			$Slash = strrpos($Path, '/');
			$Dot = strrpos($Path, '.');

			return $Dot > $Slash ? substr($Path, ++$Dot) : false;

			// Return '' if empty extension. If empty filename return false

			// $Extension = $Dot > $Slash ? substr($Path, ++$Dot) : false;

			// return $Extension !== false ? $Extension : '';
		}
	}