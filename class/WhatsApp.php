<?php
	require_once 'whatsapi/whatsprot.class.php';

	class WhatsApp
	{
		private $WhatsApp = null;

		public function __construct(WhatsProt $WhatsApp)
		{
			$this->WhatsApp = $WhatsApp;
		}

		public function EventManager()
		{ return $this->WhatsApp->EventManager(); }

		// Functions
		// Send{type}Message => Send{type} only ?
	}