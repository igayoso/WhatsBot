<?php
	class Json
	{ // Non static context => new Json($Filename) => ->Read() ->Write($Data)
		public static function Read($Filename)
		{
			if(is_readable($Filename))
			{
				$Data = file_get_contents($Filename);

				if($Data !== false)
				{
					$Data = json_decode($Data, true);

					if($Data !== null)
						return $Data;
				}
			}

			return false;
		}

		public static function Write($Filename, $Data)
		{
			$Data = json_encode($Data);

			if($Data !== false)
				if(file_put_contents($Filename, $Data) === strlen($Data))
					return true;

			return false;
		}
	}