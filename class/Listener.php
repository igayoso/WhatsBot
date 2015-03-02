<?php
	require_once 'WhatsApp.php';

	require_once 'Parser.php';

	class WhatsBotListener
	{
		private $WhatsApp = null;

		private $Parser = null;

		private $StartTime = null;

		public function __construct(WhatsApp $WhatsApp, WhatsBotParser $Parser)
		{
			$this->WhatsApp = $WhatsApp;
			$this->Parser = $Parser;

			$this->StartTime = time(); // Maybe a class/function
		}

		public function onGetMessage($Me, $From, $ID, $Type, $Time, $Name, $Text)
		{
			$this->Parser->ParseTextMessage($Me, $From, $From, $ID, $Type, $Time, $Name, $Text);
		}

		public function onGetGroupMessage($Me, $FromGroupJID, $FromUserJID, $ID, $Type, $Time, $Name, $Text)
		{
			$this->Parser->ParseTextMessage($Me, $FromGroupJID, $FromUserJID, $ID, $Type, $Time, $Name, $Text);
		}

		// Events

		/* To do: Hacer otra clase (Listener), que se encargue de loguear todo a la BD. Bindear ambas clases al EventManager 
		 * Esto creo que evitaría algunos problemas con los threads
		 */
	}