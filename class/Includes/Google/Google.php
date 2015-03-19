<?php
	require_once 'Currency.php';
	require_once 'Search.php';
	require_once 'Translate.php';

	class Google
	{
		use GoogleCurrency;
		use GoogleSearch;
		use GoogleTranslate;
	}