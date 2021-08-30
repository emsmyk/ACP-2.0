<?php
class ServerUstawieniaController
{

  function __construct()
  {
    $this->serverId = Get::int('server_id');
  }

  function index()
  {
    return SQL::all("SELECT *,
    (SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_jr`) AS ser_jr,
    (SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_opiekun`) AS ser_opiekun,
    (SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_copiekun`) AS ser_copiekun
    FROM `acp_serwery`;");
  }

  function edit()
  {
    return SQL::row("SELECT * FROM `acp_serwery` WHERE `serwer_id` = $this->serverId;");
  }

  function editCron()
  {
    return SQL::row("SELECT *, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` =  $this->serverId LIMIT 1) AS `nazwa` FROM `acp_serwery_cronjobs` WHERE `serwer` =  $this->serverId;");
  }

  function store($dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'mod' => 'reg',
    ],[
      'mod.reg' => 'Nazwa Moda dla serwera jest wymagana'
    ]);

    $from->test_serwer = ($_POST['test_serwer'] == 'on') ? 1 : 0;

    $last_insert = SQL::insert('acp_serwery',[
        'game' => $from->new_gra,
        'mod' => $from->mod,
        'test_serwer' => $from->test_serwer,
        'ip' => $from->ip,
        'port' => $from->port,
      ]
    );
    SQL::insert('acp_serwery_cronjobs',[
        'serwer' => $last_insert
      ]
    );

    Logs::log("Dodano nowy serwer $from->ip:$from->port (ID: $last_insert)", "?x=serwery_ust");
  }

  function update($dostep)
  {
    Permission::check($dostep);
    Permission::check($dostep);

    $from = From::check([
      'mod' => 'reg',
    ],[
      'mod.reg' => 'Nazwa Modu serwera jest wymagana'
    ]);

    $from->rcon = (empty($from->rcon)) ? $this->edit()->rcon : encrypt_decrypt('encrypt', $from->rcon);
    $from->ftpp = (empty($from->ftpp)) ? $this->edit()->ftp_haslo : encrypt_decrypt('encrypt', $from->ftpp);

    SQL::update('acp_serwery',[
        'test_serwer' => $from->test_serwer,
        'ip' => $from->ip,
        'port' => $from->port,
        'prefix_sb' => $from->prefix_sb,
        'prefix_hls' => $from->prefix_hls,
        'serwer_on' => $from->wlaczony,
        'cronjobs' => $from->cronjobs,
        'istotnosc' => $from->istonosc,
        'rcon' => $from->rcon,
        'czas_reklam' => $from->czasreklam,
        'liczba_map' => $from->liczbamap,
        'ip_bot_hlstats' => $from->botip,
        'link_gotv' => $from->gotvlink,
        'ftp_user' => $from->ftpu,
        'ftp_haslo' => $from->ftpp,
        'ftp_host' => $from->ftph,
        'ser_a_jr' => $from->junioradmin,
        'ser_a_opiekun' => $from->opiekun,
        'ser_a_copiekun' => $from->copiekun
      ],
      $this->serverId,
      'serwer_id'
    );

    Logs::log("Zaktualizowano ustawienia serwera ".$this->edit()->nazwa." MOD: $from->e_mod (ID: $this->serverId)", "?x=serwery_ust&edycja=$this->serverId");
  }

  function updateCron($dostep)
  {
    Permission::check($dostep);

    $from = From::check();

    $from->mapy_plugin = (empty($from->mapy_plugin)) ? 'NULL' : $from->mapy_plugin;

    SQL::update('acp_serwery_cronjobs',[
        'typ_polaczenia' => $from->typ_polaczenia,
        'katalog' => $from->katalog,
        'reklamy' => $from->reklamy,
        'bazy' => $from->bazy,
        'cvary' => $from->cvary,
        'mapy' => $from->mapy,
        'mapy_plugin' => $from->mapy_plugin,
        'hextags' => $from->hextags,
        'help_menu' => $from->help_menu,
        'uslugi' => $from->uslugi,
      ],
      $this->serverId,
      'serwer'
    );

    Logs::log("Zaktualizowano zdalne prace wykonywane na dla serwera ".$this->edit()->nazwa." ($this->serverId)", "?x=serwery_ust&cron=$this->serverId");
  }

  function updateImg($dostep)
  {
    Permission::check($dostep);


    $file_name = $_FILES['nazwa_pliku']['name'];
    $file_size = $_FILES['nazwa_pliku']['size'];
    $file_tmp = $_FILES['nazwa_pliku']['tmp_name'];
    $file_type = $_FILES['nazwa_pliku']['type'];

    if($file_size > 2097152){
      return Messe::array([
        'type' => 'info',
        'text' => "Obrazek jest za duży, makysmalna wielkość to 2 MB"
      ]);
    }
    if(file_exists("www/server_banner/".$this->edit()->serwer_id.".png")) {
      unlink("www/server_banner/".$this->edit()->serwer_id.".png");
    }

    move_uploaded_file($file_tmp,"www/server_banner/".$this->edit()->serwer_id.".png");

    Logs::log("Zaktualizowano obrazek serwera ".$this->edit()->nazwa." ($this->serverId)", "?x=serwery_ust&edytuj=$this->serverId");
  }

  function destroy($dostep)
  {
    Permission::check($dostep);

    SQL::query("DELETE FROM `acp_serwery` WHERE `serwer_id` = $this->serverId LIMIT 1;");
    SQL::query("DELETE FROM `acp_serwery_cronjobs` WHERE `serwer` = $this->serverId LIMIT 1;");
    SQL::query("DELETE FROM `acp_serwery_logs` WHERE `serwer_id` = $this->serverId;");
    SQL::query("DELETE FROM `acp_serwery_logs_day` WHERE `serwer_id` = $this->serverId;");
    SQL::query("DELETE FROM `acp_serwery_logs_hour` WHERE `serwer_id` = $this->serverId;");
    SQL::query("DELETE FROM `acp_serwery_logs_month` WHERE `serwer_id` = $this->serverId;");

    Logs::log("Usunięto serwer ".$this->edit()->nazwa." ID: $this->serverId");
  }
}
 ?>
