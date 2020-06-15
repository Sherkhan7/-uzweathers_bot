<?php

//ini_set('error_reporting', E_ALL);
require "../vendor/autoload.php";

use Telegram\Bot\Api;

$telegram = new Api('1038081989:AAHfMUWCUQnqXZX5e-JCztdHM-F0tprPYhA');

//$y = $telegram->setWebhook([
//    'url' => 'https://caf339380bf2.ngrok.io'
//]);

$x = $telegram->getWebhookUpdates();
//$z = $telegram->removeWebhook();

//$t = $telegram->getUpdates();

function dd($x)
{
    dump($x);
    die;
}

dump($x);