<?php
	require_once 'WhatsBotCore.php';

	class WhatsBotListener extends WhatsBotCore
	{
		// Log to DB
		public function onGetMessage($Me, $From, $ID, $Type, $Time, $Name, $Text)
		{
			$this->Parser->ParseTextMessage($Me, $From, $ID, $Type, $Time, $Name, $Text);
		}

		public function onGetGroupMessage($Me, $FromG, $FromU, $ID, $Type, $Time, $Name, $Text)
		{
			$this->Parser->ParseTextMessage($Me, $FromG, $ID, $Type, $Time, $Name, $Text);
		}

		public function onDisconnect($Me, $Socket)
		{
			$this->connect(); // Why not disconnect? beacuse disconnect() will throw onDisconnect event again ==> infinite loop...
		}
	}