<?php
	require_once 'WhatsApp.php';

	require_once 'Parser.php';

	class WhatsBotListener
	{
		private $WhatsApp = null;

		private $Parser = null;

		private $StartTime = null;

		public function __construct(WhatsApp $WhatsApp, WhatsBotParser $Parser)
		{
			$this->WhatsApp = $WhatsApp;
			$this->Parser = $Parser;

			$this->StartTime = time(); // Maybe a class/function
		}

		# Messages

		public function onGetMessage($Me, $From, $ID, $Type, $Time, $Name, $Text)
		{
			$this->Parser->ParseTextMessage($Me, $From, $From, $ID, $Type, $Time, $Name, $Text);
		}

		public function onGetGroupMessage($Me, $FromGroupJID, $FromUserJID, $ID, $Type, $Time, $Name, $Text)
		{
			$this->Parser->ParseTextMessage($Me, $FromGroupJID, $FromUserJID, $ID, $Type, $Time, $Name, $Text);
		}

		public function onGetImage($Me, $From, $ID, $Type, $Time, $Name, $Size, $URL, $File, $MIME, $Hash, $Width, $Height, $Preview, $Caption)
		{
			$this->onGetGroupImage($Me, $From, $From, $ID, $Type, $Time, $Name, $Size, $URL, $File, $MIME, $Hash, $Width, $Height, $Preview, $Caption);
		}

		public function onGetGroupImage($Me, $FromGroupJID, $FromUserJID, $ID, $Type, $Time, $Name, $Size, $URL, $File, $MIME, $Hash, $Width, $Height, $Preview, $Caption)
		{
			// Download data

			$this->Parser->ParseMediaMessage($Me, $FromGroupJID, $FromUserJID, $ID, 'image', $Time, $Name, array
			(
				'URL' => $URL,
				'File' => $File,
				'Size' => $Size,
				'MIME' => $MIME,
				'Hash' => $Hash,
				'Width' => $Width,
				'Height' => $Height,
				'Preview' => $Preview,
				'Caption' => $Caption
			));
		}

		public function onGetVideo($Me, $From, $ID, $Type, $Time, $Name, $URL, $File, $Size, $MIME, $Hash, $Duration, $VCodec, $ACodec, $Preview, $Caption)
		{
			$this->onGetGroupVideo($Me, $From, $From, $ID, $Type, $Time, $Name, $URL, $File, $Size, $MIME, $Hash, $Duration, $VCodec, $ACodec, $Preview, $Caption)
		}

		public function onGetGroupVideo($Me, $FromGroupJID, $FromUserJID, $ID, $Type, $Time, $Name, $URL, $File, $Size, $MIME, $Hash, $Duration, $VCodec, $ACodec, $Preview, $Caption)
		{
			// Download data

			$this->Parser->ParseMediaMessage($Me, $FromGroupJID, $FromUserJID, $ID, 'video', $Time, $Name, array
			(
				'URL' => $URL,
				'File' => $File,
				'Size' => $Size,
				'MIME' => $MIME,
				'Hash' => $Hash,
				'Duration' => $Duration,
				'VCodec' => $VCodec,
				'ACodec' => $ACodec,
				'Preview' => $Preview,
				'Caption' => $Caption
			));
		}

		// https://github.com/mgp25/WhatsAPI-Official/issues/496 Wait for fix and add more events

		/* Events: 
		 * onClose($mynumber, $error)
		 * onCodeRegister( $mynumber, $login, $password, $type, $expiration, $kind, $price, $cost, $currency, $price_expiration )
		 * onCodeRegisterFailed( $mynumber, $status, $reason, $retry_after )
		 * onCodeRequest( $mynumber, $method, $length )
		 * onCodeRequestFailed( $mynumber, $method, $reason, $param )
		 * onCodeRequestFailedTooRecent( $mynumber, $method, $reason, $retry_after )
		 * onConnect( $mynumber, $socket ) 
		 * onConnectError( $mynumber, $socket )
		 * onCredentialsBad( $mynumber, $status, $reason )
		 * onCredentialsGood( $mynumber, $login, $password, $type, $expiration, $kind, $price, $cost, $currency, $price_expiration )
		 * onDisconnect( $mynumber, $socket ) 
		 * onDissectPhone( $mynumber, $phonecountry, $phonecc, $phone, $phonemcc, $phoneISO3166, $phoneISO639, $phonemnc ) 
		 * onDissectPhoneFailed( $mynumber )
		 * onGetAudio( $mynumber, $from, $id, $type, $time, $name, $size, $url, $file, $mimeType, $fileHash, $duration, $acodec )
		 * onGetBroadcastLists( $mynumber, $broadcastLists )
		 * onGetError( $mynumber, $from, $id, $data )
		 * onGetExtendAccount( $mynumber, $kind, $status, $creation, $expiration )
		 * onGetGroupParticipants( $mynumber, $groupId, $groupList )
		 * onGetGroups( $mynumber, $groupList )
		 * onGetGroupsInfo( $mynumber, $groupList )
		 * onGetGroupsSubject( $mynumber, $group_jid, $time, $author, $name, $subject )
		 * onGetLocation( $mynumber, $from, $id, $type, $time, $name, $nameLocation, $longitude, $latitude, $url, $preview )
		 * onGetNormalizedJid( $mynumber, $data )
		 * onGetPrivacyBlockedList( $mynumber, $data )
		 * onGetProfilePicture( $mynumber, $from, $type, $data )
		 * onGetReceipt( $from, $id, $offline, $retry )
		 * onGetRequestLastSeen( $mynumber, $from, $id, $seconds )
		 * onGetServerProperties( $mynumber, $version, $props )
		 * onGetServicePricing( $mynumber, $price, $cost, $currency, $expiration )
		 * onGetStatus( $mynumber, $from, $requested, $id, $time, $data )
		 * onGetSyncResult( $result )
		 * onGetvCard( $mynumber, $from, $id, $type, $time, $name, $vcardname, $vcard )
		 * onGroupCreate( $mynumber, $groupId )
		 * onGroupisCreated( $mynumber, $creator, $gid, $subject, $admin, $creation, $members = array()){}
		 * onGroupsChatCreate( $mynumber, $gid )
		 * onGroupsChatEnd( $mynumber, $gid )
		 * onGroupsParticipantsAdd( $mynumber, $groupId, $jid )
		 * onGroupsParticipantsRemove( $mynumber, $groupId, $jid )
		 * onLogin( $mynumber )
		 * onLoginFailed( $mynumber, $data )
		 * onLoginSuccess( $mynumber, $kind, $status, $creation, $expiration )
		 * onMediaMessageSent( $mynumber, $to, $id, $filetype, $url, $filename, $filesize, $filehash, $caption, $icon )
		 * onMediaUploadFailed( $mynumber, $id, $node, $messageNode, $statusMessage )
		 * onMessageComposing( $mynumber, $from, $id, $type, $time )
		 * onMessagePaused( $mynumber, $from, $id, $type, $time )
		 * onMessageReceivedClient( $mynumber, $from, $id, $type, $time )
		 * onMessageReceivedServer( $mynumber, $from, $id, $type, $time )
		 * onPaidAccount( $mynumber, $author, $kind, $status, $creation, $expiration )
		 * onPaymentRecieved( $mynumber, $kind, $status, $creation, $expiration )
		 * onPing( $mynumber, $id )
		 * onPresence( $mynumber, $from, $status )
		 * onProfilePictureChanged( $mynumber, $from, $id, $time )
		 * onProfilePictureDeleted( $mynumber, $from, $id, $time )
		 * onSendMessage( $mynumber, $target, $messageId, $node )
		 * onSendMessageReceived( $mynumber, $id, $from, $type )
		 * onSendPong( $mynumber, $msgid )
		 * onSendPresence( $mynumber, $type, $name )
		 * onSendStatusUpdate( $mynumber, $txt )
		 * onStreamError( $data )
		 * onUploadFile( $mynumber, $filename, $url )
		 * onUploadFileFailed( $mynumber, $filename )
		 */

		/* To do: Hacer otra clase (Listener), que se encargue de loguear todo a la BD. Bindear ambas clases al EventManager 
		 * Esto creo que evitaría algunos problemas con los threads
		 */
	}