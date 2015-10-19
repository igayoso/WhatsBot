<?php
	require_once dirname(__FILE__) . '/class/WhatsBot.php';

	# Debug

	$Arguments = getopt('d');

	$Debug = isset($Arguments['d']);

	# Start

	Std::Out('Starting WhatsBot...');

	$W = new WhatsBot($Debug);
	$W->Start();
	$W->Listen();