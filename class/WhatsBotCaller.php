<?php
	require_once 'Utils.php';

	class WhatsBotCaller
	{
		private $ModuleManager = null;
		private $Whatsapp = null;
		private $Utils = null;

		public function __construct(&$MDLM, WhatsappBridge &$WPB)
		{
			if($MDLM != null && !($MDLM instanceof ModuleManager)) // Podríamos testear en cada método si $this->ModuleManager está o no instanciado correctamente
				trigger_error('You must pass a ModuleManager to WhatsBotCaller', E_USER_ERROR);
				
			$this->ModuleManager = &$MDLM;
			$this->Whatsapp = &$WPB;
			$this->Utils = new Utils();
		}

		public function CallModule($Code, $Name, $Params, $From, $Original, $Data)
		{
			return eval($Code);
		}

		public function CallDomainPlainModule($Code, $From, $Data, $URL, $ParsedURL) // cambiar orden, data al final
		{
			return eval($Code);
		}
	}