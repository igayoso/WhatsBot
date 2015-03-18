<?php
	require_once 'whatsapi/whatsprot.class.php';

	require_once 'WhatsApp/Functions.php';
	require_once 'Lang.php';

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

		public function SendAudio($To, $Path, $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ return $this->WhatsApp->SendMessageAudio($To, $Path, $StoreURLMedia, $Size, $Hash); }

		public function SendImage($To, $Path, $Caption = '', $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ return $this->WhatsApp->SendMessageImage($To, $Path, $StoreURLMedia, $Size, $Hash, $Caption); }

		public function SendVideo($To, $Path, $Caption = '', $StoreURLMedia = false, $Size = 0, $Hash = '')
		{ return $this->WhatsApp->SendMessageVideo($To, $Path, $StoreURLMedia, $Size, $Hash, $Caption); }

		# Others

		public function SendPing()
		{ return $this->WhatsApp->SendPing(); }

		/* Functions: 
		 * checkCredentials()
		 * codeRegister($code)
		 * codeRequest($method = 'sms', $countryCode = null, $langCode = null)
		 * getMessages()
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
		 * sendMessageComposing($to)
		 * sendMessagePaused($to)
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

		private $LangSection = null;

		public function SetLangSection($Section)
		{ $this->LangSection = $Section; }

		public function SendMessage($To, $Key, $Pre = null)
		{
			$Args = func_get_args();
			array_shift($Args);

			$Message = call_user_func_array(array(new Lang($this->LangSection), 'Get'), $Args);

			if($Message !== false)
				return $this->SendRawMessage($To, $Pre . $Message);
			else
			{
				if($Key === 'message:internal_error')
					return $this->SendRawMessage($To, 'Internal error...');
				elseif($Key === 'message::module_not_loaded')
					return $this->SendRawMessage($To, 'That module doesn\'t exists. Try !help to see a list of available modules');
				else
					return $this->SendLangError($To, $Key);
			}
		}

		public function SendLangError($To, $Key)
		{
			return $this->SendRawMessage($To, "Lang error. Key not found: {$this->LangSection}::{$Key}");
		}

		public function SendRawMessage($To, $Message)
		{ // Validate from
		  // Send composing
			return $this->WhatsApp->SendMessage($To, $Message);
		}
	}