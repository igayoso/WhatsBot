<?php
	require_once 'ConfigManager.php';

	require_once 'Others/Std.php';

	class IncludeManager
	{
		public function LoadIncludes()
		{
			Std::Out("[INFO] [INCLUDES] Loading");

			$Includes = Config::Get('Includes');

			if(is_array($Includes))
			{
				foreach($Includes as $Include)
				{
					if(is_array($Include))
						$this->LoadInclude($Include[0], $Include[1]);
					else
						$this->LoadInclude($Include[0], false);
				}

				Std::Out("[INFO] [INCLUDES] Loaded");
			}
			else
				Std::Out("[WARNING] [INCLUDES] Config file is not an array");
		}

		private function LoadInclude($Filename, $Require)
		{
			$Path = "class/Includes/{$Filename}";

			if(basename(dirname(realpath($Path))) === 'Includes')
			{
				if($Require)
					return require_once $Path;
				else
					return include_once $Path;
			}

			Std::Out("[INFO] [INCLUDES] Can't load {$Filename} (Require: " . ($Require ? 'true' : 'false') . '). It is not in Includes folder');

			return false;
		}
	}