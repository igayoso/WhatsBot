<?php
	require_once dirname(__FILE__) . '/_Loader.php';

	require_once dirname(__FILE__) . '/../LuaFunctions.php';

	class Json
	{
		public static function Decode($Filename, $FixArray = false)
		{
			if(is_readable($Filename))
			{
				$Data = file_get_contents($Filename);

				if($Data !== false)
				{
					$Data = json_decode($Data, true);

					if($Data !== null)
					{
						if($FixArray)
							return LuaFixArrayRecursive($Data);
						else
							return $Data;
					}
					else
						Std::Out("[Warning] [Json] Can't decode {$Filename}");
				}
			}
			else
				Std::Out("[Warning] [Json] No such file {$Filename}");

			return false;
		}

		public static function Encode($Filename, $Data, $Options = JSON_PRETTY_PRINT)
		{
			$EncodedData = json_encode($Data, $Options);

			if($EncodedData !== false)
			{
				$ToWrite = strlen($EncodedData);
				$Written = file_put_contents($Filename, $EncodedData);

				if($Written === $ToWrite)
					return true;
				else
					Std::Out("[Warning] [Json] {$Filename} : {$Written} bytes written of {$ToWrite}");
			}
			else
			{
				$LogFileName = 'json_warning_' . time();

				Data::Set($LogFileName, sprintf("Can't encode %s: %s", $Filename, var_export($Data, true)));

				Std::Out("[Warning] [Json] Can't encode {$Filename} (see data/{$LogFileName})");
			}

			return false;
		}
	}

	// JsonFile.php => new JsonFile($Filename) ->Decode ->Encode