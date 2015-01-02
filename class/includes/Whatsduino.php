<?php
	class Whatsduino
	{
		private $Stream = null;

		public function __construct($Port, $Rate = 9600)
		{
			if(strtolower(substr(PHP_OS, 0, 3)) === 'win')
			{
				$Command = "Whatsduino.exe {$Port} {$Rate}";

				$Stream = popen($Command, 'w');

				$this->Stream = $Stream;
			}
			else
				throw new Exception('Whatsduino can be only used in Windows...');
		}

		public function Write($String, $WithEOL = true)
		{
			$String .= $WithEOL ? "\n" : null;

			if(fwrite($this->Stream, $String) === strlen($String))
				return true;

			return false;
		}

		protected function IsAvailablePin($Pin)
		{
			return $Pin > 1 && $Pin < 12;
		}

		const OUTPUT = 'o';
		const INPUT = 'i';

		public function SetPinMode($Pin, $Mode)
		{
			if($this->IsAvailablePin($Pin))
				if($Mode == Whatsduino::OUTPUT || $Mode == Whatsduino::INPUT)
					return $this->Write("pm{$Mode} {$Pin}");

			return false;
		}

		const HIGH = 'h';
		const LOW = 'l';

		public function PinWrite($Pin, $Value)
		{
			if($this->IsAvailablePin($Pin))
				if($Value == Whatsduino::HIGH || $Value == Whatsduino::LOW)
					return $this->Write("dw{$Value} {$Pin}");

			return true;
		}
	}