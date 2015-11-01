<?php
	namespace WhatsApp;

	abstract class Message
	{
		public $Me = null;

		public $From = null;
		public $User = null;

		public $ID = null;
		public $Type = null;
		public $Time = null;
		public $Name = null;

		protected $LogDirectory = 'log';

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
			$Filename = sprintf('%s_%s', (new \DateTime('now', new \DateTimeZone('GMT')))->format('Y-m-d'), $this->GetType());

			$Log = \Data::Get($Filename, true, false, array($this->LogDirectory));

			if(empty($Log) || !is_array($Log))
				$Log = array();

			$Log[] = $this;

			return \Data::Set($Filename, $Log, true, true, array($this->LogDirectory));
		}

		public function GetType()
		{ return $this->Type; }

		public function IsGroupMessage()
		{
			return $this->From !== $this->User;
		}
	}