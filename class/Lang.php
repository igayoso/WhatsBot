<?php
	require_once 'Others/Json.php';

	class Lang
	{
		private $Section = null;
		private $Data = array();

		public function __construct($Section)
		{
			$this->Section = $Section;

			$Data = Json::Read("lang/{$Section}.json");

			if(is_array($Data))
				$this->Data = $Data;
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

			return false;
		}

		// Set()

		public function __invoke($Key)
		{
			return call_user_func_array(array($this, 'Get'), func_get_args());
		}
	}