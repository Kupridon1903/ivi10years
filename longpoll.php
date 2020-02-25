<?php
require_once '../../livecover/vendor/autoload.php';
require_once '/var/www/html/projects/ivi_10years/src/Config.php';
require_once '/var/www/html/projects/ivi_10years/src/LongPollHandler.php';

use MiniUpload\Config;
use MiniUpload\DB;
use MiniUpload\LongPollHandler;
use Smit\Livecover\VK;
use VK\CallbackApi\LongPoll\VKCallbackApiLongPollExecutor;
use VK\Client\Enums\VKLanguage;
use VK\Client\VKApiClient;

$vkApi = new VKApiClient('5.95', VKLanguage::RUSSIAN);

try {
    $response = $vkApi->groups()->getLongPollServer(Config::$ACCESS_TOKEN, [
        'group_id' => Config::$GROUP_ID,
    ]);
    $ts = $response['ts'];
}
catch (\VK\Exceptions\VKClientException $e) {
    print ($e->getMessage());
}

$vk = new VK(Config::$GROUP_ID, Config::$ACCESS_TOKEN);
$db = new DB($vk);
$handler = new LongPollHandler($vk);
$executor = new VKCallbackApiLongPollExecutor($vkApi, Config::$ACCESS_TOKEN, Config::$GROUP_ID, $handler, 25);

while (true) {
    $ts = $executor->listen($ts);
}





