<?php
	require_once 'class/Lib/_Loader.php';

	class WhatsApiEventsManager
	{
		private $Listeners = array();

		public function BindListener($Listener, $Key = null)
		{
			if(is_object($Listener))
			{
				$ClassName = get_class($Listener);

				if($Key === null)
					$Key = $this->GetNewKey();

				if(is_int($Key) || is_string($Key))
				{
					if(isset($this->Listeners[$Key]))
						Std::Out("[Warnng] [Events] Listener {$ClassName} : {$Key} will be overwrited");

					$this->Listeners[$Key] = $Listener;

					Std::Out("[Info] [Events] {$ClassName} : {$Key} binded");

					return $Key;
				}
				else
					Std::Out("[Warning] [Events] Trying to bind {$ClassName} with illegal offset type (" . var_export($Key, true) . ')');
			}
			else
				Std::Out('[Warning] [Events] Trying to bind non-object (' . var_export($Key, true) . ')');

			return false;
		}

		private function GetNewKey()
		{
			$Max = -1;

			foreach(array_keys($this->Listeners) as $Key)
				if(is_int($Key) && $Key > $Max)
					$Max = $Key;

			return ++$Max;
		}

		public function UnbindListener($Key)
		{
			if(isset($this->Listeners[$Key]))
			{
				unset($this->Listeners[$Key]);

				return true;
			}

			return false;
		}

		public function Fire($Event, Array $Params = array())
		{
			foreach($this->Listeners as $Listener)
				if(method_exists($Listener, $Event) && is_callable(array($Listener, $Event)))
					call_user_func_array(array($Listener, $Event), $Params);
		}
	}