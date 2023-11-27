<?php

namespace losthost\OldNWise\handlers;
use losthost\telle\abst\AbstractHandlerMessage;
use losthost\OldNWise\OldNWise;


class CommandDictumHandler extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        return mb_substr(mb_strtolower($message->getText()), 0, 7) === '/dictum';
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        OldNWise::showDictum();
        return true;
    }
}
