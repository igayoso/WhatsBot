<?php
	class WhatsApiEventsManager
	{
		private $Debug = false;

		private $Classes = array();

		public function setDebug($Debug)
		{
			$this->Debug = $Debug;
		}

		public function bindClass(&$Class)
		{
			$this->Classes[] = &$Class;

			if($this->Debug)
				echo 'Class ' . get_class($Class) . ' binded...' . PHP_EOL;
		}

		public function fire($Event, Array $Params)
		{
			if($this->Debug)
				echo "Event fired: {$Event}" . PHP_EOL;

			for($i = 0; $i < count($this->Classes); $i++)
				if(method_exists($this->Classes[$i], $Event) && is_callable(array($this->Classes[$i], $Event), true)) // To do: If method is private it returns true, fix!
					call_user_func_array(array($this->Classes[$i], $Event), $Params);
		}
	}