<?php
	// Change module name to somthing like extall_image.php (Error: .image module :P)
	$MaxFilesize = 16777216;

	$From = Utils::GetFrom($From);

	$Filesize = Utils::GetRemoteFilesize($URL);

	if($Filesize < $MaxFilesize)
	{
		$Image = Utils::GetRemoteFile($URL);

		if($Image !== false)
		{
			$Length = strlen($Image);

			if($Filesize === $Length && $Length < $MaxFilesize)
			{
				$Filename = tempnam('tmp/', 'img_') . ".{$ModuleName}"; // $ModuleName == $Extension

				if(file_put_contents($Filename, $Image) == $Length)
					$this->Whatsapp->SendImageMessage($From, $Filename, $URL);

				Utils::CleanTemp();
			}
			else
				$Whatsapp->SendMessage($From, 'The image is too big...');
		}
		else
			return false;
	}
	else
		$Whatsapp->SendMessage($From, 'The image is too big...');