<?php

namespace losthost\OldNWise\service;
use losthost\telle\abst\AbstractBackgroundProcess;
use losthost\OldNWise\model\server;
use losthost\OldNWise\service\Switcher;
use losthost\DB\DBView;
use losthost\DB\DBValue;


class BackSwitcher extends AbstractBackgroundProcess {
    
    public function run() {
        
        while($this->iteration()) {
            sleep(1);
        }
    }
    
    protected function iteration() {
        
        $server_ids = new DBView("SELECT id FROM [server] WHERE switched_till < ?", [date_create_immutable()->getTimestamp()]);
        
        while ($server_ids->next()) {
            $server = new server(['id' => $server_ids->id]);
            $server->lock();
            Switcher::switcher($server->user, $server->connection_host, $server->host_fingerprint, $server->connection_port, $server->secondary_ip, $server->primary_ip, false);
            $server->switched_till = null;
            $server->unlock();
        }

        $count = new DBValue("SELECT COUNT(*) AS value FROM [server] WHERE switched_till IS NOT NULL");
        if ($count->value > 0) {
            return true;
        }
        return false;
    }
}
