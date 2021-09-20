<?
require_once('func/FTP.php');

$TEST = 0;
$MakeFile = Model('MakeFile');
$Cronjobs = Model('Cronjobs');

$test_servers = ($TEST == 1) ? 'AND `test_serwer` = 1' : 'AND `serwer_on` = 1 AND `cronjobs` = 1';
$servers = SQL::all("SELECT `serwer_id`, `mod`, `test_serwer`, `nazwa`, `serwer_on`, `ftp_user`, `ftp_haslo`, `ftp_host`, `rangi`, `mapy`, `mapy_plugin`, `help_menu`, `bazy`, `reklamy`, `hextags`, `uslugi`, `katalog` FROM `acp_serwery` LEFT JOIN (`acp_serwery_cronjobs`) ON `acp_serwery`.`serwer_id` = `acp_serwery_cronjobs`.`serwer` WHERE `ip` != '' AND `port` != '' $test_servers");

foreach ($servers as $server) {
  if($TEST == 1){
    show('UWAGA! Włączony tryb testowy!', false);
  }

  $connect_server = new FTP([
    'ftp_user' => $server->ftp_user,
    'ftp_password' => encrypt_decrypt('decrypt', $server->ftp_haslo),
    'ftp_host' => $server->ftp_host,
    'sever' => $server->serwer_id
  ]);

  if($Cronjobs->ThisTime($acp_system['cron_uslugi'], $acp_system['time_uslugi']) && $server->uslugi == 1){
    $connect_server->upload([
      'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
      'ftp_dest_file_name' => $MakeFile->makeFile("admins_simple.ini", $server->serwer_id, "uslugi"),
      'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/admins_simple.ini",
      'type_upload' => 'FTP_ASCII',
      'modul' => "?x=uslugi",
      'info_wykonanie' => 'cron_uslugi'
    ]);
  }

  if($Cronjobs->ThisTime($acp_system['cron_reklamy'], $acp_system['time_reklamy']) && $server->reklamy == 1){
    $connect_server->upload([
      'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
      'ftp_dest_file_name' => $MakeFile->makeFile("reklama.ini", $server->serwer_id, "reklamy"),
      'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/reklama.ini",
      'type_upload' => 'FTP_ASCII',
      'modul' => "?x=serwery_konfiguracja&xx=reklamy",
      'info_wykonanie' => 'cron_reklamy'
    ]);
  }

  if($Cronjobs->ThisTime($acp_system['cron_hextags'], $acp_system['time_hextags']) && $server->hextags == 1){
    $connect_server->upload([
      'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
      'ftp_dest_file_name' => $MakeFile->makeFile("hextags.cfg", $server->serwer_id, "hextags"),
      'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/hextags.cfg",
      'type_upload' => 'FTP_ASCII',
      'modul' => "?x=serwery_konfiguracja&xx=hextags",
      'info_wykonanie' => 'cron_hextags'
    ]);
  }

  if($Cronjobs->ThisTime($acp_system['cron_baza'], $acp_system['time_baza']) && $server->bazy == 1){
    $connect_server->upload([
      'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
      'ftp_dest_file_name' => $MakeFile->makeFile("databases.cfg", $server->serwer_id, "database"),
      'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/databases.cfg",
      'type_upload' => 'FTP_ASCII',
      'modul' => "?x=serwery_konfiguracja&xx=baza",
      'info_wykonanie' => 'cron_baza'
    ]);
  }

  if($Cronjobs->ThisTime($acp_system['cron_mapy'], $acp_system['time_mapy']) && $server->mapy == 1){
    if(is_null($server->mapy_plugin) || $server->mapy_plugin == 'UMC') {
      $connect_server->upload([
        'ftp_directory' => $server->katalog,
        'ftp_dest_file_name' => $MakeFile->makeFile("umc_mapcycle.txt", $server->serwer_id, "mapy_umc"),
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/umc_mapcycle.txt",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=mapy",
        'info_wykonanie' => 'cron_mapy'
      ]);
    }
    else {
      $MakeFile->makeFile("lista_map.txt", $server->serwer_id, "mapchooser");

      $connect_server->upload([
        'ftp_directory' => $server->katalog,
        'ftp_dest_file_name' => 'maplist.txt',
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/lista_map.txt",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=mapy",
        'info_wykonanie' => 'cron_mapy'
      ]);
      $connect_server->upload([
        'ftp_directory' => $server->katalog,
        'ftp_dest_file_name' => 'mapcycle.txt',
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/lista_map.txt",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=mapy",
        'info_wykonanie' => 'cron_mapy'
      ]);
      $connect_server->upload([
        'ftp_directory' =>  $server->katalog."/addons/sourcemod/configs/mapchooser_extended/maps",
        'ftp_dest_file_name' => 'csgo.txt',
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/lista_map.txt",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=mapy",
        'info_wykonanie' => 'cron_mapy'
      ]);
      $connect_server->upload([
        'ftp_directory' =>  $server->katalog."/addons/sourcemod/configs/mapchooser_extended/maps",
        'ftp_dest_file_name' => 'cstrike.txt',
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/lista_map.txt",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=mapy",
        'info_wykonanie' => 'cron_mapy'
      ]);
    }
  }

  if($Cronjobs->ThisTime($acp_system['cron_help_menu'], $acp_system['time_help_menu']) && $server->help_menu == 1){
    $connect_server->upload([
      'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
      'ftp_dest_file_name' => $MakeFile->makeFile("acp_main_menu.cfg", $server->serwer_id, "help_menu"),
      'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/acp_main_menu.cfg",
      'type_upload' => 'FTP_ASCII',
      'modul' => "?x=serwery_konfiguracja&xx=help_menu",
      'info_wykonanie' => 'cron_help_menu'
    ]);

    $konfiguracja = SQL::row("SELECT * FROM `acp_serwery_helpmenu` WHERE `serwer_id` = $server->serwer_id;");
    if($konfiguracja->lista_serwerow == 1){
      $connect_server->upload([
        'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
        'ftp_dest_file_name' => $MakeFile->makeFile("acp_servers_menu.cfg", $server->serwer_id, "help_menu_listaserwerow"),
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/acp_servers_menu.cfg",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=help_menu"
      ]);
      $connect_server->upload([
        'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
        'ftp_dest_file_name' => $MakeFile->makeFile("acp_details_menu.cfg", $server->serwer_id, "help_menu_listaserwerow_details"),
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/acp_details_menu.cfg",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=help_menu"
      ]);
    }
    if($konfiguracja->lista_adminow == 1){
      $connect_server->upload([
        'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
        'ftp_dest_file_name' => $MakeFile->makeFile("acp_admins_menu.cfg", $server->serwer_id, "help_menu_listaadminow"),
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/acp_admins_menu.cfg",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=help_menu"
      ]);
    }
    if($konfiguracja->opis_vipa == 1){
      $connect_server->upload([
        'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
        'ftp_dest_file_name' => $MakeFile->makeFile("acp_vip_panel.cfg", $server->serwer_id, "help_menu_opisvipa"),
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/acp_vip_panel.cfg",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=help_menu"
      ]);
    }
    if($konfiguracja->lista_komend == 1){
      $connect_server->upload([
        'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
        'ftp_dest_file_name' => $MakeFile->makeFile("acp_command_menu.cfg", $server->serwer_id, "help_menu_komendy"),
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/acp_command_menu.cfg",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=help_menu"
      ]);
    }
    if($konfiguracja->statystyki == 1){
      $connect_server->upload([
        'ftp_directory' => $server->katalog."/addons/sourcemod/configs",
        'ftp_dest_file_name' => $MakeFile->makeFile("acp_stats_menu.cfg", $server->serwer_id, "help_menu_statystyki"),
        'ftp_source_file_name' => "www/upload/serwer_$server->serwer_id/acp_stats_menu.cfg",
        'type_upload' => 'FTP_ASCII',
        'modul' => "?x=serwery_konfiguracja&xx=help_menu"
      ]);
    }
  }

  // wgrywarka
  $wgrywarkaList = SQL::all("SELECT * FROM `acp_wgrywarka` WHERE `status` = 0 AND `serwer_id` = $server->serwer_id");
  foreach ($wgrywarkaList as $wgrywarkaLine) {
    $wgrywarkaLine->file = json_decode($wgrywarkaLine->file);
    foreach ($wgrywarkaLine->file as $value) {
      $connect_server->upload([
        'ftp_directory' => $server->katalog.$value->ftp_directory,
        'ftp_dest_file_name' => $value->ftp_dest_file_name,
        'ftp_source_file_name' => $value->ftp_source_file_name,
        'type_upload' => 'FTP_BINARY',
        'modul' => "?x=wgrywarka",
        'wgrywarka_file_id' => $wgrywarkaLine->id
      ]);
    }
  }

  if($Cronjobs->ThisTime($acp_system['cron_file_list_pluginy'], $acp_system['cron_file_list_pluginy_time'])){
    $connect_server->scan([
      'katalog' => $server->katalog."/addons/sourcemod/plugins",
      'type' => 'nlist',
      'acp_cache_api' => 'serwer_id'.$server->serwer_id.'_pluginy',
      'info_wykonanie' => 'cron_file_list_pluginy'
    ]);
  }

  if($Cronjobs->ThisTime($acp_system['cron_file_list_mapy'], $acp_system['cron_file_list_mapy_time'])){
    $connect_server->scan([
      'katalog' => $server->katalog."/maps",
      'type' => 'nlist',
      'acp_cache_api' => 'serwer_id'.$server->serwer_id.'_mapy',
      'info_wykonanie' => 'cron_file_list_mapy'
    ]);
  }

  if($Cronjobs->ThisTime($acp_system['cron_file_list_logi'], $acp_system['cron_file_list_logi_time'])){
    $connect_server->scan([
      'katalog' => $server->katalog."/addons/sourcemod/logs",
      'type' => 'nlist',
      'acp_cache_api' => 'serwer_id'.$server->serwer_id.'_logs_sm',
      'info_wykonanie' => 'cron_file_list_logi'
    ]);
    $connect_server->scan([
      'katalog' => $server->katalog."/logs",
      'type' => 'nlist',
      'acp_cache_api' => 'serwer_id'.$server->serwer_id.'_logs',
      'info_wykonanie' => 'cron_file_list_logi'
    ]);
  }

  // ROUNDSOUND
  if(Controller('RoundsoundUploadFTP')->setting['rs_on'] == '1'){
    foreach ( Controller('RoundsoundUploadFTP')->setting['rs_serwery'] as $rs_serwer) {

      if(strtotime(Controller('RoundsoundUploadFTP')->setting['rs_cron'])< (time() - 360) && $server->serwer_id == $rs_serwer){
        $connect_server->upload([
          'ftp_directory' =>  $server->katalog."/addons/sourcemod/configs",
          'ftp_dest_file_name' => $MakeFile->makeFile("abner_res.txt", $server->serwer_id, "roundsound"),
          'ftp_source_file_name' => "www/upload/serwer_".$server->serwer_id."/abner_res.txt",
          'type_upload' => 'FTP_ASCII',
          'modul' => "?x=roundsound",
          'info_wykonanie' => "rs_cron",
          'special_table' => "rs_ustawienia"
        ]);
        $connect_server->upload([
          'ftp_directory' =>  $server->katalog."/cfg/sourcemod",
          'ftp_dest_file_name' => $MakeFile->makeFile("abner_res.cfg", $server->serwer_id, "roundsound_cfg"),
          'ftp_source_file_name' => "www/upload/serwer_".$server->serwer_id."/abner_res.cfg",
          'type_upload' => 'FTP_ASCII',
          'modul' => "?x=roundsound",
          'info_wykonanie' => "rs_cron",
          'special_table' => "rs_ustawienia"
        ]);
      }

      if(strtotime(Controller('RoundsoundUploadFTP')->setting['rs_cron_utwory'])< (time() - 360) && $server->serwer_id == $rs_serwer){
        foreach (Controller('RoundsoundUploadFTP')->SongList() as $value){
          $piosenka_code = SQL::one("SELECT `mp3_code` FROM `rs_utwory` WHERE `id` = $value LIMIT 1");
          $connect_server->upload([
            'ftp_directory' =>  $server->katalog."/sound/".Controller('RoundsoundUploadFTP')->setting['rs_katalog']."/".Controller('RoundsoundUploadFTP')->setting['rs_roundsound'],
            'ftp_dest_file_name' => "$piosenka_code.mp3",
            'ftp_source_file_name' => "www/mp3/$piosenka_code.mp3",
            'type_upload' => 'FTP_BINARY',
            'modul' => "?x=roundsound_utowry",
            'info_wykonanie' => "rs_cron_utwory",
            'special_table' => "rs_ustawienia"
          ]);
        }

      }
    }
  }

}

// Blokowanie cronjoba gdy 5 razy w ciagu 6 h wystapi problem z polaczeniem
foreach($servers as $server){
  (int)$liczba_bledow = SQL::one("SELECT COUNT(*) FROM `acp_serwery_bledy` WHERE `serwer_id` = $server->serwer_id AND `status` = 1 AND `data` > NOW() - INTERVAL 3 HOUR");
  if($liczba_bledow >= 5 && $server->test_serwer == 0){
    SQL::query("UPDATE `acp_serwery` SET `cronjobs` = '-1' WHERE `serwer_id` = $server->serwer_id;");

    Powiadomienia::new(
      User::getUserHavePermission('SerwerCron'),
      [],
      "?x=serwery_ust&edycja=$server->serwer_id",
      "Cronjobs | Serwer: $server->nazwa [$server->mod](ID: $server->serwer_id) został zablokowany z powodu problemów z połaczeniem. Sprawdź dane FTP a następnie ustaw edycję plików na [Tak]",
      "fa fa-server"
    );
  }
}

// kasowanie plików
foreach($servers as $server){
  File::delete_old_files("www/upload/serwer_$server->serwer_id");
}
?>
