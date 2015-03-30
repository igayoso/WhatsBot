<?php
	if(Admin::Is($User))
	{
		for($i = 1; $i <= 4; $i++)
			if(!isset($Params[$i]))
				$Params[$i] = null;

		if($Params[1] === 'list')
		{
			if($Params[2] === null)
			{
				$Admins = Admin::GetList();

				if(is_array($Admins))
				{
					$Message = $Lang('message:list::admin_list');
					$Pattern = $Lang('message:list::pattern');

					if($Message === false)
						return array(WARNING_LANG_ERROR, 'message:list::admin_list');
					if($Pattern === false)
						return array(WARNING_LANG_ERROR, 'message:list::pattern');

					$Count = count($Admins);
					for($i = 0; $i < $Count; $i++)
						$Message .= sprintf($Pattern, $i, $Admins[$i][0], $Admins[$i][1]);

					return $WhatsApp->SendRawMessage($From, $Message);
				}

				return INTERNAL_ERROR;
			}
			elseif($Params[2] === 'add')
			{
				if($Params[3] !== null)
				{
					$Nickname = Command::GetText($ModuleName, $Text, null, array($Params[1], $Params[2], $Params[3]));

					$Added = Admin::Add($Params[3], $Nickname);

					if($Added === true)
						return $WhatsApp->SendMessage($From, 'message:list:add:added', $Params[3]);
					elseif($Added === 1)
						return $WhatsApp->SendMessage($From, 'message:list:add:already_exists', $Params[3]);

					return INTERNAL_ERROR;
				}
			}
			elseif($Params[2] === 'delete')
			{
				if($Params[3] !== null)
				{
					$ID = intval($Params[3]);

					if((string)$ID === $Params[3])
					{
						if($ID !== 0)
						{
							$Deleted = Admin::Delete($ID);

							if(is_array($Deleted))
								return $WhatsApp->SendMessage($From, 'message:list:delete:deleted', $Deleted[0], $Deleted[1], $Deleted[2]);
							elseif($Deleted === 1)
								return $WhatsApp->SendMessage($From, 'message:list:delete:doesnt_exists', $ID);

							return INTERNAL_ERROR;
						}

						return $WhatsApp->SendMessage($From, 'message:list:delete:cant_delete_0');
					}
				}
			}
		}
		elseif($Params[1] === 'set')
		{
			if($Params[2] === 'status')
			{
				$Status = Command::GetText($ModuleName, $Text, false, array($Params[1], $Params[2]));

				if($Status !== false)
				{
					$WhatsApp->SetStatus($Status);
					
					return $WhatsApp->SendMessage($From, 'message:set:status:changed', $Status);
				}
			}
			elseif($Params[2] === 'picture')
			{
				$URL = Command::GetText($ModuleName, $Text, false, array($Params[1], $Params[2]));
						
				if($URL !== false)
				{
					$ParsedURL = parse_url($URL);
					$Extension = pathinfo($ParsedURL['path'], PATHINFO_EXTENSION);

					if($ParsedURL != false && $Extension != false)
					{
						try
						{
							$Image = Unirest\Request::get($URL);

							if($Image->code === 200)
							{
								$FileSize = strlen($Image->raw_body);

								if($FileSize < WhatsApp::MaxMediaSize)
								{
									$Filename = Random::Filename(strtolower($Extension));

									if(file_put_contents($Filename, $Image->raw_body) === $FileSize)
									{
										$WhatsApp->SetProfilePicture($Filename);

										unlink($Filename);

										return $WhatsApp->SendMessage($From, 'message:set:picture:setted'); // :changed ?
									}

									return INTERNAL_ERROR;
								}

								return $WhatsApp->SendMessage($From, 'message:set:picture:too_big');
							}

							Std::Out("[WARNING] [Command::Admin.Set.Picture] Reponse code {$Image->code}. Request to {$URL}");
								
							return $WhatsApp->SendMessage($From, 'message:set:picture:download_error');
						}
						catch(Exception $Exception)
						{
							Std::Out("[WARNING] [Command::Admin.Set.Picture] Exception: " . $Exception->getMessage());

							return $WhatsApp->SendMessage($From, 'message:set:picture:download_error');
						}
					}

					return $WhatsApp->SendMessage($From, 'message:set:picture:download_error');
				}
			}
		}
		elseif($Params[1] === 'uptime')
		{
			$Start = $WhatsBot->GetStartTime();

			$Date = gmdate('Y-m-d H:i:s', $Start);
			$Diff = GetTimeDiff($Start, time());

			if($Diff[0] > 0)
				return $WhatsApp->SendMessage($From, 'message:uptime:days', $Date, $Diff[0], $Diff[1], $Diff[2], $Diff[3]);

			if($Diff[1] > 0)
				return $WhatsApp->SendMessage($From, 'message:uptime:hours', $Date, $Diff[1], $Diff[2], $Diff[3]);

			if($Diff[2] > 0)
				return $WhatsApp->SendMessage($From, 'message:uptime:minutes', $Date, $Diff[2], $Diff[3]);

			return $WhatsApp->SendMessage($From, 'message:uptime:seconds', $Date, $Diff[3]);
		}

		return SEND_USAGE;
	}

	return NOT_ADMIN;