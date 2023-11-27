<?php

namespace losthost\OldNWise\handlers;
use losthost\OldNWise\OldNWise;
use losthost\telle\abst\AbstractHandlerMessage;


class CommandStartHandler extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        return mb_substr(mb_strtolower($message->getText()), 0, 6) === '/start';
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        OldNWise::showInitialMessage();
        return true;
    }
}
