<?php
	require_once 'whatsapi/whatsprot.class.php';

	require_once 'WhatsApp/Functions.php';

	class WhatsApp
	{
		private $WhatsApp = null;

		public function __construct(WhatsProt $WhatsApp)
		{
			$this->WhatsApp = $WhatsApp;
		}

		# Config

		public function EventManager()
		{ return $this->WhatsApp->EventManager(); }

		# Connection

		public function Connect()
		{ return $this->WhatsApp->Connect(); }

		public function IsConnected()
		{ return $this->WhatsApp->IsConnected(); }

		public function Disconnect()
		{ return $this->WhatsApp->Disconnect(); }
		
		# Login

		public function LoginWithPassword($Password)
		{ return $this->WhatsApp->LoginWithPassword($Password); }

		# Listen

		public function PollMessage($AutoReceipt = true)
		{ return $this->WhatsApp->PollMessage($AutoReceipt); }

		# Messages

		public function SendMessage($To, $Message, $ID = null)
		{ return $this->WhatsApp->SendMessage($To, $Message, $ID); }

		# Others

		public function SendPing()
		{ return $this->WhatsApp->SendPing(); }

		// Functions
		// Send{type}Message => Send{type} only ?
	}