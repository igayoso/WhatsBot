<?php
	require_once 'ModuleManagerCore.php';

	require_once 'ModuleManagerCaller.php';

	class ModuleManager extends ModuleManagerCore
	{
		use ModuleManagerCaller;
	}