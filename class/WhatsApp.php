<?php
	require_once 'WhatsAppJID.php';

	require_once 'whatsapi/whatsprot.class.php';

	class WhatsApp
	{
		use WhatsAppJID;

		private $WhatsApp = null;

		public function __construct(WhatsProt $WhatsApp)
		{
			$this->WhatsApp = $WhatsApp;
		}

		public function EventManager()
		{ return $this->WhatsApp->EventManager(); }

		public function IsConnected()
		{ return $this->WhatsApp->IsConnected(); }

		public function Connect()
		{ return $this->WhatsApp->Connect(); }

		public function LoginWithPassword($Password)
		{ return $this->WhatsApp->LoginWithPassword($Password); }

		public function PollMessage($AutoReceipt = true)
		{ return $this->WhatsApp->PollMessage($AutoReceipt); }

		public function SendPing()
		{ return $this->WhatsApp->SendPing(); }

		// Functions
		// Send{type}Message => Send{type} only ?
	}