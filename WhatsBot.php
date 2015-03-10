<?php
	require_once 'class/WhatsBot.php';
	require_once 'class/Others/Std.php';

	# Config
	$Debug = false;


	Std::Out('Starting WhatsBot...');

	$W = new WhatsBot($Debug);
	$W->Start();
	$W->Listen();