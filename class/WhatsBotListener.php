<?php
	class WhatsBotListener
	{
		private $Whatsapp = null;
		private $Password = null;

		private $Parser = null;

		private $StartTime = null;

		public function __construct(WhatsProt &$Whatsapp, $Password, WhatsBotParser &$Parser)
		{
			$this->Whatsapp = &$Whatsapp;
			$this->Password = $Password;

			$this->Parser = &$Parser;

			// Maybe bind $this to eventManager here

			$this->StartTime = time();
		}

		public function onGetMessage($Me, $From, $ID, $Type, $Time, $Name, $Text)
		{
			if($Time > $this->StartTime)
				$this->Parser->ParseTextMessage($Me, null, $From, $ID, $Type, $Time, $Name, $Text);
		}

		public function onGetGroupMessage($Me, $FromG, $FromU, $ID, $Type, $Time, $Name, $Text)
		{
			if($Time > $this->StartTime)
				$this->Parser->ParseTextMessage($Me, $FromG, $FromU, $ID, $Type, $Time, $Name, $Text);
		}

		public function onGetReceipt($From, $ID, $Offline, $Retry)
		{
			$this->Whatsapp->sendPong($ID);
		}

		/* Events: 
			onClose($mynumber, $error)
		    onCodeRegister( $mynumber, $login, $password, $type, $expiration, $kind, $price, $cost, $currency, $price_expiration )
		    onCodeRegisterFailed( $mynumber, $status, $reason, $retry_after )
		    onCodeRequest( $mynumber, $method, $length )
		    onCodeRequestFailed( $mynumber, $method, $reason, $param )
		    onCodeRequestFailedTooRecent( $mynumber, $method, $reason, $retry_after )
		    onConnect( $mynumber, $socket ) 
		    onConnectError( $mynumber, $socket )
		    onCredentialsBad( $mynumber, $status, $reason )
		    onCredentialsGood( $mynumber, $login, $password, $type, $expiration, $kind, $price, $cost, $currency, $price_expiration )
		    onDisconnect( $mynumber, $socket ) 
		    onDissectPhone( $mynumber, $phonecountry, $phonecc, $phone, $phonemcc, $phoneISO3166, $phoneISO639, $phonemnc ) 
		    onDissectPhoneFailed( $mynumber )
		    onGetAudio( $mynumber, $from, $id, $type, $time, $name, $size, $url, $file, $mimeType, $fileHash, $duration, $acodec )
		    onGetBroadcastLists( $mynumber, $broadcastLists )
		    onGetError( $mynumber, $id, $data )
		    onGetExtendAccount( $mynumber, $kind, $status, $creation, $expiration )
		    onGetGroupMessage( $mynumber, $from_group_jid, $from_user_jid, $id, $type, $time, $name, $body )
		    onGetGroupParticipants( $mynumber, $groupId, $groupList )
		    onGetGroups( $mynumber, $groupList )
		    onGetGroupsInfo( $mynumber, $groupList )
		    onGetGroupsSubject( $mynumber, $group_jid, $time, $author, $name, $subject )
		    onGetImage( $mynumber, $from, $id, $type, $time, $name, $size, $url, $file, $mimeType, $fileHash, $width, $height, $preview, $caption )
		    onGetLocation( $mynumber, $from, $id, $type, $time, $name, $name, $longitude, $latitude, $url, $preview )
		    onGetMessage( $mynumber, $from, $id, $type, $time, $name, $body )
		    onGetNormalizedJid( $mynumber, $data )
		    onGetPrivacyBlockedList( $mynumber, $data )
		    onGetProfilePicture( $mynumber, $from, $type, $data )
		    onGetReceipt( $from, $id, $offline, $retry )
		    onGetRequestLastSeen( $mynumber, $from, $id, $seconds )
		    onGetServerProperties( $mynumber, $version, $props )
		    onGetServicePricing( $mynumber, $price, $cost, $currency, $expiration )
		    onGetStatus( $mynumber, $from, $requested, $id, $time, $data )
		    onGetSyncResult( $result )
		    onGetVideo( $mynumber, $from, $id, $type, $time, $name, $url, $file, $size, $mimeType, $fileHash, $duration, $vcodec, $acodec, $preview, $caption )
		    onGetvCard( $mynumber, $from, $id, $type, $time, $name, $vcardname, $vcard )
		    onGroupCreate( $mynumber, $groupId )
		    onGroupisCreated( $mynumber, $creator, $gid, $subject )
		    onGroupsChatCreate( $mynumber, $gid )
		    onGroupsChatEnd( $mynumber, $gid )
		    onGroupsParticipantsAdd( $mynumber, $groupId, $jid )
		    onGroupsParticipantsRemove( $mynumber, $groupId, $jid)
		    onLogin( $mynumber )
		    onLoginFailed( $mynumber, $data )
		    onMediaMessageSent( $mynumber, $to, $id, $filetype, $url, $filename, $filesize, $filehash, $caption, $icon )
		    onMediaUploadFailed( $mynumber, $id, $node, $messageNode, $statusMessage )
		    onMessageComposing( $mynumber, $from, $id, $type, $time )
		    onMessagePaused( $mynumber, $from, $id, $type, $time )
		    onMessageReceivedClient( $mynumber, $from, $id, $type, $time )
		    onMessageReceivedServer( $mynumber, $from, $id, $type, $time )
		    onPaidAccount( $mynumber, $author, $kind, $status, $creation, $expiration )
		    onPing( $mynumber, $id )
		    onPresence( $mynumber, $from, $status )
		    onProfilePictureChanged( $mynumber, $from, $id, $time )
		    onProfilePictureDeleted( $mynumber, $from, $id, $time )
		    onSendMessage( $mynumber, $target, $messageId, $node )
		    onSendMessageReceived( $mynumber, $id, $from, $type )
		    onSendPong( $mynumber, $msgid )
		    onSendPresence( $mynumber, $type, $name )
		    onSendStatusUpdate( $mynumber, $txt )
		    onStreamError( $data )
		    onUploadFile( $mynumber, $filename, $url )
		    onUploadFileFailed( $mynumber, $filename )
    	*/
	}