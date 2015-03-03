<?php
	/* WhatsBot Module
	 * 
	 * Author: @fermino
	 * 
	 * Type: command
	 * Name: echo
	 * 
	 * Usage: !echo <text>
	 */

	$WhatsApp->SendMessage($From, Command::GetText($ModuleName, $Text, $Lang('write_something')));