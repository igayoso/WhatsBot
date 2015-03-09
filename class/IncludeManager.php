<?php
	require_once 'ConfigManager.php';

	require_once 'Others/Std.php';

	class IncludeManager
	{
		public function LoadIncludes()
		{
			Std::Out('[INFO] [INCLUDES] Loading');

			$Includes = Config::Get('Includes');

			if(is_array($Includes))
			{
				foreach($Includes as $Include)
				{ // Show number of loaded files
					if(is_array($Include))
						$this->LoadInclude($Include[0], $Include[1]);
					else
						$this->LoadInclude($Include[0], false);
				}

				Std::Out('[INFO] [INCLUDES] Loaded');

				return true;
			}

			Std::Out('[WARNING] [INCLUDES] Config file is not an array');

			return false;
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

			Std::Out("[WARNING] [INCLUDES] Can't load {$Filename} (Require: " . ($Require ? 'true' : 'false') . '). It is not in Includes folder');

			if($Require)
				exit;

			return false;
		}
	}