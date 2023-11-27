<?php

namespace losthost\OldNWise\handlers;
use losthost\OldNWise\OldNWise;
use losthost\telle\abst\AbstractHandlerMessage;


class MessageNotHandledHandle extends AbstractHandlerMessage {
    
    protected function check(\TelegramBot\Api\Types\Message &$message): bool {
        return true;
    }

    protected function handle(\TelegramBot\Api\Types\Message &$message): bool {
        OldNWise::showInitialMessage();
        return true;
    }
}
