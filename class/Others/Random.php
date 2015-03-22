<?php
	class Random
	{
		public static function Filename($Extension)
		{
			$Name = str_replace('.', null, str_replace(' ', null, microtime()));

			$Path = "tmp/{$Name}.{$Extension}";

			if(is_file($Path))
				return self::Filename($Extension);

			return $Path;

			// do while ?
		}

		// Numbers
		// Strings
		// Chars
	}