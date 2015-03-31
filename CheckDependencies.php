<?php
	const CHECK_EXTENSION = 1;
	const CHECK_FUNCTION = 2;

	$ToCheck = array
	(
		'curl' => array(CHECK_EXTENSION, 'curl'),
		'gd' => array(CHECK_EXTENSION, 'gd'),
		'openssl' => array(CHECK_EXTENSION, 'openssl')
	);

	$Keys = array_keys($ToCheck);

	$Count = count($ToCheck);
	for($i = 0; $i < $Count; $i++)
	{
		echo "Checking {$Keys[$i]}... ";

		switch($ToCheck[$Keys[$i]][0])
		{
			case CHECK_EXTENSION:
				if(extension_loaded($ToCheck[$Keys[$i]][1]))
					echo 'ok';
				else
					echo 'failed';
				break;
			case CHECK_FUNCTION:
				if(function_exists($ToCheck[$Keys[$i]][1]))
					echo 'ok';
				else
					echo 'failed';
				break;
		}

		echo PHP_EOL;
	}

	// ffmpeg