<?php
	require_once 'Message.php';

	class TextMessage extends Message
	{
		public $Text = null;

		public function __construct($Me, $From, $User, $ID, $Type, $Time, $Name, $Text)
		{
			$this->Text = $Text;

			parent::__construct($Me, $From, $User, $ID, $Type, $Time, $Name);
		}

		public function GetText($ModuleName, Array $Params = array(), $Else = false)
		{
			$Length = strlen($ModuleName) + 2;

			foreach($Params as $Param)
				$Length += strlen($Param) + 1;

			$Text = substr($this->Text, $Length);

			return $Text === false ? $Else : $Text;
		}

		public function GetType()
		{ return $this->Type; }
	}