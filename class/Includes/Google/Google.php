<?php
	require_once 'Currency.php';
	require_once 'Translate.php';

	class Google
	{
		use GoogleCurrency;
		use GoogleTranslate;
	}