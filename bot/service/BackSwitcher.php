<?php

namespace losthost\OldNWise\service;
use losthost\telle\abst\AbstractBackgroundProcess;
use losthost\OldNWise\model\server;
use losthost\OldNWise\service\Switcher;
use losthost\DB\DBView;
use losthost\DB\DBValue;
use losthost\telle\model\DBPendingJob;
use losthost\DB\DB;


class BackSwitcher extends AbstractBackgroundProcess {
    
    public function run() {
        
        if (!$this->lock_async()) {
            sleep(2);
            if (!$this->lock_async()) {
                $this->setResult('Skipped.');
                return;
            }
        }
        
        $this->setResult('Running...');
        while($this->iteration()) {
            sleep(1);
        }
        
        $this->unlock();
        $this->setResult('Finished.');
    }
    
    protected function iteration() {
        
        $server_ids = new DBView("SELECT id FROM [server] WHERE switched_till < ?", [date_create_immutable()->getTimestamp()]);
        
        while ($server_ids->next()) {
            $server = new server(['id' => $server_ids->id]);
            $server->lock();
            try {
                Switcher::switcher('root', $server->connection_host, $server->host_fingerprint, $server->connection_port, $server->secondary_ip, $server->primary_ip, false);
                $server->switched_till = null;
            } catch (Exception $exc) {
                error_log($e->getMessage());
            }

            $server->unlock();
        }

        $count = new DBValue("SELECT COUNT(*) AS value FROM [server] WHERE switched_till IS NOT NULL");
        if ($count->value > 0) {
            return true;
        }
        return false;
    }
    
    protected function setResult(string $result) {
        $job = new DBPendingJob((int)$this->param);
        $job->result = $result;
        $job->write();
    }
    
    public function lock_async() {
        
        $sth = DB::prepare('UPDATE [telle_bot_params] SET value = "LOCKED" WHERE name = "bs_lock" AND value = ""');
        $sth->execute();
        
        if ($sth->rowCount() == 1) {
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
        throw new Exception("Cannot lock BackSwitcher.", 10);
    }
    
    public function unlock() {
        $sth = DB::prepare('UPDATE [telle_bot_params] SET value = "" WHERE name = "bs_lock"');
        $sth->execute();
    }
    
}
