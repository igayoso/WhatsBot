<?php
	class Thread_TempCleaner extends WhatsBotThread
	{
		public function run()
		{
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