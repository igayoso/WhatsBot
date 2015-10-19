<?php
	require_once dirname(__FILE__) . '/../Lib/_Loader.php';

	require_once dirname(__FILE__) . '/Message.php';

	class MediaMessage extends Message
	{
		public $SubType = null;

		public $URL = null;
		public $File = null;
		public $Size = null;

		public $MIME = null;
		public $Hash = null;

		protected $Data = null;

		protected $MediaDirectory = 'media';

		protected $PreviewFileNameSuffix = 'preview';

		public function __construct($Me, $From, $User, $ID, $Type, $Time, $Name, $SubType, $URL, $File, $Size, $MIME, $Hash)
		{
			$this->SubType = $SubType;
			$this->URL = !empty($URL) ? $URL : null;
			$this->File = $File;
			$this->Size = (int) $Size;
			$this->MIME = $MIME;
			$this->Hash = $Hash;

			parent::__construct($Me, $From, $User, $ID, $Type, $Time, $Name);

			$this->LoadBinaryData();
		}

		private function LoadBinaryData()
		{
			if(!empty($this->File))
			{
				Data::CreateDirectory($this->MediaDirectory);

				$Pathinfo = pathinfo($this->File);

				if(!empty($Pathinfo['filename']) && !empty($Pathinfo['extension']))
				{
					$this->LoadData($Pathinfo['filename'], $Pathinfo['extension']);

					$this->LoadPreview($Pathinfo['filename'], $Pathinfo['extension']);

					// Return x && y ?
				}
			}

			return false;
		}

		private function LoadData($FileName, $Extension)
		{
			if(isset($this->Data))
			{
				$Path = "{$this->MediaDirectory}/{$FileName}.{$Extension}";

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

			return false;
		}

		private function LoadPreview($FileName, $Extension)
		{
			if(isset($this->Preview))
			{
				$Path = "{$this->MediaDirectory}/{$FileName}.";

				if(!empty($this->PreviewFileNameSuffix))
					$Path .= $this->PreviewFileNameSuffix . '.';

				$Path .= $Extension;

				$Preview = Data::Get($Path, false, false);

				if(!empty($Preview))
				{
					if(empty($this->Preview))
						$this->Preview = $Preview;

					return true;
				}
				elseif(!empty($this->Preview))
					return Data::Set($Path, $this->Preview);

				return false;
			}

			return false;
		}

		public function GetData() // It's binary data, StorageListener can't log it (json_encode warning)
		{ return $this->Data; }

		public function GetType()
		{ return $this->SubType; }
	}