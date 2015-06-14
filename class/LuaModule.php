<?php
	require_once 'Lib/_Loader.php';

	require_once 'Module.php';

	require_once 'LuaUtils.php';

	class LuaModule extends Module
	{
		use LuaUtils;

		protected $PathExtension = 'lua';
		protected $Extension = 'lua';

		private $Lua = null;

		protected function _Load()
		{
			$this->Lua = new Lua;

			$this->AssignUserConsts();
			$this->RegisterUsefulFunctions();

			$this->LinkObjects(array($this->ModuleManager, $this->WhatsBot, $this->WhatsApp));
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

		protected function GetLua()
		{ return $this->Lua; }
	}