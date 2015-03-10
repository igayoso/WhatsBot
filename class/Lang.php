<?php
	require_once 'Others/Json.php';
	require_once 'Others/Std.php';

	class Lang
	{
		private $Section = null;
		private $Data = array();

		public function __construct($Section)
		{
			Std::Out();
			Std::Out('[INFO] [LANG] Loading');

			$this->Section = $Section;

			$Data = Json::Read("lang/{$Section}.json");

			if(is_array($Data))
				$this->Data = $Data;
			else
				Std::Out("[WARNING] [LANG] Can't load lang/{$Section}.json");

			Std::Out('[INFO] [LANG] Ready!');
		}

		public function Get($Key)
		{
			if(!empty($this->Data[$Key]))
			{
				$Args = func_get_args();

				if(count($Args) > 1)
				{
					$Args[0] = $this->Data[$Key];

					return call_user_func_array('sprintf', $Args);
				}

				return $this->Data[$Key];
			}

			Std::Out("[WARNING] [LANG] Key {$this->Section}::{$Key} doesn't exists");

			return false;
		}

		// Set()

		public function __invoke($Key)
		{
			return call_user_func_array(array($this, 'Get'), func_get_args());
		}
	}