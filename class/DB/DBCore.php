<?php
	abstract class DBCore
	{
		protected $DB = null;

		public function __construct($Filename = 'DB.sqlite')
		{
			$this->DB = new PDO("sqlite:data/{$Filename}");
		}
	}