<?php

namespace losthost\OldNWise\handlers;
use losthost\OldNWise\OldNWise;
use losthost\OldNWise\handlers\CallbackCommonHandler;
use losthost\OldNWise\model\dictum_author;
use losthost\BotView\BotView;

class CallbackAuthorHandler extends CallbackCommonHandler {
    
    protected function check(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        return mb_substr($callback_query->getData(), 0, 7) === 'author_';
    }

    protected function handle(\TelegramBot\Api\Types\CallbackQuery &$callback_query): bool {
        $author_id = mb_substr($callback_query->getData(), 7);
        
        $author = new dictum_author(['id' => $author_id]);
        $view = new BotView(OldNWise::$api, OldNWise::$chat->id);
        $view->show('author', 'author-keyboard', ['author_name' => $author->name, 'author_description' => $author->description], $callback_query->getMessage()->getMessageId());
        
        $this->answerCallbackQuery($callback_query);
        return true;
    }
}
