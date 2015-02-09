<?php
	class Thread_StdInputParser extends Thread
	{
		use WhatsBotThread;

		public function run()
		{
			require_once 'class/Utils.php'; // Al parecer, aunque falle, sólo detiene el thread y no todo el script C:

			Utils::WriteNewLine();
			Utils::Write('Starting WhatsBot standard input parser. ');
			Utils::Write('Interactive console enabled. Try to write something!');
			Utils::WriteNewLine();

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
							{
								$this->SendMessage($Splitted[1], $Text);
								Utils::Write('> Message sended...');
							}
							else
								Utils::Write('> You must enter some text...');
						}
						else
							Utils::Write('> You must enter some text...');
						break;
					case 'exit':
						Utils::Write('Stopping WhatsBot. A module-based, user-friendly whatsapp bot. https://github.com/fermino/WhatsBot. By @fermino...');
						Utils::ReadLine(); // Cambiar algo en WhatsBot que evite pollMessage();
						exit;
						break;
					default:
						Utils::Write('> Unrecognized command...');
				}
			}
		}
	}