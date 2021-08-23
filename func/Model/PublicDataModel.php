<?php
class PublicDataModel
{
  function __construct()
  {
    $this->media = [
        'media_fb' => [
            'icon' => 'facebook',
            'button' => 'facebook'
        ],
        'media_insta' => [
            'icon' => 'instagram',
            'button' => 'instagram'
        ],
        'media_steam' => [
            'icon' => 'steam',
            'button' => 'github'
        ],
        'media_yt' => [
            'icon' => 'youtube',
            'button' => 'google'
        ]
    ];

    $this->page = [
      'nazwa' => SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'acp_nazwa'"),
      'wersja' => SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'acp_wersja'"),
    ];

  }

  function social()
  {
    $tekst = '<div class="row">
      <div class="col-lg-12">
        <div class="text-center">';
        $media = SQL::all("SELECT * FROM `acp_system` WHERE `conf_name` LIKE '%media%'");
        foreach ($media as $value):
          if(!empty($value->conf_value)):
            $tekst .= '<a href="'.$value->conf_value.'" target="_blank" class="btn btn-'.$this->media[$value->conf_name]['button'].'"><i class="fa fa-'.$this->media[$value->conf_name]['icon'].'"></i></a>';
          endif;
        endforeach;
    $tekst .= '</div>
      </div>
    </div>';
    return $tekst;
  }

  function stopka()
  {
    return '<div class="row" style="margin-top: 10px;">
      <div class="col-lg-12 text-center text-lg-left">
        <p style="color: #fff;"><b>'.$this->page['nazwa'].' | ACP</b>  </br>Version '.$this->page['wersja'].'</p>
      </div>
    </div>';
  }

  function menu($x, $www, $nazwa)
  {
    $system = new stdClass();

    if(SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'danepub_menu_on' LIMIT 1") == 0){
      return;
    }

    $system->menu = json_decode(SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'danepub_menu_list' LIMIT 1"));
    $tekst = '<nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="'.$www.'">'.$nazwa.' | ACP</a>
        </div>
        <ul class="nav navbar-nav">';

          foreach ($system->menu as $key => $value) {
            $value->link_act = explode("?x=", $value->link);
            $active = ($x == $value->link_act[1]) ? 'class="active"' : '';
            $tekst .= '<li '.$active.'><a href="'.$value->link.'" '.$value->blank.'>'.$value->page.'</a></li>';
          }
        $tekst .= '</ul>
      </div>
    </nav>';
    return $tekst;
  }

  function serwer_list($x, $serwer_on=1)
  {
    if($serwer_on == 1){
      return SQL::all("SELECT `serwer_id`, `status`, `prefix_sb`, `istotnosc`, `game`, `ip`, `port`, `nazwa`, `mod`, `graczy`, `max_graczy`, `boty`, `mapa` FROM `acp_serwery` WHERE `serwer_on` = 1 AND `test_serwer` = '0' ORDER BY `istotnosc` ASC");
    }
    return SQL::all("SELECT `serwer_id`, `status`, `prefix_sb`, `istotnosc`, `game`, `ip`, `port`, `nazwa`, `mod`, `graczy`, `max_graczy`, `boty`, `mapa` FROM `acp_serwery` ORDER BY `istotnosc` ASC");
  }

  function serwer_banner($id)
  {
    return Controller('Server')->banner($id);
  }

  function admin_list($id)
  {
    return json_decode( SQL::one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$id."_admin' LIMIT 1;") );
  }

  function changelog_list($id)
  {
    return SQL::all("SELECT *, `user` AS `user_id`, (SELECT `login` FROM `acp_users` WHERE `user` = `user_id` LIMIT 1) AS `user_name` FROM `acp_log_serwery` WHERE `serwer_id` = $id;");
  }

  function hlstats_top_list($id)
  {
    return SQL::all("SELECT `id`, `serwer_id` AS `srv_id`, `data`, DATE_ADD(`data`, INTERVAL -1 DAY) AS `new_data`, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `srv_id` LIMIT 1) AS `nazwa` FROM `acp_serwery_hlstats_top` WHERE `serwer_id` = $id;");
  }
  function hlstats_top_details($id)
  {
    $dane = SQL::one("SELECT `dane` FROM `acp_serwery_hlstats_top` WHERE `id` = $id;");
    $dane = json_decode(stripslashes($dane));
    return $dane;
  }
  function hlstats_top_details_dane($id)
  {
    return SQL::row("SELECT `id`, `serwer_id` AS `srv_id`, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `srv_id` LIMIT 1) AS `nazwa`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `srv_id` LIMIT 1) AS `mod`, DATE_ADD(`data`, INTERVAL -1 DAY) AS `data` FROM `acp_serwery_hlstats_top` WHERE `id` = $id LIMIT 1;");
  }

  function serwer_details($id)
  {
    $dane = SQL::row("SELECT `game`, `ip`, `port`, `mod`, `status`, `nazwa`, `graczy`, `max_graczy`, `boty`, `tags`, `mapa`, `ser_a_jr`, `ser_a_opiekun`, `ser_a_copiekun` FROM `acp_serwery` WHERE `serwer_id` = $id;");

    $dane->junioradmin = (empty($dane->ser_a_jr)) ? '<i>Brak danych</i>' : User::printName($dane->ser_a_jr, false);
    $dane->opiekun = (empty($dane->ser_a_opiekun)) ? '<i>Brak danych</i>' : User::printName($dane->ser_a_opiekun, false);
    $dane->zastepca = (empty($dane->ser_a_copiekun)) ? '<i>Brak danych</i>' : User::printName($dane->ser_a_copiekun, false);

    $dane->regulamin = SQL::row("SELECT * FROM `acp_serwery_regulamin` WHERE `serwer_id` = $id");
    $dane->mapa_img = Model('Server')->map_img($dane->mapa);
    $dane->graczy_live = json_decode( SQL::one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id$id' LIMIT 1; ") );

    $dane->hlstats = SQL::row("SELECT `data`, `hls_graczy`, `hls_nowych_graczy`, `hls_zabojstw`, `hls_nowych_zabojstw`, `hls_hs`, `hls_nowych_hs` FROM `acp_serwery_hlstats` WHERE `serwer_id` = $id LIMIT 1");
    $dane->changelog = SQL::all("SELECT * FROM `acp_log_serwery` WHERE `serwer_id` = $id LIMIT 5");
    $dane->logs = SQL::all("SELECT `graczy`, `boty`, `sloty`, `data` FROM `acp_serwery_logs_hour` WHERE `serwer_id` = $id LIMIT 24");
    return $dane;
  }
}
?>
