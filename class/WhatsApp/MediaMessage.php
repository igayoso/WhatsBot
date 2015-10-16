<?php
	require_once 'Message.php';

	class MediaMessage extends Message
	{
		public $SubType = null;

		public $URL = null;
		public $File = null;
		public $Size = null;

		public $MIME = null;
		public $Hash = null;

		public function __construct($Me, $From, $User, $ID, $Type, $Time, $Name, $SubType, $URL, $File, $Size, $MIME, $Hash)
		{
			$this->SubType = $SubType;
			$this->URL = $URL;
			$this->File = $File;
			$this->Size = (int) $Size;
			$this->MIME = $MIME;
			$this->Hash = $Hash;

			parent::__construct($Me, $From, $User, $ID, $Type, $Time, $Name);
		}

		public function GetType()
		{ return $this->SubType; }
	}