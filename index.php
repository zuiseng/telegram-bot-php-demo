<?php
/**
 * @author      akasha
 * @copyright   2020 (https://zuiseng.com)
 */
require 'App/TelegramController.php';
require 'App/function.php';
use App\TelegramController;
date_default_timezone_set('Asia/Shanghai');
$config = (require __DIR__ . '/config.php');
$bot = new TelegramController($config['token']);
$data = $bot->getData("php://input");
$chat_id = $data['message']['chat']['id'];
$user_id = $data['message']['from']['id'];
$username = $data['message']['from']['username'];
$ism = $data['message']['from']['first_name'] . ' ' . $data['message']['from']['last_name'];
$text = $data['message']['text'];
$message_id = $data['message']['message_id'];
/** CallbackQuery */
$callback = $data['callback_query'];
$callback_id = $callback['id'];
$call_data = $callback['data'];
$call_chat_id = $callback['message']['chat']['id'];
$call_message_id = $callback['message']['message_id'];
/** Audio */
$audio = $data['message']['audio'];
/** Photo */
$photo = $data['message']['photo'];
/** Video */
$video = $data['message']['video'];
$key_edit = $bot->InlineKeyboard([[['text' => 'How are you?', 'callback_data' => 'how']]]);
$keyboard = $bot->ReplyKeyboardMarkup([['Hello world!']], true);
if ($text == "/start") {
    $bot->sendMessage(['chat_id' => $chat_id, 'text' => "\n挂不敢算尽,畏天道无常.情不敢至深,恐大梦一场！\n  \n  \n 请输入  /help 查看支持的命令 ", 'parse_mode' => 'HTML']);
}
if ($text == "/help") {
    $bot->sendMessage(['chat_id' => $chat_id, 'text' => "\n /aword 酒后吐真言   \n /so 带你环游世界", 'parse_mode' => 'HTML']);
}
if ($text == "/dmm_status") {
    $all = (int) Get_sum();
    $new = (int) Get_today();
    $content = "dmm爬虫状态如下: \n 共有 " . $all . "部大妹妹的作品 \n 今天新增了" . $new . "条";
    $bot->sendMessage(['chat_id' => $chat_id, 'text' => $content, 'parse_mode' => 'HTML']);
}
if ($text == "/aword") {
    $content = file_get_contents("https://v1.hitokoto.cn/?encode=text");
    $bot->sendMessage(['chat_id' => $chat_id, 'text' => $content, 'parse_mode' => 'HTML']);
}
if (substr($text, 0, 3) == "/so") {
    $content = trim(str_replace('/so', "", $text));
    if (strlen($content) > 1) {
        $content_list = v_list($content);
        if (count($content_list) == count($content_list, 1)) {
            $num = 1;
        } else {
            $num = count($content_list);
        }
        if ($num > 0) {
            $content = "关于《" . $content . "》找到" . $num . "条内容";
            if ($num == 1) {
                $list_temp['text'] = $content_list['name'] . ' ' . $content_list['note'];
                $list_temp['callback_data'] = 'movie_' . $content_list['id'];
                $movies_list[] = $list_temp;
                $key = $bot->InlineKeyboard([$movies_list]);
                $bot->sendMessage(['chat_id' => $chat_id, 'text' => $content, 'parse_mode' => 'HTML', 'reply_markup' => $key]);
            } else {
                $movies_list = $list_temp = [];
                for($x=0; $x<$num;$x++){
                    $list_temp['text'] = $content_list[$x]['name'] . ' ' . $content_list[$x]['note'];
                    $list_temp['callback_data'] = 'movie_' . trim($content_list[$x]['id']);
                    $movies_list[$x][] = $list_temp;
                }
                $key = $bot->InlineKeyboardMarkup([$movies_list]);
                $bot->sendMessage(['chat_id' => $chat_id, 'text' => $content, 'parse_mode' => 'HTML', 'reply_markup' => $key]);
            }
        } else {
            $content = "未找到关于《" . $content . "》的信息";
            $bot->sendMessage(['chat_id' => $chat_id, 'text' => $content, 'parse_mode' => 'HTML']);
        }
    } else {
        $content = "请输入要搜的名字 \n 例如 /so 传说中的";
        $bot->sendMessage(['chat_id' => $chat_id, 'text' => $content, 'parse_mode' => 'HTML']);
    }
}

if (substr($call_data, 0, 6) == "movie_") {
    $movie_id = trim(str_replace('movie_', "", $call_data));
    if (strlen($movie_id) > 1) {
        $movies_list = v_movie($movie_id);
        $content = "《" . $movies_list['name'] . "》\n类型：" . $movies_list['type'] . "\n演员：" . $movies_list['actor'] . "\n导演：" . $movies_list['director'] . "\n描述：" . $movies_list['des'] . "\n播放地址\n";
        $url_list = $movies_list['dl']['dd'][0];
        $url_list = explode("#", $url_list);
        $play_url = '';
        foreach ($url_list as $one) {
            $temp_list = explode("$", $one);
            $play_url .= '<a href="' . $temp_list[1] . '">' . $temp_list[0] . "(__点击播放__)</a>\n";
        }
        $content .= $play_url;
        
        if(!$movies_list['name']){
          $content = '抱歉，解析失败';
        }
        $bot->editMessageText(['chat_id' => $call_chat_id, 'message_id' => $call_message_id, 'text' => $content, 'parse_mode' => 'HTML', 'reply_markup' => $key]);
        $bot->answerCallbackQuery(['callback_query_id' => $callback_id, 'text' => "点击下面链接播放即可"]);
    } else {
        $content = "请输入要搜的名字 \n 例如 /so 传说中的";
        $bot->sendMessage(['chat_id' => $chat_id, 'text' => $content, 'parse_mode' => 'HTML']);
    }
}