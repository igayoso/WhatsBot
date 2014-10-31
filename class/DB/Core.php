<?php
	abstract class DBCore
	{
		protected $L = null;
		protected $C = null;

		protected $DB = null;

		public function __construct($Hostname, $Username, $Password, $Database, Logger &$Logger, Catcher &$Catcher)
		{
			$this->L = $Logger;
			$this->C = $Catcher;

			try
			{
				$this->DB = new PDO("mysql:host={$Hostname};dbname={$Database}", $Username, $Password);
			}
			catch (PDOException $E)
			{
				$this->C->onFatalException($E);
			}
		}
	}