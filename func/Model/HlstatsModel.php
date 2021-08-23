<?php
class HlstatsModel
{
  function __construct()
  {
    $this->hlx_host = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'api_hlx_host' LIMIT 1");
    $this->hlx_db = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'api_hlx_db' LIMIT 1");
    $this->hlx_user = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'api_hlx_user' LIMIT 1");
    $this->hlx_pass = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'api_hlx_pass' LIMIT 1");

    $this->db = new DB($this->hlx_host, $this->hlx_user, $this->hlx_pass, $this->hlx_db);
  }

  function czas_polaczenia($srv, $steam)
  {
    $steam = substr($steam, 8);
    $player_id = $this->db->get_row("SELECT `playerId` FROM `hlstats_PlayerUniqueIds` WHERE `game` = '$srv' AND `uniqueId` = '$steam' LIMIT 1")[0];
    $dane = $this->db->get_row("SELECT `connection_time` FROM `hlstats_Players` WHERE `playerId` = $player_id")[0];

    return $dane;
  }

  function say($srv)
  {
    $dane->serwer = $this->db->get_results("SELECT hlstats_Servers.serverId, hlstats_Servers.name FROM hlstats_Servers WHERE hlstats_Servers.game='$srv' LIMIT 1", true);
    $dane->say = $this->db->get_results("SELECT *, `playerId` AS `id_playera`, (SELECT `lastName` FROM `hlstats_Players` WHERE `playerId` = `id_playera` LIMIT 1) AS `name`, (SELECT `uniqueId` FROM `hlstats_PlayerUniqueIds` WHERE `playerId` = `id_playera` LIMIT 1) AS `steam` FROM `hlstats_Events_Chat` WHERE `serverId` = ".$dane->serwer->serverId." ORDER BY hlstats_Events_Chat.id DESC LIMIT 100", true);

    return $dane;
  }

  function ilosc_graczy($srv)
  {
    return $this->db->get_row("SELECT `players` FROM `hlstats_Servers` WHERE `game` = $srv")[0];
  }

  function top50($srv)
  {
    return $this->db->get_results("
    SELECT
      SQL_CALC_FOUND_ROWS
      hlstats_Players.playerId,
      hlstats_Players.connection_time,
                unhex(replace(hex(hlstats_Players.lastName), 'E280AE', '')) as lastName,
      hlstats_Players.flag,
      hlstats_Players.country,
      hlstats_Players.skill,
      hlstats_Players.kills,
      hlstats_Players.deaths,
      hlstats_Players.last_skill_change,
      ROUND(hlstats_Players.kills/(IF(hlstats_Players.deaths=0, 1, hlstats_Players.deaths)), 2) AS kpd,
      hlstats_Players.headshots,
      ROUND(hlstats_Players.headshots/(IF(hlstats_Players.kills=0, 1, hlstats_Players.kills)), 2) AS hpk,
      IFNULL(ROUND((hlstats_Players.hits / hlstats_Players.shots * 100), 1), 0) AS acc,
      activity,
      (SELECT uniqueId FROM hlstats_PlayerUniqueIds WHERE playerId = hlstats_Players.playerId LIMIT 1) AS steam
    FROM
      hlstats_Players
    WHERE
      hlstats_Players.game = '$srv'
      AND hlstats_Players.hideranking = 0
    ORDER BY skill DESC
    LIMIT 50
    ", true);
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
