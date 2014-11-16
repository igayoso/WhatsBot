<?php
	class WhatsBotListener
	{
		private $Whatsapp = null;
		private $Password = null;

		private $Parser = null;

		public function __construct(WhatsProt &$Whatsapp, $Password, WhatsBotParser &$Parser)
		{
			$this->Whatsapp = &$Whatsapp;
			$this->Password = $Password;

			$this->Parser = &$Parser;
		}

		public function onGetMessage($Me, $From, $ID, $Type, $Time, $Name, $Text)
		{
			$this->Parser->ParseTextMessage($Me, null, $From, $ID, $Type, $Time, $Name, $Text);
		}

		public function onGetGroupMessage($Me, $FromG, $FromU, $ID, $Type, $Time, $Name, $Text)
		{
			$this->Parser->ParseTextMessage($Me, $FromG, $FromU, $ID, $Type, $Time, $Name, $Text);
		}
	}