<?php
	require_once 'whatsapi/whatsprot.class.php';

	require_once 'WhatsApp/Functions.php';

	class WhatsApp
	{
		private $WhatsApp = null;

		public function __construct(WhatsProt $WhatsApp)
		{
			$this->WhatsApp = $WhatsApp;
		}

		# Config

		public function EventManager()
		{ return $this->WhatsApp->EventManager(); }

		# Connection

		public function Connect()
		{ return $this->WhatsApp->Connect(); }

		public function IsConnected()
		{ return $this->WhatsApp->IsConnected(); }

		public function Disconnect()
		{ return $this->WhatsApp->Disconnect(); }
		
		# Login

		public function LoginWithPassword($Password)
		{ return $this->WhatsApp->LoginWithPassword($Password); }

		# Listen

		public function PollMessage($AutoReceipt = true)
		{ return $this->WhatsApp->PollMessage($AutoReceipt); }

		# Messages

		public function SendMessage($To, $Message, $ID = null)
		{ return $this->WhatsApp->SendMessage($To, $Message, $ID); }

		# Others

		public function SendPing()
		{ return $this->WhatsApp->SendPing(); }

		/* Functions: 
		 * checkCredentials()
		 * codeRegister($code)
		 * codeRequest($method = 'sms', $countryCode = null, $langCode = null)
		 * eventManager()
		 * getMessages()
		 * login() // Don't use this one!
		 * loginWithPassword($password)
		 * sendActiveStatus()
		 * sendBroadcastAudio($targets, $path, $storeURLmedia = false, $fsize = 0, $fhash = "")
		 * sendBroadcastImage($targets, $path, $storeURLmedia = false, $fsize = 0, $fhash = "", $caption = "")
		 * sendGetBroadcastLists()
		 * sendBroadcastLocation($targets, $long, $lat, $name = null, $url = null)
		 * sendBroadcastMessage($targets, $message)
		 * sendBroadcastVideo($targets, $path, $storeURLmedia = false, $fsize = 0, $fhash = "", $caption = "")
		 * sendClearDirty($categories)
		 * sendGetClientConfig()
		 * sendGetGroupV2Info 
		 * sendGetGroupsInfo($gjid)
		 * sendGetGroupsOwning()
		 * sendGetGroupsParticipants($gjid)
		 * sendGetNormalizedJid($countryCode, $number)
		 * sendGetPrivacyBlockedList()
		 * sendGetProfilePicture($number, $large = false)
		 * sendGetRequestLastSeen($to)
		 * sendGetServerProperties()
		 * sendGetServicePricing($lg, $lc)
		 * sendGetStatuses($jids)
		 * sendGroupsChatCreate($subject, $participants = array())
		 * sendSetGroupSubject($gjid, $subject)
		 * sendGroupsChatEnd($gjid)
		 * sendGroupsLeave($gjids)
		 * sendGroupsParticipantsAdd($groupId, $participants)
		 * sendGroupsParticipantsRemove($groupId, $participants)
		 * sendMessageAudio($to, $filepath, $storeURLmedia = false, $fsize = 0, $fhash = "")
		 * sendMessageComposing($to)
		 * sendMessagePaused($to)
		 * sendMessageImage($to, $filepath, $storeURLmedia = false, $fsize = 0, $fhash = "", $caption = "")
		 * sendMessageVideo($to, $filepath, $storeURLmedia = false, $fsize = 0, $fhash = "", $caption = "")
		 * sendMessageLocation($to, $long, $lat, $name = null, $url = null)
		 * sendChatState($to, $state)
		 * sendNextMessage()
		 * sendOfflineStatus()
		 * sendPong($msgid)
		 * sendAvailableForChat($nickname = null)
		 * sendPresence($type = "active")
		 * sendPresenceSubscription($to)
		 * sendSetGroupPicture($gjid, $path)
		 * sendSetPrivacyBlockedList($blockedJids = array())
		 * sendSetProfilePicture($path)
		 * sendSetRecoveryToken($token)
		 * sendStatusUpdate($txt)
		 * sendVcard($to, $name, $vCard)
		 * sendBroadcastVcard($targets, $name, $vCard)
		 */

		// Send{type}Message => Send{type} only ?

		// Send Composing
	}