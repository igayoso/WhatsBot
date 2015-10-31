<?php
	require_once __DIR__ . '/class/WhatsBot.php';

	# Debug

	$Arguments = getopt('d');

	$Debug = isset($Arguments['d']);

	# Start

	Std::Out('Starting WhatsBot...');

	$W = new WhatsBot($Debug);
	$W->Start();
	$W->Listen();