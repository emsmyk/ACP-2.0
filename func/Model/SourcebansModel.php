<?php
class SourcebansModel
{
  function __construct()
  {
    $this->sb_host = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'api_sb_host' LIMIT 1");
    $this->sb_db = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'api_sb_db' LIMIT 1");
    $this->sb_user = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'api_sb_user' LIMIT 1");
    $this->sb_pass = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'api_sb_pass' LIMIT 1");

    $this->db = new DB($this->sb_host, $this->sb_user, $this->sb_pass, $this->sb_db);
  }

  function admins($prf)
  {
    return $this->db->get_results("SELECT `aid`, `user`, `authid`, `immunity`, `srv_group`, `srv_flags` FROM `".$prf."_admins` WHERE `authid` != '' AND `aid` != '' AND `user` != 'CONSOLE' ORDER BY FIELD(`srv_group`, 'Opiekun', 'Starszy Admin', 'Admin', 'Legenda', '')", true);
  }

  function admin_exist($prf, $aid)
  {
    $admin = $this->db->get_row("SELECT `user`, `authid` FROM `".$prf."_admins` WHERE `aid` = $aid LIMIT 1")[0];

    if(empty($Server)){
      return false;
    }

    return $admin;
  }

  /*
    $prf = prefix serwera
    $where = dane jakie mają zostać weliminowane
    $order = order
  */
  function admins_list($prf, $where='', $order='')
  {
    return $this->db->get_results("SELECT `aid`, `user`, `authid`, `srv_group` FROM `".$prf."_admins` $where  $order", true);
  }

  function groups($prf)
  {
    return $this->db->get_results("SELECT * FROM `".$prf."_srvgroups` ORDER BY `immunity` ASC", true);
  }

  function servers($prf)
  {
    return $this->db->get_results("SELECT * FROM `".$prf."_servers`", true);
  }

  function server_exist($prf, $ip, $port)
  {
    $Server = $this->db->get_row("SELECT `sid` FROM `".$prf."_servers` WHERE `ip` = ".$ip." AND `port` = ".$port." LIMIT 1")[0];

    if(empty($Server)){
      return false;
    }

    return true;
  }

  function last_admin($prf)
  {
    return $this->db->get_results("SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '".$prf."_admins'", true);
  }

  function raport_opiekuna($prf)
  {
    return [
      'liczba_ban' => $this->db->get_row("SELECT COUNT(`bid`) FROM `".$prf."_bans`")[0],
      'liczba_mute' => $this->db->get_row("SELECT COUNT(`bid`) FROM `".$prf."_comms` WHERE `type` = 1")[0],
      'liczba_gag' => $this->db->get_row("SELECT COUNT(`bid`) FROM `".$prf."_comms` WHERE `type` = 2")[0],
      'liczba_unban' => $this->db->get_row("SELECT COUNT(`bid`) FROM `".$prf."_bans` WHERE `RemovedBy` IS NOT NULL AND `RemovedOn` IS NOT NULL")[0],
      'liczba_unmute' => $this->db->get_row("SELECT COUNT(`bid`) FROM `".$prf."_comms` WHERE `type` = 2 AND `RemovedBy` IS NOT NULL AND `RemovedOn` IS NOT NULL")[0],
      'liczba_ungag' => $this->db->get_row("SELECT COUNT(`bid`) FROM `".$prf."_comms` WHERE `type` = 2 AND `RemovedBy` IS NOT NULL AND `RemovedOn` IS NOT NULL")[0],

    ];
  }

  function admin_store($prf, $dostep)
  {
    Permission::check($dostep);

    $from = From::check(
      [
        'nick' => 'reg',
        'steam' => 'reg'
      ],
      [
        'nick.reg' => 'Pole NicK jest wymagane.',
        'steam.reg' => 'Pole Steam jest wymagane.'
      ]
    );

    $from->steam = $Steam->toSteamID($from->steam);
    $from->ranga_explode = explode(":", $from->ranga);

    $from->serwer_dane = Model('Server')->more($serwer);

    if(!$this->server_exist($prf, $from->serwer_dane->ip, $from->serwer_dane->port)) {
      return Messe::array([
        'type' => 'info',
        'text' => "Wskazany serwer nie istnieje w systemie Sourcebans"
      ]);
    }

    $this->db->insert($prf."_admins", [
      'user' => $from->ranga_explode[1]." - ".$from->nick,
      'authid' => $from->steam,
      'password' => '',
      'gid' => '-1',
      'email' => '',
      'extraflags' => '0',
      'srv_group' => $in->dane_ranga_tekst,
      'immunity' => '0',
    ]);

    $this->db->insert($prf."_admins_servers_groups", [
      'admin_id' => $this->db->lastid(),
      'group_id' => $from->ranga_explode[0],
      'srv_group_id' => '-1',
      'server_id' => $sb->serwer_id
    ]);

    Logs::log("Dodano poprawnie $from->nick (STEAM: $from->steam) na rangę ".$from->ranga_explode[1]);

    if($from->changelog == 'on'){
      Logs::server("$from->nick (STEAM: $from->steam) został ".$from->ranga_explode[1]." na serwerze", $from->serwer_id);
    }
  }
  function admin_update($prf, $dostep)
  {
    Permission::check($dostep);

    $from = From::check(
      [
        'nick' => 'reg',
        'steam' => 'reg'
      ],
      [
        'nick.reg' => 'Pole NicK jest wymagane.',
        'steam.reg' => 'Pole Steam jest wymagane.'
      ]
    );

    $from->steam = $Steam->toSteamID($from->steam);
    $from->ranga_explode = explode(":", $from->ranga);

    $from->serwer_dane = Model('Server')->more($serwer);

    if(!$this->server_exist($prf, $from->serwer_dane->ip, $from->serwer_dane->port)) {
      return Messe::array([
        'type' => 'info',
        'text' => "Wskazany serwer nie istnieje w systemie Sourcebans"
      ]);
    }

    if($from->ranga_explode[0] == 0){
      $this->db->update($prf."_admins",
      [
        'user' => $from->nick,
        'authid' => $from->steam
      ],
      [
        'aid' => $from->aid
      ],
      1);
    }
    else {
      $this->db->update($prf."_admins",
        [
          'user' => $from->nick,
          'authid' => $from->steam,
          'srv_group' => $from->ranga_explode[1]
        ],
        [
          'aid' => $from->aid
        ],
        1
      );

      $this->db->update($prf."_admins_servers_groups",
        [
          'group_id' => $from->ranga_explode[0],
        ],
        [
          'id' => $from->aid
        ],
        1
      );
    }

    Logs::log("Zedytowano poprawnie ".$from->ranga_explode[1]." (STEAM: $from->steam)");
  }

  function admin_degradacja($prf, $dostep)
  {
    Permission::check($dostep);

    $from = From::check();
    $from->serwer_dane = Model('Server')->more($serwer);

    if(!$this->server_exist($prf, $from->serwer_dane->ip, $from->serwer_dane->port)) {
      return Messe::array([
        'type' => 'info',
        'text' => "Wskazany serwer nie istnieje w systemie Sourcebans"
      ]);
    }

    $admin = $this->admin_exist($prf, $from->aid);
    $admin->user_ex = explode($admin->user, ' - ');

    $this->db->update($prf."_admins",
    [
      'user' => 'Degradacja - '.$admin->user_ex['1'],
      'srv_group' => ''
    ],
    [
      'aid' => $from->aid
    ],
    1);
    $this->db->delete($prf."_admins_servers_groups", [ 'id' => $from->aid ], 1);

    Logs::log("Zdegradowano $admin->user (STEAM: $admin->authid)");
  }

  function admin_rezygnacja($prf, $dostep)
  {
    Permission::check($dostep);

    $from = From::check();
    $from->serwer_dane = Model('Server')->more($serwer);

    if(!$this->server_exist($prf, $from->serwer_dane->ip, $from->serwer_dane->port)) {
      return Messe::array([
        'type' => 'info',
        'text' => "Wskazany serwer nie istnieje w systemie Sourcebans"
      ]);
    }

    $admin = $this->admin_exist($prf, $from->aid);
    $admin->user_ex = explode($admin->user, ' - ');

    $this->db->update($prf."_admins",
    [
      'user' => 'Rezygnacja - '.$admin->user_ex['1'],
      'srv_group' => ''
    ],
    [
      'aid' => $from->aid
    ],
    1);
    $this->db->delete($prf."_admins_servers_groups", [ 'id' => $from->aid ], 1);

    Logs::log("Oznaczono Rezygnację $admin->user (STEAM: $admin->authid)");
  }

  function admin_destry($prf, $dostep)
  {
    Permission::check($dostep);

    $from = From::check();
    $from->serwer_dane = Model('Server')->more($serwer);

    if(!$this->server_exist($prf, $from->serwer_dane->ip, $from->serwer_dane->port)) {
      return Messe::array([
        'type' => 'info',
        'text' => "Wskazany serwer nie istnieje w systemie Sourcebans"
      ]);
    }

    $admin = $this->admin_exist($prf, $from->aid);

    $this->db->delete($prf."_admins", [ 'aid' => $from->aid ], 1);
    $this->db->delete($prf."_admins_servers_groups", [ 'id' => $from->aid ], 1);

    Logs::log("Usunięto poprawnie $admin->user (STEAM: $admin->authid)");
  }

  function optymalize_all_tables()
  {
    $dane = $this->db->get_results("SHOW TABLES", true);
    foreach ($dane as $tablename) {
      $this->db->query("OPTIMIZE TABLE '".$tablename."'");
    }

    return;
  }

}
?>
