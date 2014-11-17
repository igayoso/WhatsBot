<?php
	class WhatsApiEventsManager
	{
		private $Classes = array();

		public function bindClass(&$Class)
		{
			$this->Classes[] = &$Class;
		}

		public function fire($Event, Array $Params)
		{
			echo $Event . PHP_EOL; // For debug - To delete

			for($i = 0; $i < count($this->Classes); $i++)
				if(method_exists($this->Classes[$i], $Event) && is_callable(array($this->Classes[$i], $Event), true)) // To do: If method is private it returns true, fix!
					call_user_func_array(array($this->Classes[$i], $Event), $Params);
		}
	}