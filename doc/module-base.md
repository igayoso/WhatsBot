Json: 
```json
	{
		"name":"MODULE NAME",
		"help":"MODULE HELP",
		"version":MODULE VERSION (INT)
	}
```

PHP: 
```php
	<?php
		$From = Utils::GetFrom($From);
		$Text = Utils::GetText($Text);

		if($Text !== false)
		{
			// Continue
		}
		else
		{
			// Send 'You must write something' ;)
			$Whatsapp->SendMessage($From, 'You must write something...');
		}
```