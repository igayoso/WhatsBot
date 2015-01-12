<?php
	$Config = Utils::GetJson('config/IMGUR.json');

	if($Config !== false && isset($Config['endpoint']) && isset($Config['clientid']) && isset($Config['uploadlimit']) && isset($Config['sendongroup'])) // only if is pv
	{
		if($Config['sendongroup'] || !Utils::IsGroupJID($From))
		{
			if($Size <= $Config['uploadlimit'])
			{
				$Image = file_get_contents($URL);

				if($Image !== false)
				{
					$Options = array
					(
						'http' => array
						(
							'header' => "Content-type: application/x-www-form-urlencoded\nAuthorization: Client-ID {$Config['clientid']}",
							'method' => 'POST',
							'content' => http_build_query(array('image' => base64_encode($Image)))
						)
					);

					$Context = stream_context_create($Options);

					$Response = file_get_contents($Config['endpoint'], false, $Context);

					$Response = json_decode($Response, true);

					if($Response !== null && isset($Response['success']) && $Response['success'] == true && isset($Response['data']['link']))
						$Whatsapp->SendMessage($From, "Image uploaded to {$Response['data']['link']}");
				}
			}
		}
	}