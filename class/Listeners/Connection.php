<?php
	require_once __DIR__ . '/../Lib/_Loader.php';

	require_once __DIR__ . '/Core.php';

	class ConnectionListener extends WhatsBotListenerCore
	{
		protected function Load()
		{ }

		public function onPing($Me, $ID)
		{
			$this->WhatsApp->SendPong($ID);
		}
	}