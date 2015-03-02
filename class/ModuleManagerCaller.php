<?php
	trait ModuleManagerCaller
	{
		private function CallModule($Key, $ModuleName, Array $Params)
		{
			if($this->ModuleExists($Key, $ModuleName))
			{
				$Module = $this->GetModule($Key, $ModuleName);

				if($Module !== false)
				{
					if(is_readable($Module['file']))
					{
						$WhatsApp = $this->WhatsApp;
						$ModuleManager = $this;

						$Lang = new Lang("{Key}_{$ModuleName}");

						extract($Params);

						return include $Module['file'];
					}
				}
			}

			return false;
		}

		public function CallCommandModule($ModuleName, $Me, $From, $User, $ID, $Type, $Time, $Name, $Text, $Params)
		{
			return $this->CallModule('command', $ModuleName, array
			(
				'Me' => $Me,
				'From' => $From,
				'User' => $User,
				'ID' => $ID,
				'Type' => $Type,
				'Time' => $Time,
				'Name' => $Name,
				'Text' => $Text,
				'Params' => $Params
			));
		}
	}