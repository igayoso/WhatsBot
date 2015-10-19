<?php
	require_once dirname(__FILE__) . '/MediaMessage.php';

	class LocationMessage extends MediaMessage
	{
		public $Author = null;

		public $Longitude = null;
		public $Latitude = null;

		protected $Preview = null;

		public function __construct($Me, $From, $User, $ID, $Type, $Time, $Name, $URL, $Author, $Longitude, $Latitude, $Preview)
		{
			$this->Author = !empty($Author) ? $Author : null;
			$this->Longitude = $Longitude;
			$this->Latitude = $Latitude;
			$this->Preview = $Preview;

			parent::__construct($Me, $From, $User, $ID, $Type, $Time, $Name, 'location', $URL, $ID . '.jpg', null, null, null);
		}

		public function GetPreview() // It's binary data, StorageListener can't log it (json_encode warning)
		{ return $this->Preview; }
	}