<?php

namespace losthost\OldNWise\model;
use losthost\DB\DBObject;


class user_server extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'user' => 'BIGINT(20) NOT NULL',
        'server' => 'BIGINT(20) NOT NULL',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX USER_SERVER' => ['user', 'server']
    ];
    
}
