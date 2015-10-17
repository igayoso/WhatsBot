<?php
	require_once 'MediaMessage.php';

	class ImageMessage extends MediaMessage
	{
		public $Width = null;
		public $Height = null;

		protected $Preview = null;
		public $Caption = null;

		public function __construct($Me, $From, $User, $ID, $Type, $Time, $Name, $URL, $File, $Size, $MIME, $Hash, $Width, $Height, $Preview, $Caption)
		{
			$this->Width = (int) $Width;
			$this->Height = (int) $Height;
			$this->Preview = $Preview;
			$this->Caption = !empty($Caption) ? $Caption : null;

			parent::__construct($Me, $From, $User, $ID, $Type, $Time, $Name, 'image', $URL, $File, $Size, $MIME, $Hash);
		}

		public function GetPreview() // It's binary data, StorageListener can't log it (json_encode warning)
		{ return $this->Preview; }
	}