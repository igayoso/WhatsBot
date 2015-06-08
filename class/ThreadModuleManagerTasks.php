<?php
	require_once 'Thread.php';

	const MODULEMANAGER_TASK = 3;

	class ThreadModuleManagerTasks
	{
		private $TaskManager = null;

		public function __construct(WhatsBotThread $TaskManager)
		{
			$this->TaskManager = $TaskManager;
		}

		# Exists

		public function KeyExists($Key)
		{ $this->TaskManager->AddTask(MODULEMANAGER_TASK, 'KeyExists', func_get_args()); }

		public function ModuleExists($Key, $Name, $ShowWarn = true)
		{ $this->TaskManager->AddTask(MODULEMANAGER_TASK, 'ModuleExists', func_get_args()); }

		# Getter

		public function GetKeys()
		{ $this->TaskManager->AddTask(MODULEMANAGER_TASK, 'GetKeys', func_get_args()); }

		public function GetModules($Key)
		{ $this->TaskManager->AddTask(MODULEMANAGER_TASK, 'GetModules', func_get_args()); }

		public function GetModule($Key, $Name, $ShowWarn = true)
		{ $this->TaskManager->AddTask(MODULEMANAGER_TASK, 'GetModule', func_get_args()); }

		# Loader

		public function LoadModules()
		{ $this->TaskManager->AddTask(MODULEMANAGER_TASK, 'LoadModules', func_get_args()); }

		public function LoadModule($Key, $Name, $AliasOf)
		{ $this->TaskManager->AddTask(MODULEMANAGER_TASK, 'LoadModule', func_get_args()); }

		public function UnloadModule($Key, $Name)
		{ $this->TaskManager->AddTask(MODULEMANAGER_TASK, 'UnloadModule', func_get_args()); }
	}