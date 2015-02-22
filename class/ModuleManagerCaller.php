<?php
	trait ModuleManagerCaller
	{
		private function CallModule($Key, $Name, Array $Params)
		{
			if($this->ModuleExists($Key, $Name))
			{
				$WhatsApp = $this->WhatsApp;
				$ModuleManager = $this;

				// Lang

				$ModuleData = $this->GetModuleData($Key, $Name);

				if($ModuleData !== false)
				{
					if(is_readable($ModuleData['file']))
					{
						extract($Params);

						return include $ModuleData['file'];
					}
				}
			}

			return false;
		}

		public function CallCommandModule($ModuleName, $Me, $From, $ID, $Type, $Time, $Name, $Text, $Params)
		{
			return $this->CallModule('command', $ModuleName, array
			(
				'Me' => $Me,
				'From' => $From,
				'ID' => $ID,
				'Type' => $Type,
				'Time' => $Time,
				'Name' => $Name,
				'Text' => $Text,
				'Params' => $Params
			);
		}
	}