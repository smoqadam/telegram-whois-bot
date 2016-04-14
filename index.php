<?php

$message = file_get_contents('php://input');

require 'vendor/autoload.php';


$api_token = 'TOKEN';

$tg = new Smoqadam\Telegram($api_token);
$whois = new Smoqadam\Whois();

$tg->cmd('\/help', function ($domain) use ($tg, $whois) {
    $help = <<<HELP
Domain tools bot
available commands :
/help show this message
/whois [domain] show domain information
/check [domain] check if a domain available for register or not
HELP;


    $tg->sendMessage($help, $tg->getChatId());
});
/**
 * get the domain information
 */
$tg->cmd('\/whois <<:any>>', function ($domain) use ($tg, $whois) {

    if (!strlen($domain)) {

        $tg->sendMessage("/whois [domain name]  show domain information", $tg->getChatId());
        return;
    }

    $result = $whois->getDomainInfo($domain);

    $tg->sendMessage($result, $tg->getChatId());


});


//check availability
$tg->cmd('\/check <<:any>>', function ($domain) use ($tg, $whois) {

    if (!strlen($domain)) {
        $tg->sendMessage("/check [domain name] check if a domain available for register or not", $tg->getChatId());
        return;
    }

    $result = $whois->isAvailable($domain);

    if (!$result) {
        $tg->sendMessage($domain . ' is not availble', $tg->getChatId());
    } else {
        $tg->sendMessage($result . ' is available', $tg->getChatId());
    }

});

$tg->process(json_decode($message, true));