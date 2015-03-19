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
			default:
				return SEND_USAGE;
		}
	}
	else
		return NOT_ADMIN;