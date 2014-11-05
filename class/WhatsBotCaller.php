<?php
	require_once 'Utils.php';

	class WhatsBotCaller
	{
		private $Whatsapp = null;
		private $Utils = null;

		public function __construct(WhatsappBridge &$WPB)
		{
			$this->Whatsapp = &$WPB;
			$this->Utils = new Utils();
		}

		public function CallModule($Code, $Params, $Original, $Data)
		{
			try
			{
				eval($Code); // return eval();
				return true;
			}
			catch (Exception $E)
			{
				throw new Exception($E->getMessage());
				return false;
			}
		}
	}