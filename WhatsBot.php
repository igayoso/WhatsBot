<?php
	require_once 'class/WhatsBot.php';

	$Debug = false;

	$W = new WhatsBot($Debug);
	$W->Listen();