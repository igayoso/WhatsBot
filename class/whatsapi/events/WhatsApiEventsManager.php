<?php
	class WhatsApiEventsManager
	{
		private $Classes = array();
		private $Functions = array();

		public function BindListener($Class)
		{
			$this->Classes[] = $Class;
		}

		public function BindEvent($Event, $Function)
		{
			$this->Functions[] = array($Event, $Function);
		}

		public function Fire($Event, Array $Params = array())
		{
			foreach($this->Classes as $Class)
				if(method_exists($Class, $Event) && is_callable(array($Class, $Event)))
					call_user_func_array(array($Class, $Event), $Params);

			$Functions = array_filter($this->Functions, function($Value)
			{
				return $Value[0] === $Event;
			});

			foreach($Functions as $Function)
				call_user_func_array($Function[1], $Params);
		}
	}