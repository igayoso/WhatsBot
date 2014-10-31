<?php
	// USE STDIN FOR NON FATAL
	class Catcher
	{
		private $Logger;

		private $Errors = null;

		public function __construct(Logger &$L = null)
		{
			if($L == null)
				$L = new Logger();

			$this->Logger = &$L;

			$this->Errors = array();
		}

		public function onError($E)
		{
			$this->Logger->logError($E);

			array_push($this->Errors, $E);
		}

		public function onFatalError($E)
		{
			$this->onError($E);

			exit($E);
		}

		public function onException($E, $Fatal = false)
		{
			$this->Logger->logException($E->getMessage(), $E->getLine(), $E->getFile());

			array_push($this->Errors, $E->getMessage());
		}

		public function onFatalException($E)
		{
			$this->onException($E);

			exit($E->getMessage());
		}

		public function GetErrors()
		{
			if(!is_array($this->Errors) || $this->Errors === array())
				return false;
			else
				return $this->Errors;
		}
	}