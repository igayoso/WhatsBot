<?php
	class StringParser
	{
		protected $Data = array();
		protected $ToReplace = array();

		public function __construct()
		{
			$this->Data = Utils::GetJson('config/Modules.json')['parser'];
			$this->ToReplace = Utils::GetJson('config/Parser.json')['replace'];
		}

		public function Parse($String, &$Flags)
		{
			$Flags = array('question' => false);

			$PString = strtolower($String);
			$PString = str_replace($this->ToReplace[0], $this->ToReplace[1], $PString);

			$PString = str_replace('?', '', $PString, $Count);
			if($Count)
				$Flags['question'] = true;

			$Splitted = explode(' ', $PString);

			$Action = $this->GetAction($Splitted);

			if($Action !== false)
			{
				$Object = $this->GetObject($Splitted, $Action[0], $Action[2]);

				return array($Action, $Object);
			}

			return false;
		}

		protected function GetAction(Array $Splitted)
		{
			foreach($this->Data as $Action => $Data)
				foreach($Splitted as $Offset => $Word)
					if(in_array($Word, $Data[0]))
						return array($Action, $Word, $Offset);

			return false;
		}

		protected function GetObject(Array $Splitted, $Action, $Offset)
		{
			$Splitted = array_slice($Splitted, $Offset + 1, null, true);

			foreach($this->Data[$Action][1] as $Object => $Data)
				foreach($Splitted as $Offset => $Word)
					if(in_array($Word, $Data))
						return array($Object, $Word, $Offset);

			return false;
		}
	}