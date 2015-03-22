<?php
	$MaxFileSize = 16777216;

	try
	{
		$Image = Unirest\Request::get($URL);

		if($Image->code === 200)
		{
			$FileSize = strlen($Image->raw_body);

			if($FileSize < $MaxFileSize)
			{
				$Filename = Random::Filename(strtolower($Extension));

				if(file_put_contents($Filename, $Image->raw_body) === $FileSize)
				{
					$Response = $WhatsApp->SendImage($From, $Filename);

					unlink($Filename);

					return $Response;
				}
				else
					return INTERNAL_ERROR;
			}
			else
				return $WhatsApp->SendMessage($From, 'message:too_big');
		}
		else
			Std::Out("[WARNING] [Extension::{$Extension}] Reponse code {$Image->code}. Request to {$URL}");

		return INTERNAL_ERROR;
	}
	catch(Exception $Exception)
	{
		Std::Out("[WARNING] [Extension::{$Extension}] Exception: " . $Exception->getMessage());

		return INTERNAL_ERROR;
	}