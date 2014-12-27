<?php

//Translate Text Using Bing API

$From  = Utils::GetFrom($From);
$fText = Utils::GetText($ModuleName, $Text);
$uParse	= explode(" " , $fText);
$uLang	= $uParse[0];
unset ($uParse[0]);
$text = implode(" ", $uParse);

//Load Config
$Config = Utils::GetJson('config/BingTranslate.json');

//If Configuration Is Done
if(!empty($Config['secret']) && !empty($Config['clientid']))
{
	$BingTranslator = new BingTranslator($Config['clientid'],$Config['secret']);
	$translation = $BingTranslator->getTranslation('en',$uLang,$text);
        $Whatsapp->SendMessage($From,$translation);

}

else
	echo "\nConfig Is Missing";



