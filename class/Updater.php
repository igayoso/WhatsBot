<?php
	class Updater
	{
		protected $Endpoint = 'https://github.com';
		protected $DownloadEndpoint = 'https://raw.githubusercontent.com';
		protected $APIEndpoint = 'https://api.github.com';
		protected $Repo = null;

		public function __construct($Repo = 'fermino/WhatsBot')
		{
			$this->Repo = $Repo;
		}

		public function CheckUpdates()
		{
			$Local = Utils::GetJson('data/Version.json');
			$Remote = Utils::GetRemoteJson("{$this->DownloadEndpoint}/{$this->Repo}/master/data/Version.json");

			if($Local !== false && $Remote !== false)
			{
				if($Remote['version'] > $Local['version'])
					Utils::Write("There is a new update. Download it from {$this->Endpoint}/{$this->Repo}/releases");
				else
					Utils::Write('WhatsBot is updated...');
			}
			else
				Utils::Write('Can\'t connect to server to check updates...');
		}
	}