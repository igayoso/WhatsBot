<?php
	/* To do. 
	 * If you have already the video size and hash, you can send it without re-uploading it to whatsapp servers
	 * $w->sendMessageVideo($target, $filepath, false, $fsize, $fhash, $caption);
	 * 
	 * Use TempFile
	 */

	$From = Utils::GetFrom($From);

	$Error = true;

	$Config = Utils::GetJson('config/soundcloud.json');

	if(!empty($Config['endpoint']) && !empty($Config['clientid']))
	{
		$RequestURL = "{$Config['endpoint']}resolve.json?client_id={$Config['clientid']}&url={$URL}";

		$Track = Utils::GetRemoteJson($RequestURL, 302);

		if($Track !== false && $Track['kind'] == 'track')
		{
			$RequestURL = "{$Config['endpoint']}i1/tracks/{$Track['id']}/streams?client_id={$Config['clientid']}";

			$Streams = Utils::GetRemoteJson($RequestURL, 200);

			if($Streams !== false)
			{
				if(isset($Streams['http_mp3_128_url']))
				{
					$Data = Utils::GetRemoteFile($Streams['http_mp3_128_url']);

					if($Data !== false && strlen($Data) > 0)
					{
						$Filename = tempnam('.', 'tmp') . '.mp3';

						if(file_put_contents($Filename, $Data))
						{
							$R = $Whatsapp->SendAudioMessage($From, $Filename);

							if($R)
								$Error = false;
						}

						if(is_file($Filename))
							unlink($Filename);
					}
				}
				elseif(isset($Streams['hls_mp3_128_url']))
				{
					$Playlist = Utils::GetRemoteFile($Streams['hls_mp3_128_url']);

					if($Playlist !== false)
					{
						$URLs = Utils::GetURLs($Playlist);

						if($URLs !== false)
						{
							$Data = '';

							foreach($URLs as $URL)
							{
								$D = Utils::GetRemoteFile($URL);

								if($D !== false)
									$Data .= $D;
								else
								{
									$Data = false;
									break;
								}
							}

							if(strlen($Data) > 0)
							{
								$Filename = tempnam('.', 'tmp') . '.mp3';

								if(file_put_contents($Filename, $Data))
								{
									$R = $Whatsapp->SendAudioMessage($From, $Filename);

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

	if($Error)
		$Whatsapp->SendMessage($From, 'Can\'t download track... Try with another song :)');