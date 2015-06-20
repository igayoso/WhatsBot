<?php
	require_once 'Lib/_Loader.php';

	require_once 'Thread.php';

	require_once 'LuaWithPHP.php';

	class LuaThread extends WhatsBotThread
	{
		protected $PathExtension = 'lua';
		protected $Extension = 'lua';

		protected function _Load()
		{ return self::LOADED; }

		protected function Execute()
		{
			try
			{
				$Lua = new LuaWithPHP;

				$Lua->AssignUserConstants();

				$Lua->LinkObjects(array($this->ThreadManager, $this->ModuleManager, $this->EventManager, $this->WhatsApp, $this->WhatsBot));

				$Lua->LinkObject($this, false, false, false);
				$Lua->AssignVariable('Data', $this->Data);

				$Lua->LinkObject(new Lang("Thread_{$this->Name}"), true, true, true);

				while(!$this->Stop && is_readable($this->XPath))
					if($Lua->Include($this->XPath) === false)
						$this->Stop('Lua fatal error');
			}
			catch(Exception $Exception)
			{
				Std::Out("[Warning] [Threads] (Lua) Can't execute {$this->Name}. " . get_class($Exception) . 'thrown (' . $Exception->GetMessage() . ')');
			}
		}
	}