<?php
	$Libs = array
	(
		'Std',
		'Json',
		'Config',
		'Lang',

		'.Admin'
	);

	foreach($Libs as $Lib)
		require_once "{$Lib}.php";