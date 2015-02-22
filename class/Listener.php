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

		// Events

		/* To do: Hacer otra clase (Listener), que se encargue de loguear todo a la BD. Bindear ambas clases al EventManager 
		 * Esto creo que evitaría algunos problemas con los threads
		 */
	}