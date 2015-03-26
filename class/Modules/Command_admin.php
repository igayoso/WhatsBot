<?php
	if(Admin::Is($User))
	{
		for($i = 1; $i <= 4; $i++)
			if(!isset($Params[$i]))
				$Params[$i] = null;

		switch($Params[1])
		{
			case 'list':
				switch($Params[2])
				{
					case null:
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
						else
							return INTERNAL_ERROR;
						break;
					case 'add':
						if($Params[3] !== null)
						{
							$Nickname = Command::GetText($ModuleName, $Text, null, array($Params[1], $Params[2], $Params[3]));

							$Added = Admin::Add($Params[3], $Nickname);

							if($Added === true)
								return $WhatsApp->SendMessage($From, 'message:list:add:added', $Params[3]);
							elseif($Added === 1)
								return $WhatsApp->SendMessage($From, 'message:list:add:already_exists', $Params[3]);
							else
								return INTERNAL_ERROR;
						}
						else
							return SEND_USAGE;
						break;
					case 'delete':
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
									else
										return INTERNAL_ERROR;
								}
								else
									return $WhatsApp->SendMessage($From, 'message:list:delete:cant_delete_0');
							}
							else
								return SEND_USAGE;
						}
						else
							return SEND_USAGE;
						break;
					default:
						return SEND_USAGE;
				}
				break;
			case 'set':
				switch($Params[2])
				{
					case 'status':
						$Status = Command::GetText($ModuleName, $Text, false, array($Params[1], $Params[2]));

						if($Status !== false)
						{
							$WhatsApp->SetStatus($Status);
							
							return $WhatsApp->SendMessage($From, 'message:set:status:changed', $Status);
						}
						else
							return SEND_USAGE;
						break;
					case 'picture':
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
											else
												return INTERNAL_ERROR;
										}
										else
											return $WhatsApp->SendMessage($From, 'message:set:picture:too_big');
									}
									else
									{
										Std::Out("[WARNING] [Command::Admin.Set.Picture] Reponse code {$Image->code}. Request to {$URL}");
										
										return $WhatsApp->SendMessage($From, 'message:set:picture:download_error');
									}
								}
								catch(Exception $Exception)
								{
									Std::Out("[WARNING] [Command::Admin.Set.Picture] Exception: " . $Exception->getMessage());

									return $WhatsApp->SendMessage($From, 'message:set:picture:download_error');
								}
							}
							else
								return $WhatsApp->SendMessage($From, 'message:set:picture:download_error');
						}
						else
							return SEND_USAGE;
						break;
					default:
						return SEND_USAGE;
				}
				break;
			default:
				return SEND_USAGE;
		}
	}
	else
		return NOT_ADMIN;