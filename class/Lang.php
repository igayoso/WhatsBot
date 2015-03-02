<?php
	require_once 'ConfigManager.php';

	class Lang
	{
		private $Section = null;
		private $Data = array('Main' => array());

		public function __construct($Section = 'Main')
		{
			$this->Section = $Section;

			$Data = Config::Get('Lang');

			if(is_array($Data))
				$this->Data = $Data;
		}

		public function __invoke($String)
		{
			if(!empty($this->Data[$this->Section][$String]))
			{
				$Args = func_get_args();

				if(count($Args) > 1)
				{
					$Args[0] = $this->Data[$this->Section][$String];
					
					return call_user_func_array('sprintf', $Args);
				}

				return $this->Data[$this->Section][$String];
			}

			return false;
		}
	}

	function Lang($String)
	{
		return call_user_func_array(new Lang, func_get_args());
	}