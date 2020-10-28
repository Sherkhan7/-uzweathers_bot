<?php
require "menu/menu.php";

//require "config.php";

use Telegram\Bot\Api;

$telegram = new Api('1038081989:AAHfMUWCUQnqXZX5e-JCztdHM-F0tprPYhA', true);
//$conn_array = ['localhost', 'u83155_botdb', 'u83155_root', 'hpnotebook'];
//$conn = connect('localhost', 'u83155_botdb', 'u83155_root', 'hpnotebook');
//file_put_contents(__DIR__ . '/connection.txt' , json_encode($conn, JSON_PRETTY_PRINT));

$update = $telegram->getWebhookUpdates();
file_put_contents(__DIR__ . '/update.json', json_encode($update, JSON_PRETTY_PRINT));

$chat_id = $update->getMessage()->getChat()->getId();
$message_id = $update->getMessage()->getMessageId();
$is_bot = $update->getMessage()->getFrom()->get('is_bot');
$first_name = $update->getMessage()->getChat()->getFirstName();
$last_name = $update->getMessage()->getChat()->getLastName();
$username = $update->getMessage()->getChat()->getUsername();
$text = $update->getMessage()->getText();
$date = date('Y-m-d H:i:s', $update->getMessage()->getDate());

$vars_arr = [
    'chat_id',
    'message_id',
    'is_bot',
    'first_name',
    'last_name',
    'username',
    'text',
    'date',
];

$vars = compact($vars_arr);
file_put_contents(__DIR__ . '/vars.json', json_encode($vars, JSON_PRETTY_PRINT));

//$user = get_user($conn, $chat_id);

//if (!empty($user)) {
//file_put_contents(__DIR__ . '/userfromdb.txt', json_encode($user, JSON_PRETTY_PRINT));

//    $user_text_result = add_user_textlog($conn, ['chat_id' => $chat_id, 'text' => $text]);

//    $telegram->sendMessage([
//        'chat_id' => $chat_id,
//        'text' => $user_text_result,
//    ]);
//}
// else {
//
//    $user_info = ['chat_id', 'is_bot', 'first_name', 'last_name', 'username'];
//    $user_info = compact($user_info);
//    $new_user_result = add_user($conn, $user_info);
//    $user_text_result = add_user_textlog($conn, ['chat_id' => $chat_id, 'text' => $text]);
//
//    $telegram->sendMessage([
//        'chat_id' => $chat_id,
//        'text' => $user_text_result,
//    ]);
//}

if ($text == '/news') {

    $reply_markup = $telegram->replyKeyboardMarkup([
        'keyboard' => $menu_1,
        'resize_keyboard' => true,
    ]);

    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => 'Yangiliklarni tanlang',
//    'reply_to_message_id' =>  $message_id,
        'reply_markup' => $reply_markup,
    ]);
}

if ($text == "Siyosat" || $text == "Dunyoda" || $text == "Iqtisod" || $text == "Sport") {

    $xml = simplexml_load_file('https://oz.sputniknews-uz.com/export/rss2/archive/index.xml');
    $xml_channel = $xml->channel;
//    file_put_contents(__DIR__ . '/news.json', json_encode($xml_channel, JSON_PRETTY_PRINT));
    $items = [];

    foreach ($xml_channel->item as $item) {
        array_push($items, $item);
    }

    file_put_contents(__DIR__ . '/items.json', json_encode($items, JSON_PRETTY_PRINT));
    $siyosat = [];
    $dunyoda = [];
    $sport = [];
    $iqtisod = [];

    foreach ($items as $item) {
        if ($item->category == 'Dunyoda') {
            array_push($dunyoda, $item);
        }
        if ($item->category == 'Siyosat') {
            array_push($siyosat, $item);
        }
        if ($item->category == 'Sport') {
            array_push($sport, $item);
        }
        if ($item->category == 'Iqtisod') {
            array_push($iqtisod, $item);
        }
    }

    function call_news($text)
    {
        global $siyosat, $dunyoda, $sport, $iqtisod, $telegram, $chat_id;

        if ($text == "Dunyoda") $news_arr = $dunyoda;
        if ($text == "Siyosat") $news_arr = $siyosat;
        if ($text == "Iqtisod") $news_arr = $iqtisod;
        if ($text == "Sport") $news_arr = $sport;


        foreach ($news_arr as $new) {
            $reply = "$text: \n\n";
            $reply .= "\xE2\x9E\xA1 $new->title\nData: $new->pubDate (<a href='$new->link'>Xabarni to'liq o'qish</a>)\n\n";
            $telegram->sendMessage(['chat_id' => $chat_id, 'parse_mode' => 'HTML',
                'disable_web_page_preview' => false, 'text' => $reply]);
        }

        file_put_contents(__DIR__ . '/reply.json', json_encode($reply, JSON_PRETTY_PRINT));
        file_put_contents(__DIR__ . "/$text.json", json_encode($news_arr, JSON_PRETTY_PRINT));

    }

    call_news($text);

}

//elseif ($text === 'Button 2') {
//    $reply_markup = $telegram->replyKeyboardMarkup([
//        'keyboard' => $menu_2,
//        'resize_keyboard' => true,
//    ]);
//
//    $telegram->sendMessage([
//        'chat_id' => $chat_id,
//        'text' => $reply,
////    'reply_to_message_id' =>  $message_id,
//        'reply_markup' => $reply_markup,
//    ]);
//}
//else {
//    $reply_markup = $telegram->replyKeyboardMarkup([
//        'keyboard' => $menu,
//        'resize_keyboard' => true,
//    ]);
//
//    $telegram->sendMessage([
//        'chat_id' => $chat_id,
//        'text' => $reply,
////    'reply_to_message_id' =>  $message_id,
//        'reply_markup' => $reply_markup,
//    ]);
//}

//
//const  TOKEN = '1038081989:AAHfMUWCUQnqXZX5e-JCztdHM-F0tprPYhA';
//const BASE_URL = 'https://api.telegram.org/bot' . TOKEN . '/';
//
//$update = file_get_contents("php://input");
//
//$update = json_decode(file_get_contents("php://input"), true);
//
//function sendRequest($method, $params = []) {
//    $url = BASE_URL . $method . '?' . http_build_query($params);
//
//    return json_decode(file_get_contents($url), true);
//}
//
//$chat_id = $update['message']['chat']['id'];
//$text = $update['message']['text'];
////
//$params = [
//    'chat_id' => $chat_id,
//    'text' => $text
//];
//
//sendRequest('sendmessage', $params);