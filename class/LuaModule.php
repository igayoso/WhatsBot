<?php
	require_once 'Lib/_Loader.php';

	require_once 'Module.php';

	class LuaModule extends Module
	{
		protected $PathExtension = 'lua';
		protected $Extension = 'lua';

		private $Lua = null;

		protected function _Load()
		{
			$this->Lua = new Lua;

			$Constants = get_defined_constants(true)['user'];
			$Functions = array('var_dump' => 'var_dump');
			$Objects = array($this, $this->ModuleManager, $this->WhatsBot, $this->WhatsApp);

			$this->AssignConsts($Constants);
			$this->RegisterCallbacks($Functions);
			$this->LinkObjects($Objects);
		}

		# Vars

		private function AssignVars(Array $Vars, $Prefix = null)
		{
			foreach($Vars as $Key => $Value)
				$this->AssignVar($Prefix . $Key, $Value);
		}

		private function AssignVar($Key, $Value)
		{
			if(is_scalar($Value))
				if(is_object($this->Lua->Assign($Key, $Value))) // "Returns $this or NULL on failure"
					return true;

			Std::Out("[Warning] [Modules] (Lua) Can't assign a non-scalar value (\${$Key}, " . gettype($Value) . ')');

			return false;
		}

		# Consts

		private function AssignConsts(Array $Consts, $Prefix = null)
		{
			return $this->AssignVars($Consts, $Prefix);
		}

		private function AssignConst($Key, $Value)
		{
			return $this->AssignVar($Key, $Value);
		}

		# Funcs

		private function RegisterCallbacks(Array $Callbacks, $Prefix = null)
		{
			foreach($Callbacks as $Name => $Callback)
				$this->RegisterCallback($Prefix . $Name, $Callback);
		}

		private function RegisterCallback($Name, $Callback)
		{
			if(is_object($this->Lua->RegisterCallback($Name, $Callback)))
				return true;

			Std::Out("[Warning] [Modules] (Lua) Can't register the {$Name} callback");

			return false;
		}

		# Objects

		private function LinkObjects(Array $Objects, $ConstsWithPrefix = true, $MethodsWithPrefix = false, $PropertiesWithPrefix = false)
		{
			foreach($Objects as $Object)
				$this->LinkObject($Object, $ConstsWithPrefix, $MethodsWithPrefix, $PropertiesWithPrefix);
		}

		private function LinkObject($Object, $ConstsWithPrefix = true, $MethodsWithPrefix = false, $PropertiesWithPrefix = false)
		{
			# Reflection

			$Class = new ReflectionClass($Object);
			$ClassName = $Class->GetShortName();

			$Constants = $Class->GetConstants();
			$Methods = $Class->GetMethods(ReflectionMethod::IS_PUBLIC);
			$Properties = $Class->GetProperties(ReflectionProperty::IS_PUBLIC);

			# Prefixes

			$ClassPrefix = $Class . '_';

			$ConstsPrefix = $ConstsWithPrefix ? strtoupper($ClassPrefix) : null;
			$MethodsPrefix = $MethodsWithPrefix ? $ClassPrefix : null;
			$PropertiesPrefix = $PropertiesWithPrefix ? $ClassPrefix : null;

			# Consts

			$this->AssignConsts($Constants, $ConstsPrefix);

			# Callbacks

			/**
			 * If you can do this
			 * In one line
			 * Without using aux vars
			 * Without repeating code
			 * Without doing things like: asd($a = dsa(), ...)
			 * 
			 * YOU'RE A GOD. 
			 */

			// Convert ReflectionMethod's into array(MethodName => Callback, [...])

			$Methods = array_map(function($Method) { return $Method->name; }, $Methods);
			$Methods = array_combine($Methods, array_map(function($Method) use($Object) { return array($Object, $Method); }, $Methods));

			$Methods = array_filter($Methods, function($Method) { return strpos($Method, '__') !== 0; }, ARRAY_FILTER_USE_KEY);

			$this->RegisterCallbacks($Methods, $MethodsPrefix);

			# Vars

			// Convert ReflectionProperty's into array(PropertyName => Value, [...])

			$Properties = array_map(function($Property) { return $Property->name; }, $Properties);
			$Properties = array_combine($Properties, array_map(function($Property) use($Object) { return $Object->{$Property}; }, $Properties));

			$this->AssignVars($Properties);
		}

		public function Execute(Message $Message, Array $Params = array())
		{
			if($Message->Time >= $this->WhatsBot->GetStartTime())
			{
				if($this->IsEnabled())
				{
					if(is_readable($this->XPath))
					{
						$LangSection = "{$this->Key}_{$this->AliasOf}";

						$this->WhatsApp->SetLangSection($LangSection);
						$this->LinkObject(new Lang($LangSection), true, true, true);

						$this->AssignVars($Params);

						$this->LinkObject($Message, true, false, false);

						$Return = $this->Lua->Include($this->XPath);

						if($Return !== null)
							return $Return;

						return self::EXECUTED;
					}

					Std::Out("[Warning] [Modules] Can't execute {$this->Key}::{$this->Name} ({$this->AliasOf}). {$this->PathExtension} file is not readable");

					return self::NOT_READABLE;
				}

				return self::NOT_ENABLED;
			}

			return self::EXECUTED;
		}
	}