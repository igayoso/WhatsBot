<?php
	require_once 'WhatsApp.php';
	require_once 'ModuleManager.php';

	abstract class WhatsBotThread extends Thread
	{
		protected $WhatsApp = null;
		protected $ModuleManager = null;

		public function __construct(WhatsApp $WhatsApp, ModuleManager $ModuleManager)
		{
			$this->WhatsApp = $WhatsApp;
			$this->ModuleManager = $ModuleManager;
		}
	}