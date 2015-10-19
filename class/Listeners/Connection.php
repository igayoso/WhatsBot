<?php
	require_once dirname(__FILE__) . '/../Lib/_Loader.php';

	require_once dirname(__FILE__) . '/Core.php';

	class ConnectionListener extends WhatsBotListenerCore
	{
		protected function Load()
		{ }

		public function onPing($Me, $ID)
		{
			$this->WhatsApp->SendPong($ID);
		}
	}