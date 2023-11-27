<?php

namespace losthost\OldNWise\handlers;
use losthost\telle\abst\AbstractHandlerCallback;
use losthost\OldNWise\OldNWise;
use Exception;

abstract class CallbackCommonHandler extends AbstractHandlerCallback {

    protected function answerCallbackQuery(\TelegramBot\Api\Types\CallbackQuery &$callback_query): void {
        try {
            OldNWise::$api->answerCallbackQuery($callback_query->getId());
        } catch (Exception $exc) {
            error_log($exc->getMessage());
        }
    }
}
