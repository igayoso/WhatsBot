<?php
	$ToCheck = array
	(
		'curl' => array('extension', 'curl')
	);

	$Keys = array_keys($ToCheck);

	$Count = count($ToCheck);
	for($i = 0; $i < $Count; $i++)
	{
		echo "Checking {$Keys[$i]}... ";

		switch($ToCheck[$Keys[$i]][0])
		{
			case 'extension':
				if(extension_loaded($ToCheck[$Keys[$i]][1]))
					echo 'ok';
				else
					echo 'failed';
				break;
			case 'function':
				if(function_exists($ToCheck[$Keys[$i]][1]))
					echo 'ok';
				else
					echo 'failed';
				break;
		}

		echo PHP_EOL;
	}