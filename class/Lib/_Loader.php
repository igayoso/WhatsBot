<?php
	$CoreLibs = array
	(
		'Std',
		'Json',
		'Config',
		'Lang',

		'Regex'
	);

	LoadCoreLibs($CoreLibs);
	
	#############
	# Functions #
	#############

	function LoadCoreLibs($Libs)
	{
		foreach($Libs as $Lib)
			require_once "{$Lib}.php";
	}

	function LoadLibs()
	{
		Std::Out();
		Std::Out('[INFO] [LIBS] Loading');

		$Libs = Config::Get('Libs');

		if(is_array($Libs))
		{
			foreach($Libs as $Lib)
			{
				$Path = "class/Lib/.{$Lib}.php";

				if(basename(dirname(realpath($Path))) === 'Lib')
				{
					if(is_readable($Path))
					{
						// Lint

						require_once $Path;
					}
					else
						Std::Out("[WARNING] [LIBS] Can't load {$Lib}. It is not readable");
				}
				else
					Std::Out("[WARNING] [LIBS] Can't load {$Lib}. It is not in Lib/ folder");
			}

			Std::Out('[INFO] [LIBS] Ready!');
		}
		else
			Std::Out('[WARNING] [LIBS] Config file is not an array');
	}