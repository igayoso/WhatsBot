Add module name to config/Modules.json => ['modules']['commands']


Add cmd_MODULE_NAME.json to modules/

Json: 
```json
	{
		"name":"MODULE NAME",
		"help":"MODULE HELP",
		"version":MODULE VERSION (INT)
	}
```

Add cmd_MODULE_NAME.php to modules/

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