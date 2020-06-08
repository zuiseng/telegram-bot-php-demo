<?php
/**
 * @author      Akasha
 * @copyright   2020 (https://zuiseng.com)
 */

declare(strict_types=1);

namespace App;


/**
 * Class TelegramController
 * @package App
 */
final class TelegramController
{
    private $token;

    /**
     * TelegramController constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @param string $method
     * @param array $data
     * @return array
     */
    public function request(string $method, array $data = [])
    {
        $url = "https://api.telegram.org/bot$this->token/$method";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($ch);
        if(curl_error($ch)){
            var_dump(curl_error($ch));
            exit;
        }

        return json_decode($res);
    }

    /**
     * @param $data
     * @return array
     */
    public function getData($data){
        return json_decode(file_get_contents($data), TRUE);
    }

    /**
     * To send a text message
     * @param $type
     * @return array
     */
    public function sendMessage(array $type){
        return $this->request('sendMessage', $type);
    }

    /**
     * Status typing...
     * @param $type
     * @return array
     */
    public function sendChatAction(array $type){
        return $this->request('sendChatAction', $type);
    }

    /**
     * To send a picture
     * @param $type
     * @return array
     */
    public function sendPhoto(array $type){
        return $this->request('sendPhoto', $type);
    }

    /**
     * To send an audio file
     * @param $type
     * @return array
     */
    public function sendAudio(array $type){
        return $this->request('sendAudio', $type);
    }

    /**
     * To send an video file
     * @param $type
     * @return array
     */
    public function sendVideo(array $type){
        return $this->request('sendVideo', $type);
    }

    /**
     * To send an video file | format .ogg
     * @param $type
     * @return array
     */
    public function sendVoice(array $type){
        return $this->request('sendVoice', $type);
    }

    /**
     * Receive files sent to the bot
     * @param $type
     * @return array
     */
    public function getFile(array $type){
        return $this->request('getFile', $type);
    }

    /**
     * Forwarding messages
     * Xabarlarni forward qilish
     * @param $type
     * @return array
     */
    public function forwardMessage(array $type){
        return $this->request('forwardMessage', $type);
    }

    /**
     * Create an inline keyboard
     * Inline keyboard yaratish
     * @param $type
     * @return false|string
     */
    public function inlineKeyboard(array $type){
        return json_encode(['inline_keyboard' => $type]);
    }

    /**
     * Create inline keyboards
     * Inline keyboard yaratish
     * @param $type
     * @return false|string
     */
    public function inlineKeyboardMarkup(array $type){
      	$type = $type[0];
        return json_encode(['inline_keyboard' => $type]);
    }
    /**
     * Create a simple keyboard
     * Oddiy keyboard yaratish
     * @param array $type
     * @param bool $resize
     * @param bool $one_time
     * @return false|string
     */
    public function ReplyKeyboardMarkup(array $type, bool $resize = false, bool $one_time = false){
        return json_encode([
            'keyboard' => $type,
            'resize_keyboard' => $resize,
            'one_time_keyboard' => $one_time
        ]);
    }

    /**
     * Create a notification
     * Notification yaratish
     * @param $type
     * @return array
     */
    public function answerCallbackQuery(array $type){
        return $this->request('answerCallbackQuery', $type);
    }

    /**
     * Delete the message
     * Xabarni o'chirish
     * @param $type
     * @return array
     */
    public function deleteMessage(array $type){
        return $this->request('deleteMessage', $type);
    }

    /**
     * Membership check
     * A'zolikni tekshirish
     * @param $type
     * @return array
     */
    public function getChatMember(array $type){
        return $this->request('getChatMember', $type);
    }

    /**
     * Determine the number of people in the chat
     * Suhbatdoshlar sonini aniqlang
     * @param $type
     * @return array
     */
    public function getChatMembersCount(array $type){
        return $this->request('getChatMembersCount', $type);
    }

    /**
     * Edit the message
     * Xabarni tahrirlash
     * @param $type
     * @return array
     */
    public function editMessageText(array $type){
        return $this->request('editMessageText', $type);
    }

}