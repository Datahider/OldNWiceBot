<?php

namespace losthost\OldNWise\handlers;
use losthost\OldNWise\OldNWise;
use losthost\OldNWise\handlers\CallbackCommonHandler;

class CallbackMoreHandler extends CallbackCommonHandler {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        return $callback_query->getData() === 'more';
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        OldNWise::showDictum($callback_query->getMessage()->getMessageId());
        $this->answerCallbackQuery($callback_query);
        return true;
    }
}
