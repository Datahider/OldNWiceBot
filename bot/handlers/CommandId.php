<?php

namespace losthost\OldNWise\handlers;

use losthost\telle\abst\AbstractHandlerMessage;
use losthost\OldNWise\OldNWise;

class CommandId extends AbstractHandlerMessage {

    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        return mb_strtolower($message->getText()) === '/id';
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        OldNWise::$api->sendMessage(OldNWise::$chat->id, OldNWise::$chat->id);
        return true;
    }
}
