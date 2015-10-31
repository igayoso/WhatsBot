<?php
	$Libs = array
	(
		# Core libs

		'Std',
		'Json', // Deprecated, will be removed in WhatsBot 2.2

		# Filesystem

		'FileManager',
		'Config',
		'Lang',
		'Data',

		# Message utils

		'vCardParser/vCard',

		# Admin utils

		'Admin',

		# Misc

		'Unirest/Unirest',
		'SimpleAPI',
		'Regex'
	);

	foreach($Libs as $Lib)
		LoadLib($Lib, false);
	
	#############
	# Functions #
	#############

	function LoadLibs()
	{
		Std::Out();
		Std::Out('[Info] [Libs] Loading');

		$Libs = Config::Get('Libs');

		if(is_array($Libs))
		{
			foreach($Libs as $Lib)
				LoadLib($Lib);

			Std::Out('[Info] [Libs] Ready!');
		}
		else
			Std::Out('[Warning] [Libs] Config file is not an array');
	}

	function LoadLib($Lib, $Test = true, $ShowWarning = true)
	{
		if($Test)
		{
			$Path = __DIR__ . "/{$Lib}.php";

			if(is_file($Path))
			{
				if(is_readable($Path))
				{
					// Lint

					return (bool) require_once $Path;
				}
				elseif($ShowWarning)
					Std::Out("[Warning] [Libs] Can't load {$Lib}. File isn't readable");
			}
			elseif(strpos(str_replace('\\', '/', $Lib), '/') === false && LoadLib($Lib . '/' . $Lib, $Test, false))
				return true;
			elseif($ShowWarning)
				Std::Out("[Warning] [Libs] Can't load {$Lib}. File doesn't exist");

			return false;
		}
		else
			return (bool) require_once __DIR__ . "/{$Lib}.php";
	}