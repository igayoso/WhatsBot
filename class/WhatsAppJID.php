<?php
	trait WhatsAppJID
	{
		public static function IsUserMessage($From, $User)
		{
			return $From === $User;
		}

		public static function IsGroupMessage($From, $User)
		{
			return $From !== $User;
		}
	}