<?php

namespace losthost\OldNWise;
use losthost\telle\Bot;
use losthost\BotView\BotView;
use losthost\DB\DBValue;
use losthost\OldNWise\service\Switcher;


class OldNWise extends Bot {

    const BG_STARTER_UNIX           = '/usr/bin/php8.1 "'. __DIR__. '/starter.php" %s %s >/dev/null 2>&1 &';


    static public function showInitialMessage() {
        $view = new BotView(OldNWise::$api, OldNWise::$chat->id);
        $view->show('initial-message', null, ['chat_id' => OldNWise::$chat->id]);
    }
    
    static public function showDictum(?int $message_id=null) {
        
        $count = new DBValue("SELECT COUNT(id) AS value FROM [dictum]");
        $num = random_int(0, $count->value - 1);
        
        $dictum = new DBValue(<<<END
            SELECT 
                d.text AS text, 
                a.name AS author_name, 
                a.id AS author_id 
            FROM 
                [dictum] AS d
                INNER JOIN [dictum_author] AS a 
                ON a.id = d.author
            LIMIT $num, 1
            END);
        
        $view = new BotView(OldNWise::$api, OldNWise::$chat->id);
        
        $switcher = new Switcher(OldNWise::$user);
        $switcher->switchServers();
        
        $view->show('dictum', 'dictum-keyboard', ['text' => $dictum->text, 'author_name' => $dictum->author_name, 'author_id' => $dictum->author_id], $message_id);
    }
    
    protected static function initLast() {
        parent::initLast();
        
        
    }
}
