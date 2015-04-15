<?php
	$Libs = array
	(
		'Std',
		'Json',
		'Config',
		'Lang'
	);

	foreach($Libs as $Lib)
		require_once "{$Lib}.php";