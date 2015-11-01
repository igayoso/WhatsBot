<?php
	namespace WhatsApp;

	require_once __DIR__ . '/../Lib/_Loader.php';

	require_once __DIR__ . '/MediaMessage.php';

	class vCardMessage extends MediaMessage
	{
		public $vCardName = null;
		public $vCard = null;

		protected $Preview = null;

		protected $PreviewFilenameSuffix = false;

		public function __construct($Me, $From, $User, $ID, $Type, $Time, $Name, $vCardName, $vCard)
		{
			$this->vCardName = $vCardName;
			$this->vCard = new \vCard(false, $vCard);

			$this->Preview = $vCard;

			parent::__construct($Me, $From, $User, $ID, $Type, $Time, $Name, 'vcard', null, $ID . '.vcf', null, null, null);
		}
	}