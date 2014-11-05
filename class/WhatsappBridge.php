<?php
	class WhatsappBridge
	{
		private $Whatsapp = null;

		public function __construct(WhatsProt &$WP)
		{
			$this->Whatsapp = &$WP;
		}

		public function SendMessage($To, $Message)
		{
			return $this->Whatsapp->SendMessage($To, $Message);
		}

		public function SetStatus($Status)
		{
			return $this->Whatsapp->sendStatusUpdate($Status);
		}
	}