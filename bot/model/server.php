<?php

namespace losthost\OldNWise\model;
use losthost\DB\DBObject;
use losthost\DB\DB;
use Exception;


class server extends DBObject {
    
    const METADATA = [
        'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
        'name' => 'VARCHAR(50) NOT NULL',
        'primary_ip' => 'VARCHAR(15) NOT NULL',
        'secondary_ip' => 'VARCHAR(15) NOT NULL',
        'redirect_port' => 'INT(11) NOT NULL',
        'connection_port' => 'INT(11) NOT NULL',
        'connection_host' => 'VARCHAR(128) NOT NULL',
        'host_fingerprint' => 'VARCHAR(256) NOT NULL',
        'access_seconds' => 'INT(11) NOT NULL',
        'is_disabled' => 'TINYINT(1) NOT NULL',
        'is_locked' => 'TINYINT(1) NOT NULL',
        'switched_till' => 'BIGINT(20)',
        'PRIMARY KEY' => 'id',
        'UNIQUE INDEX NAME' => 'name',
    ];
    
    protected function beforeInsert($comment, $data) {
        parent::beforeInsert($comment, $data);
        if ($this->__data['is_disabled'] === null) {
            $this->__data['is_disabled'] = 0;
        }
    }
    
    public function lock_async() {
        
        $sth = DB::prepare('UPDATE '. static::tableName(). ' SET is_locked = 1 WHERE id = ? AND is_locked = 0');
        $sth->execute([$this->id]);
        
        if ($sth->rowCount() == 1) {
            $this->fetch();
            return true;
        }
        return false;
    }
    
    public function lock(int $timeout=900) { // 15 min default timeout
        foreach (range(1, $timeout*10) as $iteration) {
            if ($this->lock_async()) {
                return;
            }
            usleep(100000); // ждем 0.1 сек
        }
        throw new Exception("Cannot lock server.", 10);
    }
    
    public function unlock() {
        $this->is_locked = false;
        $this->write();
    }
}
