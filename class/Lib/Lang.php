<?php
	require_once '_Loader.php';

	class Lang
	{
		private $Section = null;
		private $Data = array();

		public function __construct($Section)
		{
			$this->Section = $Section;

			$Data = Json::Decode("lang/{$Section}.json");

			if(is_array($Data))
				$this->Data = $Data;
			else
				Std::Out("[WARNING] [LANG] Can't load lang/{$Section}.json");
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
	}