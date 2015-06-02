<?php
	require_once 'Lib/_Loader.php';

	require_once 'ThreadTasks.php';

	class WhatsBotThread extends Thread
	{
		use ThreadTasks;
		
		private $Name = null;

		private $Data = array();

		private $Loaded = false;

		private $Exit = false;

		const LOADED = true;
		const NOT_LOADED = false;
		const LOAD_ERROR = -1;

		public function __construct($Name)
		{
			$this->Name = $Name;

			$this->Load();
		}

		public function Load()
		{
			$JPath = "class/Threads/{$this->Name}.";
			$PPath = $JPath . 'php';
			$JPath .= 'json';

			if(basename(dirname(realpath($JPath))) === 'Threads')
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
						Std::Out("[Warning] [Threads] Can't load {$this->Name}. PHP file is not readable");
				}
				else
					Std::Out("[Warning] [Threads] Can't load {$this->Name}. Json file is not readable");
			}
			else
				Std::Out("[Warning] [Threads] Can't load {$this->Name}. It is not in Threads folder");

			$this->Loaded = self::NOT_LOADED;

			return self::NOT_LOADED;
		}

		public function Run()
		{
			sleep(5);
			
			require_once 'Lib/_Loader.php';

			$this->Execute();

			if(strtolower(substr(PHP_OS, 0, 3)) === 'win') // When a thread gets stopped, windows says "PHP-CLI has stopped working *trollface*"
				while(true) // So ...
					sleep(1); // We will wait ... ... ...
		}

		private function Execute()
		{
			$LangSection = "Thread_{$this->Name}";
			$Lang = new Lang($LangSection);

			$Path = "class/Threads/{$this->Name}.php";

			while(!$this->Exit && is_readable($Path))
				include($Path);

			Std::Out();
			Std::Out("[Info] [Threads] {$this->Name} stopped ($this->Exit)");
		}

		public function IsLoaded()
		{ return $this->Loaded; }

		public function _Exit($Code = 1)
		{ $this->Exit = $Code; }
	}