<?php
	class Std
	{
		public static function In()
		{
			return trim(fgets(STDIN));
		}

		public static function Out($String, $WithNewLine = true)
		{
			$String .= $WithNewLine ? PHP_EOL : null;

			return fwrite(STDOUT, $String) === strlen($String);
		}
	}