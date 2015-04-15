<?php
	require_once '_Loader.php';

	class Json
	{
		public static function Decode($Filename)
		{
			if(is_readable($Filename))
			{
				$Data = file_get_contents($Filename);

				if($Data !== false)
				{
					$Data = json_decode($Data, true);

					if($Data !== null)
						return $Data;
					else
						Std::Out("[WARNING] [JSON] Can't decode {$Filename}");
				}
			}
			else
				Std::Out("[WARNING] [JSON] No such file {$Filename}");

			return false;
		}

		public static function Encode($Filename, $Data, $Options = JSON_PRETTY_PRINT)
		{
			$Data = json_encode($Data, $Options);

			if($Data !== false)
			{
				$ToWrite = strlen($Data);
				$Writed = file_put_contents($Filename, $Data);

				if($Writed === $ToWrite)
					return true;
				else
					Std::Out("[WARNING] [JSON] {$Filename} : {$Writed} bytes writed of {$ToWrite}");
			}
			else
				Std::Out("[WARNING] [JSON] Can't encode {$Filename}");

			return false;
		}
	}

	// JsonFile.php => new JsonFile($Filename) ->Decode ->Encode