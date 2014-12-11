<?php
	class Thread_StdInputParser extends WhatsBotThread
	{
		public function run()
		{
			require_once 'class/Utils.php';

			while(true)
			{
				$Line = Utils::ReadLine();
				$Splitted = explode(' ', $Line);

				switch(strtolower($Splitted[0]))
				{
					case 'clean':
						break;
					case 'send':
						if(!empty($Splitted[1]) && !empty($Splitted[2]))
							$this->SendMessage($Splitted[1], $Splitted[2]);
						break;
					default:
						Utils::Write('Unrecognized command...');
				}
			}
		}
	}