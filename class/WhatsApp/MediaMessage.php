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

		protected $Data = null;

		private $MediaDirectory = 'media';

		public function __construct($Me, $From, $User, $ID, $Type, $Time, $Name, $SubType, $URL, $File, $Size, $MIME, $Hash)
		{
			$this->SubType = $SubType;
			$this->URL = $URL;
			$this->File = $File;
			$this->Size = (int) $Size;
			$this->MIME = $MIME;
			$this->Hash = $Hash;

			parent::__construct($Me, $From, $User, $ID, $Type, $Time, $Name);

			$this->LoadData();
		}

		private function LoadData()
		{
			Data::CreateDirectory($this->MediaDirectory);

			$Path = "{$this->MediaDirectory}/{$this->File}";

			$this->Data = Data::Get($Path, false, false);

			if(empty($this->Data))
			{
				if(!empty($this->URL))
				{
					$this->Data = file_get_contents($this->URL);

					if(!empty($this->Data))
						return Data::Set($Path, $this->Data);
				}

				$this->Data = null;

				return false;
			}

			return true;
		}

		public function GetData()
		{
			// json_encode can't encode $this->Data

			return $this->Data;
		}

		public function GetType()
		{ return $this->SubType; }
	}