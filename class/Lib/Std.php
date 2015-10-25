<?php
	class Std
	{
		private static $Input = null;
		private static $Output = null;
		private static $Error = null;

		public static function Init()
		{
			if(!is_resource(self::$Input))
				if(!is_resource(self::$Input = fopen('php://stdin', 'r')))
					throw new Exception("Can't open php://stdin in read mode");

			if(!is_resource(self::$Output))
				if(!is_resource(self::$Output = fopen('php://stdout', 'w')))
					throw new Exception("Can't open php://stdout in write mode");

			if(!is_resource(self::$Error))
				if(!is_resource(self::$Error = fopen('php://stderr', 'w')))
					throw new Exception("Can't open php://stderr in write mode");
		}

		public static function In()
		{
			self::Init();

			return trim(fgets(self::$Input));
		}

		public static function Out($String = null, $NewLines = 1)
		{
			self::Init();

			for($i = 0; $i < $NewLines; $i++)
				$String .= PHP_EOL;

			return fwrite(self::$Output, $String) === strlen($String);
		}

		public static function Err($String = null, $NewLines = 1)
		{
			self::Init();

			for($i = 0; $i < $NewLines; $i++)
				$String .= PHP_EOL;

			return fwrite(self::$Error, $String) === strlen($String);
		}
	}