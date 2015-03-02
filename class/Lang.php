<?php
	require_once 'ConfigManager.php';

	class Lang
	{
		private $Section = null;
		private $Data = array('main' => array());

		public function __construct($Section = 'main')
		{
			$this->Section = $Section;

			$Data = Config::Get('Lang');

			if(is_array($Data))
				$this->Data = $Data;
		}

		public function __invoke($String)
		{
			if(!empty($this->Data[$this->Section][$String]))
				return $this->Data[$this->Section][$String];

			return false;
		}
	}

	function Lang($String, $Section = 'main')
	{
		$Lang = new Lang($Section);

		return $Lang($String);
	}