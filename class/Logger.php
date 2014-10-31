<?php
	const NL = "\n"; // PHP_EOL?

	class Logger
	{
		private $Error = null;

		public function __construct($Error = 'log.log')
		{
			$this->Error = $Error;
		}

		public static function logError($Message)
		{
			try
			{
				$Date = date('Y-m-d h:i:s', mktime());
				
				$Message = "[{$Date}] [ERROR] {$Message}, at line {$Line}. {$File}" . NL;
				
				$File = fopen($this->Error, 'a');
				fwrite($File, $Message);
				fclose($File);

				return true;
			}
			catch (Exception $E)
			{
				trigger_error($E->getMessage, E_USER_WARNING);
			}

			return false;
		}

		public function logException($Message, $Line, $File)
		{
			try
			{
				$Date = date('Y-m-d h:i:s', mktime());
				
				$Message = "[{$Date}] [EXCEPTION] {$Message}, at line {$Line}. {$File}" . NL;
				
				$File = fopen($this->Error, 'a');
				fwrite($File, $Message);
				fclose($File);

				return true;
			}
			catch (Exception $E)
			{
				trigger_error($E->getMessage, E_USER_WARNING);
			}
			
			return false;
		}

		public function log($Message)
		{
			try
			{
				$Date = date('Y-m-d h:i:s', mktime());
				
				$Message = "[{$Date}] $Message" . NL;
				
				$File = fopen($this->Error, 'a');
				fwrite($File, $Message);
				fclose($File);

				return true;
			}
			catch (Exception $E)
			{
				trigger_error($E->getMessage, E_USER_WARNING);
			}
			
			return false;
		}
	}