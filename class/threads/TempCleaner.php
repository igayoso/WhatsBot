<?php
	class Thread_TempCleaner extends Thread
	{
		use WhatsBotThread;
		
		public function run()
		{
			require_once 'class/Utils.php';

			while(true)
			{
				sleep(300); // In json?

				Utils::Write('Cleaning temp directory...');
				Utils::CleanTemp(); // Test if true
				Utils::Write('Temp directory cleaned...');
				Utils::WriteNewLine();
			}
		}
	}