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
	}