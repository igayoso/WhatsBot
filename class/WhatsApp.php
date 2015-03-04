<?php
	require_once 'whatsapi/whatsprot.class.php';

	require_once 'WhatsApp/Message.php';

	class WhatsApp
	{
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

		public function SendMessage($To, $Message, $ID = null)
		{ return $this->WhatsApp->SendMessage($To, $Message, $ID); }

		// Functions
		// Send{type}Message => Send{type} only ?
	}