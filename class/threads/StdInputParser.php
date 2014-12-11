<?php
	class Thread_StdInputParser extends Thread
	{
		use WhatsBotThread;

		public function run()
		{
			require_once 'class/Utils.php';

			while(true)
			{
				Utils::Write('>> ', false);

				$Line = Utils::ReadLine();
				$Splitted = explode(' ', $Line);

				if(empty($Line))
					continue;

				switch(strtolower($Splitted[0]))
				{
					case 'send':
						if(!empty($Splitted[1]))
						{
							$Text = substr($Line, 5 + strlen($Splitted[1]));

							if($Text !== false)
								$this->SendMessage($Splitted[1], $Text);
							else
								Utils::Write('> You must enter some text...');
						}
						else
							Utils::Write('> You must enter some text...');
						break;
					default:
						Utils::Write('> Unrecognized command...');
				}
			}
		}
	}