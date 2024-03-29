<?
require __DIR__ . './../../func/SourceQuery/bootstrap.php';
use xPaw\SourceQuery\SourceQuery;

//
// SOURCEMOD sprawdzenie dostepnych nowych silnikow
//
$aktualizacja_source = Model("SourceUpdate")->sprawdz_dostepne((int)$acp_system['sourceupdate_wymus']);

//
// Powiadomienia Wirepusher
//

Model('Wirepusher')->bell();

//
// Serwery Aktualizacja danych
//
foreach($servers->servers as $servers){
  $servers->prefix_sb = SQL::one('SELECT `prefix_sb` FROM `acp_serwery` WHERE `serwer_id` = '.$servers->serwer_id.' LIMIT 1 ');

  //sv_tags
  $servers->rcon_dec = encrypt_decrypt('decrypt', SQL::one('SELECT `rcon` FROM `acp_serwery` WHERE `serwer_id` = '.$servers->serwer_id.' LIMIT 1') );
  if(!empty($servers->rcon_dec)){
    $list_tags = SQL::all("SELECT * FROM `acp_serwery_tagi` WHERE `serwer` IN (0, $servers->serwer_id) ");
    $sv_tags = 'sm_acvar_console sv_tags "! !,';
    foreach ($list_tags as $key => $value) {
      if($value->staly == 0){
        $value->losowa = rand(0, 1);
        if($value->losowa == 0){
          $sv_tags .= "$value->tekst,";
        }
      }
      else {
        $sv_tags .= "$value->tekst,";
      }
    }
    $sv_tags .= '"';
    $servers->sv_tags = $sv_tags;
  }

  // daj szanse na aktualizacje danych serwerow ktore nie odpowiadaja
  if($servers->status == 1 && strtotime($servers->status_data) < (time() - $acp_system['cron_serwery_time_off'])) {
    $db->update('acp_serwery',
      [
        'status' => '0',
      ],
      [
        'serwer_id' => $servers->serwer_id
      ]
    );
  }

  if(strtotime($acp_system['cron_serwery']) < (time() - $acp_system['time_serwery']) && $servers->status == 0){
    $Query = new SourceQuery( );

    try
    {
      switch ($servers->game) {
        case 'CSGO':
          $Query->Connect( $servers->ip, $servers->port, 1, SourceQuery::SOURCE );
          if(!empty($servers->rcon_dec)){
            $Query->SetRconPassword($servers->rcon_dec);
            $Query->Rcon( $servers->sv_tags );
          }
          break;
        case 'CS':
          $Query->Connect( $servers->ip, $servers->port, 1, SourceQuery::GOLDSOURCE );
          break;
      }

      $servers->sourcequery = $Query->GetInfo( );

      $db->insert('acp_serwery_logs',[
          'serwer_id' => $servers->serwer_id,
          'graczy' => $servers->sourcequery['Players'],
          'boty' => $servers->sourcequery['Bots'],
          'sloty' => $servers->sourcequery['MaxPlayers'],
          'data' => date('Y-m-d H:i:s'),
        ]
      );

      // update serwer
      $db->update('acp_serwery',[
          'nazwa' => $servers->sourcequery['HostName'],
          'mapa' => $servers->sourcequery['Map'],
          'graczy' => $servers->sourcequery['Players'],
          'max_graczy' => $servers->sourcequery['MaxPlayers'],
          'boty' => $servers->sourcequery['Bots'],
          'tags' => $servers->sourcequery['GameTags'],
        ],[
          'serwer_id' => $servers->serwer_id
        ]
      );

      //lista graczy cache
      $db->delete('acp_cache_api', [ 'get' => 'serwer_id'.$servers->serwer_id ]);
      $db->insert('acp_cache_api',[
        'get' => "serwer_id".$servers->serwer_id,
        'dane' => Model('Cronjobs')->jsonRemoveUnicodeSequences($Query->GetPlayers()),
        'data' => date('Y-m-d H:i:s'),
      ]);

      Model('Cronjobs')->UpdateTime('cron_serwery');
    }
    catch( Exception $e )
    {
      if($e->getMessage( ) == 'Failed to read any data from socket') {
        $db->update('acp_serwery',[
            'status' => '1',
            'status_data' => date('Y-m-d H:i:s'),
            'nazwa' => 'none',
            'mapa' => 'none',
            'graczy' => '0',
            'max_graczy' => '0',
            'boty' => '0',
            'tags' => $servers->sourcequery['GameTags'],
          ],[
            'serwer_id' => $servers->serwer_id
          ]
        );
      }
    }
    finally
    {
    	$Query->Disconnect( );
    }
  }


  //
  // Lista Adminów NEW
  //
  if(strtotime($acp_system['cron_adminlist'])< (time() - $acp_system['cron_adminlist_time'])){
    $admin_list = new stdClass();
    $admin_list_det = new stdClass();
    $i = 1;
    foreach (Model('Sourcebans')->admins_list(
        $servers->prefix_sb,
        "WHERE `authid` != '' AND `aid` != '' AND `user` != 'CONSOLE' AND `srv_group` NOT IN ('', 'Legenda', 'VIP', 'Weteran')",
        "ORDER BY FIELD(`srv_group`, 'Opiekun', 'Starszy Admin', 'Admin')"
      )
    as $admin) {
      $steamData = $Steam->GetSteamData($admin->authid);

      $admin_list->{$i++} = $admin_list_det = new stdClass();
      $admin_list_det->{'user'} = $admin->user;
      $admin_list_det->{'srv_group'} = $admin->srv_group;
      $admin_list_det->{'authid'} = $admin->authid;
      $admin_list_det->{'steam'} = $admin->authid;
      $admin_list_det->{'steam_nick'} = htmlentities($steamData['personaname']);
      $admin_list_det->{'steam_lastlogoff'} = $steamData['lastlogoff'];
      $admin_list_det->{'steam_profileurl'} = $steamData['profileurl'];
      $admin_list_det->{'steam_avatar'} = $steamData['avatar'];
      $admin_list_det->{'steam_status'} = $steamData['personastate'];
    }

    $admin_list = Model('Cronjobs')->jsonRemoveUnicodeSequences($admin_list);

    if( $db->exists('acp_cache_api', 'get', [ 'get' => 'serwer_id'.$servers->serwer_id.'_admin' ]) ){
      $db->update('acp_cache_api', [
        'dane' => $admin_list
      ], [
        'get' => 'serwer_id'.$servers->serwer_id.'_admin'
      ]);
    }
    else {
      $db->insert('acp_cache_api', [
        'get' => "serwer_id".$servers->serwer_id."_admin",
        'dane' => $admin_list
      ]);
    }

    Model('Cronjobs')->UpdateTime('cron_adminlist');
  }
}

//
// Aktualizacja danych profili steam
//
$limit_steam = $acp_system['acp_steam_count_limit'];
$users = $db->get_results("SELECT `user`, `login`, `steam`, `steam_update` FROM `acp_users` WHERE `banned` = -1 AND `steam` NOT LIKE '%STEAM%' AND `steam` != '' AND `steam_update` < NOW() - INTERVAL 900 SECOND LIMIT $limit_steam; ", true);
foreach($users as $user){
  $steamData = $Steam->GetSteamData($user->steam);

  $db->update(
    'acp_users',
    [
      'steam_avatar' => $steamData['avatarfull'],
      'steam_login' => $steamData['personaname'],
      'steam_update' => date("Y-m-d H:i:s")
    ],
    [
      'user' => $user->user
    ]
  );
}

?>
