<?php

namespace losthost\OldNWise\service;
use losthost\DB\DBView;
use losthost\OldNWise\model\server;
use losthost\telle\model\DBUser;
use losthost\telle\model\DBPendingJob;
use losthost\OldNWise\service\BackSwitcher;
use Exception;

class Switcher {
    
    protected $servers = [];
    protected $user;


    public function __construct(DBUser &$user) {

        $this->user = $user;
        
        $servers = new DBView(<<<END
                SELECT 
                    s.id AS id
                FROM 
                    [user_server] AS us
                        INNER JOIN [server] AS s
                        ON us.server = s.id
                WHERE 
                    user = ?
                    AND s.is_disabled = 0
                END, [$user->id]);

        while ($servers->next()) {
            $this->servers[] = new server(['id' => $servers->id]);
        }   
        
    }
    
    public function switchServers() {
        foreach ($this->servers as $server) {
            $server->lock();
            try {
                if (!$server->switched_till) {
                    $this->switcher(
                            'root', 
                            $server->connection_host, 
                            $server->host_fingerprint, 
                            $server->connection_port, 
                            $server->primary_ip,
                            $server->secondary_ip,
                            false
                    ); 
                }
                $server->switched_till = time()+$server->access_seconds;
                error_log("$server->name SWITCHED BY ". $this->user->first_name);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
            
            $server->unlock();
        }

        $this->armBackSwitcher();
    }
    
    public function dropServers() {

        foreach ($this->servers as $server) {
            $server->lock();
            $server->switched_till = null;
            $server->is_disabled = true;
            $server->unlock();

            $this->switcher(
                    'root', 
                    $server->connection_host, 
                    $server->host_fingerprint, 
                    $server->connection_port, 
                    $server->secondary_ip,
                    $server->primary_ip,
                    true
            );
            error_log("$server->name DROPPED BY ". $this->user->first_name);
        }
    }
        
    static public function switcher($user, $host, $fingerprint, $nat_id, $replaceable_hp, $replacing_hp, $reset) {

        $nat_config = static::execute($user, $host, $fingerprint, "ipfw nat $nat_id show config");
        error_log("Original - $nat_config");

        $nat_config = preg_replace("/$replaceable_hp/", $replacing_hp, $nat_config);
        error_log("Replaced - $nat_config");

        if ($reset) {
            static::execute($user, $host, $fingerprint, "ipfw nat $nat_id config reset");
        }
        static::execute($user, $host, $fingerprint, $nat_config);
    }
    
    static protected function execute($user, $host, $fingerprint, $command ) {
        $ssh_dir = exec('cd ~'.  get_current_user().'; pwd').'/.ssh/';

        $connection = ssh2_connect($host, 22);
        if (!$connection) {
            throw new Exception("Can't connect to host $host");
        }
        $got_fingerprint = ssh2_fingerprint($connection);
        if ($got_fingerprint == $fingerprint) {
            $public_key_file = $ssh_dir.'id_rsa.pub';
            $private_key_file = $ssh_dir.'id_rsa';

            if (ssh2_auth_pubkey_file($connection, $user, $public_key_file, $private_key_file)) {
                // Authenticated
                $stream = ssh2_exec($connection, $command);
                stream_set_blocking($stream, TRUE);
                $data = stream_get_contents($stream);
                fclose($stream);
                return $data;
            } else {
                throw new Exception("Not authenticated. Public key: $public_key_file, Private key: $private_key_file\n");
            }
        } else {
            throw new Exception("Invalid host fingerprint. Got: $got_fingerprint Awaiting: $fingerprint\n");
        }
    }
    
    protected function armBackSwitcher() {
        $jobs = new DBView('SELECT id FROM [telle_pending_jobs] WHERE job_class = ? AND was_started IS NULL LIMIT 1', [BackSwitcher::class]);
        if (!$jobs->next()) {
            $a_second = date_interval_create_from_date_string('1 second');
            $backswitch_in_background = new \losthost\telle\model\DBBotParam('backswitch_in_background', false);
            $job = new DBPendingJob(date_create_immutable()->add($a_second), $backswitch_in_background, BackSwitcher::class);
            $job->job_args = $job->id;
            $job->write();
        }
    }
    
}
