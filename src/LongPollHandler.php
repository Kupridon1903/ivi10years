<?php

namespace MiniUpload;

require_once '/var/www/html/projects/ivi_10years/src/Config.php';
require_once '/var/www/html/projects/ivi_10years/src/DB.php';

use Smit\Livecover\VK;
use VK\CallbackApi\VKCallbackApiHandler;

class LongPollHandler extends VKCallbackApiHandler
{
    private $vk;
    private $db;

    public function __construct(VK $vk) {
        $this->vk = $vk;
        // Класс работы с бд
        $this->db = new DB($vk);
    }

    // При новом комментарии
    public function wallReplyNew(int $group_id, ?string $secret, array $object) {
        $post_id = $object['post_id'];
        $user_id = $object['from_id'];
        $comment = $object['text'];
        if ($post_id != Config::$POST_ID) return;
        if (strpos($comment, '#ivi10years') !== false) {
            $comment = str_replace('#ivi10years', '', $comment);
            if (mb_strlen($comment) < 400)
            $this->db->addComment($comment, $user_id);
        }
    }

    public function messageNew(int $group_id, ?string $secret, array $object) {
        $user_id = $object['from_id'];
        $message = $object['text'];
        $message = mb_strtolower($message);
        $words = ['хочу подарок', 'хачу падарок', 'хочу падарок', 'хачу подарок'];
        if(in_array($message, $words)) {
            $this->db->checkReg($user_id);
            $check = $this->db->checkGift($user_id);
            if ($check == 1){
                $this->vk->message($user_id,'Ссылка');
                $this->db->incGift($user_id);
            }
            else {
                $this->vk->message($user_id,'Ты уже получал подарок');
            }
        }
    }
}