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

			$Path = $this->MediaDirectory . DIRECTORY_SEPARATOR . $this->File;

			$this->Data = Data::Get($Path, false, false);

			if(empty($this->Data))
			{
				$this->Data = file_get_contents($this->URL);

				if($this->Data !== false)
				{
					Data::Set($Path, $this->Data);

					$this->Data = base64_encode($this->Data);

					return true;
				}

				return false;
			}
			else
				$this->Data = base64_decode($this->Data);

			return true;
		}

		public function GetData()
		{ return base64_decode($this->Data); }

		public function GetType()
		{ return $this->SubType; }
	}