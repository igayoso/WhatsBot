<?php
	abstract class Message
	{
		public $Me = null;

		public $From = null;
		public $User = null;

		public $ID = null;
		public $Type = null;
		public $Time = null;
		public $Name = null;

		private $LogDirectory = 'log';

		public function __construct($Me, $From, $User, $ID, $Type, $Time, $Name)
		{
			$this->Me = $Me;
			$this->From = $From;
			$this->User = $User;
			$this->ID = $ID;
			$this->Type = $Type;
			$this->Time = (int) $Time;
			$this->Name = $Name;
		}

		public function Log()
		{
			Data::CreateDirectory($this->LogDirectory);

			$Pattern = sprintf('%s_%s', (new DateTime('now', new DateTimeZone('GMT')))->format('Y-m-d'), $this->GetType());

			$Path = $this->LogDirectory . DIRECTORY_SEPARATOR . $Pattern;

			$Log = Data::Get($Path, true, false);

			if(empty($Log) || !is_array($Log))
				$Log = array();

			$Log[] = $this;

			return Data::Set($Path, $Log, true);
		}

		abstract public function GetType();

		public function IsGroupMessage()
		{
			return $this->From !== $this->User;
		}
	}