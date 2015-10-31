<?php
	require_once __DIR__ . '/_Loader.php';

	class Lang
	{
		private $FileManager = null;

		private $Section = null;
		private $Data = array();

		public function __construct($Section) // ...$Sections
		{
			$this->FileManager = new FileManager('lang');

			$this->Section = implode('_', func_get_args());

			$Data = $this->FileManager->GetJson($this->Section . '.json');

			if(is_array($Data))
				$this->Data = $Data;
			else
				Std::Out("[Warning] [Lang] Can't load {$this->Section}");
		}

		public function Get($Key) // $Key, ...$Args
		{
			if(isset($this->Data[$Key]))
			{
				$Args = func_get_args();

				if(func_num_args() > 1)
				{
					$Args[0] = $this->Data[$Key];

					return call_user_func_array('sprintf', $Args); // sprintf($Key, ...$Args)
				}

				return $this->Data[$Key];
			}
			else
				Std::Out("[Warning] [Lang] Key {$this->Section}::{$Key} doesn't exist");

			return false;
		}

		public function __invoke($Key) // $Key, ...$Args
		{ return call_user_func_array(array($this, 'Get'), func_get_args()); } // $this->Get($Key, ...$Args)

		// Set()
	}