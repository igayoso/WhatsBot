<?php
	class Message
	{
		public static function IsUser($From, $User)
		{
			return $From === $User;
		}

		public static function IsGroup($From, $User)
		{
			return $From !== $User;
		}
	}