<?php
	require_once 'DBCore.php';

	class WhatsBotDB extends DBCore
	{
		public function InsertMessage($Me, $From, $ID, $Type, $Time, $Name, $Text) // Agregar si se parseó o no el mensaje (Time)
		{
			$R = $this->DB->prepare('INSERT INTO `messages` VALUES (null, :bot, :from, :mid, :type, :time, :name, :text);');
			
			$R->bindParam(':bot', $Me);
			$R->bindParam(':from', $From);
			$R->bindParam(':mid', $ID);
			$R->bindParam(':type', $Type);
			$R->bindParam(':time', $Time);
			$R->bindParam(':name', $Name);
			$R->bindParam(':text', $Text);

			return $R->execute();
		}

		public function InsertGroupMessage($Me, $GroupID, $From, $ID, $Type, $Time, $Name, $Text) // Idem
		{
			$R = $this->DB->prepare('INSERT INTO `group_messages` VALUES (null, :bot, :gid, :from, :mid, :type, :time, :name, :text);');

			$R->bindParam(':bot', $Me);
			$R->bindParam(':gid', $GroupID);
			$R->bindParam(':from', $From);
			$R->bindParam(':mid', $ID);
			$R->bindParam(':type', $Type);
			$R->bindParam(':time', $Time);
			$R->bindParam(':name', $Name);
			$R->bindParam(':text', $Text);

			return $R->execute(); // Pasar todos los parametros como array?
		}
	}