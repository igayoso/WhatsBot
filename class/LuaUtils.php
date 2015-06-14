<?php
	trait LuaUtils
	{
		# Vars

		protected function AssignVars(Array $Vars, $Prefix = null)
		{
			foreach($Vars as $Key => $Value)
				$this->AssignVar($Prefix . $Key, $Value);
		}

		protected function AssignVar($Key, $Value)
		{
			if(is_scalar($Value))
				if(is_object($this->GetLua()->Assign($Key, $Value))) // "Returns $this or NULL on failure"
					return true;

			Std::Out("[Warning] [Modules] (Lua) Can't assign a non-scalar value (\${$Key}, " . gettype($Value) . ')');

			return false;
		}

		# Consts

		protected function AssignConsts(Array $Consts, $Prefix = null)
		{
			return $this->AssignVars($Consts, $Prefix);
		}

		protected function AssignConst($Key, $Value)
		{
			return $this->AssignVar($Key, $Value);
		}

		protected function AssignUserConsts()
		{
			return $this->AssignConsts(get_defined_constants(true)['user']);
		}

		# Funcs

		protected function RegisterCallbacks(Array $Callbacks, $Prefix = null)
		{
			foreach($Callbacks as $Name => $Callback)
				$this->RegisterCallback($Prefix . $Name, $Callback);
		}

		protected function RegisterCallback($Name, $Callback)
		{
			if(is_object($this->GetLua()->RegisterCallback($Name, $Callback)))
				return true;

			Std::Out("[Warning] [Modules] (Lua) Can't register the {$Name} callback");

			return false;
		}

		protected function RegisterUsefulFunctions()
		{
			$Functions = array('var_dump' => 'var_dump');

			return $this->RegisterCallbacks($Functions);
		}

		# Objects

		protected function LinkObjects(Array $Objects, $ConstsWithPrefix = true, $MethodsWithPrefix = false, $PropertiesWithPrefix = false)
		{
			foreach($Objects as $Object)
				$this->LinkObject($Object, $ConstsWithPrefix, $MethodsWithPrefix, $PropertiesWithPrefix);
		}

		protected function LinkObject($Object, $ConstsWithPrefix = true, $MethodsWithPrefix = false, $PropertiesWithPrefix = false)
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

		abstract protected function GetLua();
	}