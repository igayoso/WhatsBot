<?php
	require_once dirname(__FILE__) . '/_Loader.php';

	class Lang
	{
		private $FileManager = null;

		private $Section = null;
		private $Data = array();

		public function __construct($Section)
		{
			$this->FileManager = new FileManager('lang');

			if(func_num_args() > 1)
				$Section = implode('_', func_get_args());

			$this->Section = $Section;

			$Data = $this->FileManager->GetJson($this->Section . '.json');

			if(is_array($Data))
				$this->Data = $Data;
			else
				Std::Out("[Warning] [Lang] Can't load {$this->Section}");
		}

		public function Get($Key)
		{
			if(isset($this->Data[$Key]))
			{
				$Args = func_get_args();

				if(func_num_args() > 1)
				{
					$Args[0] = $this->Data[$Key];

					return call_user_func_array('sprintf', $Args);
				}

				return $this->Data[$Key];
			}
			else
				Std::Out("[Warning] [Lang] Key {$this->Section}::{$Key} doesn't exist");

			return false;
		}

		public function __invoke($Key)
		{ return call_user_func_array(array($this, 'Get'), func_get_args()); }

		// Set()
	}