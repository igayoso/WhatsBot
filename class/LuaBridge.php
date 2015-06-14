<?php
	require_once 'Lib/_Loader.php';

	class LuaBridge
	{
		private $Lua = null;

		public function __construct(Lua $Lua)
		{
			$this->Lua = $Lua;
		}

		public function php_load_function($Function)
		{
			try
			{
				if(function_exists($Function) && is_callable($Function))
					if(is_object($this->Lua->RegisterCallback($Function, $Function)))
						return true;

				Std::Out("[Warning] [LuaBridge] Can't load {$Function} function");
			}
			catch(Exception $Exception)
			{
				Std::Out('[Warning] [LuaBridge] ' . get_class($Exception) . " thrown while loading {$Function} function");
			}

			return false;
		}
	}