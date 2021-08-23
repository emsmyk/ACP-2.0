<?php
class Servers
{
    public $servers = NULL;

    public function __construct()
    {
      $this->servers = SQL::all('SELECT `serwer_id`, `istotnosc`, `game`, `ip`, `port`, `nazwa`, `mod`, `status`, `status_data`, `serwer_on`, `cronjobs` FROM `acp_serwery`');
    }

    function ftp()
    {
       foreach ($this->servers as $server)
       {
           $server->ftp = SQL::row('Select `ftp_user`, `ftp_haslo`, `ftp_host` From `acp_serwery` where `serwer_id`= '.$server->serwer_id.' Limit 1');
       }
       return $this->servers;
    }

    function cronjobs()
    {
       foreach ($this->servers as $server)
       {
           $server->cronjobs = SQL::row('SELECT * FROM `acp_serwery_cronjobs` WHERE `serwer` = '.$server->serwer_id.' Limit 1');
       }
       return $this->servers;
    }

    function stats()
    {
      foreach ($this->servers as $server)
      {
        $server->stats = SQL::row('Select `graczy`, `max_graczy`, `boty` From `acp_serwery` where `serwer_id`= '.$server->serwer_id.' Limit 1');
      }
      return $this->servers;
    }

    function servers_list($chose='-')
    {
      $serwer_array = array(0 => 'Wszystkie');

      foreach($this->servers as $server){
        $serwer_array[$server->serwer_id]="$server->nazwa";
      }

      return $serwer_array;
    }

    function destroyOldLogs($limit, $day){
      $kas = SQL::all("SELECT `get` FROM `acp_cache_api` WHERE `modification_data` < NOW() - INTERVAL $day DAY LIMIT $limit");

      foreach($kas as $row){
        SQL::query("DELETE FROM `acp_cache_api` WHERE `get` = '$row->get' LIMIT 1");

        if(!next($kas)){
          SQL::query("OPTIMIZE TABLE `acp_cache_api`");
        }
      }
    }
}

$servers = new Servers;
?>
