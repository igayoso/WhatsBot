<?php
	require_once 'ConfigManager.php';

	class IncludeManager
	{
		public function LoadIncludes()
		{
			$Includes = Config::Get('Includes');

			foreach($Includes as $Include)
			{
				if(is_array($Include))
					$this->LoadInclude($Include[0], $Include[1]);
				else
					$this->LoadInclude($Include[0], false);
			}
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

			return false;
		}
	}