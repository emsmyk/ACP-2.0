<?php
class ServerController
{
    function list()
    {
      $db = DB::getInstance();

      return $db->get_results("SELECT `serwer_id`, `status`, `prefix_sb`, `istotnosc`, `game`, `ip`, `port`, `nazwa`, `mod`, `graczy`, `max_graczy`, `boty`, `mapa` FROM `acp_serwery` WHERE `serwer_on` = 1 AND `test_serwer` = '0' ORDER BY `istotnosc` ASC");
    }

    function banner($id)
    {
      $banner = "./www/server_banner/$id.png";
      $banner = (file_exists($banner)) ? $banner : './www/server_banner/0.png';

      return $banner;
    }

    function AllStats()
    {
      $db = DB::getInstance();

      return [
        'servers' => $db->get_row("SELECT COUNT(`ip`) as `count` FROM `acp_serwery` WHERE `test_serwer` = '0'")[0],
        'players' => $db->get_row("SELECT sum(`graczy`) AS `graczy` FROM `acp_serwery` WHERE `test_serwer` = '0'")[0],
        'slots' => $db->get_row("SELECT sum(`max_graczy`) AS `max_graczy` FROM `acp_serwery` WHERE `test_serwer` = '0'")[0],
        'prc' => round($db->get_row("SELECT sum(`graczy`)*100/sum(`max_graczy`) AS `graczy` FROM `acp_serwery` WHERE `test_serwer` = '0'")[0], 2),
      ];
    }

}
?>
