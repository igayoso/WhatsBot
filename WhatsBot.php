<?php
	require_once __DIR__ . '/class/WhatsBot.php';

	# Debug

	$Arguments = getopt('d', array('debug'));

	$Debug = isset($Arguments['d']) || isset($Arguments['debug']);

	# Start

	Std::Out('Starting WhatsBot...');

	$W = new WhatsBot($Debug);
	$W->Start();
	$W->Listen();