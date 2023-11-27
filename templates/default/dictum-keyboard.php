<?php

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

echo serialize(new InlineKeyboardMarkup([
    [['text' => 'Автор', 'callback_data' => "author_$author_id"], ['text' => 'Ещё »', 'callback_data' => "more"]]
]));