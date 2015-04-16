<?php
	class Message
	{
		public $Me, $From, $User, $ID, $Type, $Time, $Name = null;

		public function __construct($Me, $From, $User, $ID, $Type, $Time, $Name)
		{
			$this->Me = $Me;
			$this->From = $From;
			$this->User = $User;
			$this->ID = $ID;
			$this->Type = $Type;
			$this->Time = intval($Time);
			$this->Name = $Name;
		}

		public function IsGroupMessage()
		{
			return $this->From !== $this->User;
		}
	}