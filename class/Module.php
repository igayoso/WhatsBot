<?php
	require_once 'Lib/_Loader.php';

	require_once 'ModuleManager.php';
	require_once 'WhatsBot.php';
	require_once 'WhatsApp.php';

	const SEND_USAGE = 2;

	const INTERNAL_ERROR = -1;
	const LANG_ERROR = -3;
	const NOT_ADMIN = -4;

	class Module
	{
		private $ModuleManager = null;
		private $WhatsBot = null;
		private $WhatsApp = null;

		private $Key = null;

		private $Name = null;
		private $AliasOf = null;

		private $Data = null;

		private $Loaded = false;
		private $Enabled = true;

		const LOADED = true;
		const NOT_LOADED = false;
		const LOAD_ERROR = -5;

		const ENABLED = true;
		const NOT_ENABLED = false;

		const NOT_READABLE = -2;

		const EXECUTED = true;

		public function __construct(ModuleManager $ModuleManager, WhatsBot $WhatsBot, WhatsApp $WhatsApp, $Key, $Name, $AliasOf)
		{
			$this->ModuleManager = $ModuleManager;
			$this->WhatsBot = $WhatsBot;
			$this->WhatsApp = $WhatsApp;

			$this->Key = $Key;

			$this->Name = $Name;
			$this->AliasOf = $AliasOf;

			$this->Load();
		}

		private function Load()
		{
			if($this->ModuleManager->KeyExists($this->Key))
			{
				$JPath = "class/Modules/{$this->Key}_{$this->AliasOf}.";
				$PPath = $JPath . 'php';
				$JPath .= 'json';

				if(basename(dirname(realpath($JPath))) === 'Modules')
				{
					$Json = Json::Decode($JPath);

					if(is_array($Json))
					{
						if(is_readable($PPath))
						{
							// Lint

							$this->Data = $Json;

							$this->Loaded = self::LOADED;

							return self::LOADED;
						}
						else
							Std::Out("[Warning] [Modules] Can't load {$this->Key}::{$this->Name} ({$this->AliasOf}). PHP file is not readable");
					}
					else
						Std::Out("[Warning] [Modules] Can't load {$this->Key}::{$this->Name} ({$this->AliasOf}). Json file is not readable/decodeable");
				}
				else
					Std::Out("[Warning] [Modules] Can't load {$this->Key}::{$this->Name} ({$this->AliasOf}). It is not in Modules folder");
			}

			$this->Loaded = self::NOT_LOADED;

			return self::NOT_LOADED;
		}

		public function Reload()
		{ return $this->Load(); }

		public function Execute(Message $Message, Array $Params = array())
		{
			if($Message->Time >= $this->WhatsBot->GetStartTime())
			{
				if($this->IsEnabled())
				{
					$Path = "class/Modules/{$this->Key}_{$this->AliasOf}.php";

					if(is_readable($Path))
					{
						$LangSection = "{$this->Key}_{$this->AliasOf}";

						$this->WhatsApp->SetLangSection($LangSection);
						$Lang = new Lang($LangSection);

						$ModuleManager = $this->ModuleManager;
						$WhatsBot = $this->WhatsBot;
						$WhatsApp = $this->WhatsApp;

						extract($Params);

						return include $Path;
					}

					Std::Out("[Warning] [Modules] Can't call {$this->Key}::{$this->Name} ({$this->AliasOf}). PHP file is not readable");

					return self::NOT_READABLE;
				}

				return self::NOT_ENABLED;
			}

			return self::EXECUTED;
		}

		public function IsAlias()
		{ return $this->Name != $this->AliasOf; }

		public function IsLoaded()
		{ return $this->Loaded; }

		public function IsEnabled()
		{ return $this->Enabled; }

		public function Enable()
		{ $this->Enabled = self::ENABLED; }

		public function Disable()
		{ $this->Enabled = self::NOT_ENABLED; }
	}