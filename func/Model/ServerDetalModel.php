<?php
class ServerDetalModel
{
  function AdminListCache($server, $list)
  {
    $lista_adminow_cache = json_decode(SQL::one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$server."_admin' LIMIT 1"));
    if(empty($lista_adminow_cache))
    {
      return [
        'steam' => '',
        'steam_nick' => 'Nieznany',
        'steam_avatar' => '',
        'steam_profileurl' => '',
        'steam_status' => '',
        'steam_lastlogoff' => '0000-00-00 00:00',
      ];
    }

    foreach ($lista_adminow_cache as $lista_adminow_cache_dane) {
      if($lista_adminow_cache_dane->user == $list){
        return [
          'steam' => $lista_adminow_cache_dane->steam,
          'steam_nick' => $lista_adminow_cache_dane->steam_nick,
          'steam_avatar' => $lista_adminow_cache_dane->steam_avatar,
          'steam_profileurl' => $lista_adminow_cache_dane->steam_profileurl,
          'steam_status' => $lista_adminow_cache_dane->steam_status,
          'steam_lastlogoff' => date("Y-m-d H:i:s", $lista_adminow_cache_dane->steam_lastlogoff),
        ];
      }
    }

  }

  function AdminListStatus($status)
  {
    if(empty($status)){
      return '<i class="fa fa-circle text-white"></i>';
    }
    if($status == 0){
      return '<i class="fa fa-circle text-red"></i>';
    }
    if($status == 1){
      return '<i class="fa fa-circle text-green"></i>';
    }
    if($status == 3){
      return '<i class="fa fa-circle text-yellow"></i>';
    }
    if($status == 4){
      return '<i class="fa fa-circle text-yellow"></i>';
    }
  }
}
?>
