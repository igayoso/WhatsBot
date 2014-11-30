<?php
	/* To do. 
	 * If you have already the video size and hash, you can send it without re-uploading it to whatsapp servers
	 * $w->sendMessageVideo($target, $filepath, false, $fsize, $fhash, $caption);
	 * 
	 * Use GetRemoteFile
	 */

	$From = Utils::GetFrom($From);

	$Error = true;

	$Config = Utils::GetJson('config/soundcloud.json');

	if($Config !== false && isset($Config['endpoint']) && isset($Config['clientid']))
	{
		$RequestURL = "{$Config['endpoint']}resolve.json?client_id={$Config['clientid']}&url={$URL}";

		$Headers = get_headers($RequestURL, 1); // @?

		if($Headers !== false && isset($Headers['Location']) && substr($Headers[0], 9, 3) == '302')
		{
			$Track = file_get_contents($RequestURL); // if false then return false
			$Track = json_decode($Track, true);

			if($Track !== false && isset($Track['kind']) && $Track['kind'] == 'track' && isset($Track['id']) && is_int($Track['id']))
			{
				$RequestURL = "{$Config['endpoint']}i1/tracks/{$Track['id']}/streams?client_id={$Config['clientid']}";

				$Headers = get_headers($RequestURL, 1); // @?

				if($Headers !== false && substr($Headers[0], 9, 3) == '200')
				{
					$Streams = file_get_contents($RequestURL);
					$Streams = json_decode($Streams, true);

					if($Streams !== false)
					{
						if(isset($Streams['http_mp3_128_url']))
						{
							$Data = file_get_contents($Streams['http_mp3_128_url']);

							if($Data !== false && strlen($Data) > 0)
							{
								$Filename = tempnam('.', 'tmp') . '.mp3';

								if(file_put_contents($Filename, $Data))
								{
									$R = $Whatsapp->SendMessageAudio($From, $Filename);

									if($R)
										$Error = false;
								}

								if(is_file($Filename))
									unlink($Filename);
							}
						}
						elseif(isset($Streams['hls_mp3_128_url']))
						{
							$Playlist = file_get_contents($Streams['hls_mp3_128_url']);

							if($Playlist !== false)
							{
								$URLs = Utils::GetURLs($Playlist);

								if($URLs !== false)
								{
									$Continue = true;
									$Data = '';

									foreach($URLs as $URL)
									{
										$D = file_get_contents($URL);

										if($D !== false)
											$Data .= $D;
										else
										{
											$Continue = false;
											break;
										}
									}

									if($Continue && strlen($Data) > 0)
									{
										$Filename = tempnam('.', 'tmp') . '.mp3';

										if(file_put_contents($Filename, $Data))
										{
											$R = $Whatsapp->SendMessageAudio($From, $Filename);

											if($R)
												$Error = false;
										}

										if(is_file($Filename))
											unlink($Filename);
									}
								}
							}
						}
					}
				}
			}
		}
	}

	if($Error)
		$Whatsapp->SendMessage($From, 'Can\'t download track... Try with another song :)');