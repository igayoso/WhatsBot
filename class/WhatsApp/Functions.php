<?php
	function IsGroupMessage($From, $User)
	{
		return $From !== $User;
	}