<?
class ServerKonfiguracjaModel{
  public function __construct()
  {
    $this->x = Get::string('x');
    $this->xx = Get::string('xx');
    $this->lastUpdate = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` LIKE 'cron_$this->xx'");
    $this->timeUpdate = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` LIKE 'time_$this->xx'");
  }

  function UpdateNow($dostep)
  {
    Permission::check($dostep);

    Controller('Ustawienia')->updateConf([
      ['name' => "cron_$this->xx", 'value' => '2000-00-00 00:00:00']
    ]);

    Logs::log("Wymuszono aktualizację modułu Serwery Konfiguracja [$this->xx]");
  }

  function UpdateLast()
  {
    if($this->lastUpdate == '2000-00-00 00:00:00'){
      $tekst = 'Wymuszona wcześniejsza aktualizacja plików';
    }
    else {
      $tekst = Date::relative($this->lastUpdate);
    }

    $tekst .= "<p><a href='?x=$this->x&xx=$this->xx&wymus_aktualizacje=1'><button type='button' class='btn btn-block btn-default btn-xs'>Wymuś aktualizację</button></a></p>";
    return $tekst;
  }

  function UpdateNext()
  {
    $ostatnia_aktualizacja = $this->lastUpdate;
    $this->lastUpdate = date("Y-m-d H:i:s", (strtotime(date($this->lastUpdate)) + $this->timeUpdate));
    if($this->timeUpdate == 0) {
      return 'Moduł został wyłączony..';
    }
    if($ostatnia_aktualizacja == '2000-00-00 00:00:00'){
      return 'Wymuszona wcześniejsza aktualizacja plików, odczekaj maksymalnie jedną minutę aby została wykonana..';
    }
    $mozliwosci = array("0" => "Wyłączony", "60" => "60 sekund", "1800" => "30 minut", "3600" => "1 godzinę", "7200" => "2 godziny", "14400" => "4 godziny", "43200" => "12 godzin", "86400" => "1 dobę");
    $this->timeUpdate = $mozliwosci[$this->timeUpdate];

    $kolejna_aktualizacja = "Będzie za $this->timeUpdate, czyli $this->lastUpdate";
    return $kolejna_aktualizacja;
  }

  function serwery_aktualizowane()
  {
    $ser_list_q = SQL::all("SELECT `cronjobs`, `serwer_id`, `nazwa`, `mod`, `rangi`, `mapy`, `bazy`, `reklamy`, `hextags`, `help_menu` FROM `acp_serwery` LEFT JOIN (`acp_serwery_cronjobs`) ON `acp_serwery`.`serwer_id` = `acp_serwery_cronjobs`.`serwer` WHERE `serwer_on` = 1");
    $tekst = '<ul class="list-group">';
    $tekst .= '<h2>Serwery:</h2>';
    foreach ($ser_list_q as $ser_list) {
      $tekst .= "<a href='?x=serwery_det&serwer_id=$ser_list->serwer_id'><li class='list-group-item list-group-item-dark'> <b>[$ser_list->mod]</b> $ser_list->nazwa</li></a>";
      if($ser_list->cronjobs == 0){
        $tekst .= "<p>Aktualizacje na tym serwerze zostały wyłączone całkowicie</p>";
      }
      else {
        $ser_list->rangi = ($ser_list->rangi) ?: 0;
        $ser_list->reklamy = ($ser_list->reklamy) ?: 0;
        $ser_list->mapy = ($ser_list->mapy) ?: 0;
        $ser_list->bazy = ($ser_list->bazy) ?: 0;
        $ser_list->hextags = ($ser_list->hextags) ?: 0;
        $ser_list->help_menu = ($ser_list->help_menu) ?: 0;

        $crony = array();
        $ser_list->rangi = (1 == $ser_list->rangi) ? array_push($crony, 'rangi') : 'Nie';
        $ser_list->reklamy = (1 == $ser_list->reklamy) ? array_push($crony, 'reklamy') : 'Nie';
        $ser_list->mapy = (1 == $ser_list->mapy) ? array_push($crony, 'mapy') : 'Nie';
        $ser_list->bazy = (1 == $ser_list->bazy) ? array_push($crony, 'baza') : 'Nie';
        $ser_list->hextags = (1 == $ser_list->hextags) ? array_push($crony, 'hextags') : 'Nie';
        $ser_list->help_menu = (1 == $ser_list->help_menu) ? array_push($crony, 'help_menu') : 'Nie';

        if(in_array($this->xx, $crony)){
          $tekst .= "<p>Włączona (ON)</p>";
        }
        else {
          $tekst .= "<p>Wyłączona (OFF)</p>";
        }
      }
    }
    $tekst .='</ul>';

    return $tekst;
  }

  function deleteErrorsUpload($serwer, $dostep)
  {
    Permission::check($dostep);

    SQL::query("UPDATE `acp_serwery_bledy` SET `status` = '0' WHERE `serwer_id` = $serwer;");

    Logs::log("Oznaczono wszystkie logi z prac cyklicznych jako odczytane dla serwera ".Model('Server')->basic($serwer)->mod." (ID: $serwer)", "?x=serwery_det&serwer_id=$serwer");
  }

  function dane_cronjobs($data, $czas)
  {
    if($czas == 0){
      return 'Aktualizacja wyłączona';
    }

    $teraz = time();
    $data_srt = strtotime($data);
    $za_ile_kolejna = $data_srt + $czas - $teraz;

    return "Kolejna za ".Date::secund($za_ile_kolejna)." [Ostatnia aktualizacja: $data]";
  }

  /*
    $data [
      'kierunek' => down/up,
      'id' => integer, id table,
      'ColumnSort' => text, name column table sort,
      'table' => text, name table,
      'ColumnId' => text, name uniq table,
    ]
    $dostep - name permission
  */
  function sortKolejnosc($data, $dostep)
  {
    Permission::check($dostep);

    $poz = new stdClass();

    switch ($data['kierunek']) {
      case 'down':
        $poz->aktualna = SQL::one("SELECT `".$data['ColumnSort']."` FROM `".$data['table']."` WHERE ".$data['ColumnId']." = ".$data['id']." LIMIT 1");
        $poz->nowa = (int)$poz->aktualna - 1;
        $poz->aktualnie_zajmuje = SQL::one("SELECT ".$data['ColumnId']." FROM `".$data['table']."` WHERE `".$data['ColumnSort']."` = $poz->nowa LIMIT 1");
        if($poz->nowa <= 0){
          return;
        }

        SQL::update($data['table'], [
            $data['ColumnSort'] => $poz->nowa,
          ],
          $data['id'],
          $data['ColumnId']
        );

        SQL::update($data['table'], [
            $data['ColumnSort'] => $poz->aktualna,
          ],
          $poz->aktualnie_zajmuje,
          $data['ColumnId']
        );

        break;

      case 'up':
        $poz->aktualna = SQL::one("SELECT `".$data['ColumnSort']."` FROM `".$data['table']."` WHERE ".$data['ColumnId']." = ".$data['id']." LIMIT 1");
        $poz->nowa = (int)$poz->aktualna + 1;
        $poz->aktualnie_zajmuje = SQL::one("SELECT ".$data['ColumnId']." FROM `".$data['table']."` WHERE `".$data['ColumnSort']."` = $poz->nowa LIMIT 1");

        $poz->najwieksza = SQL::one("SELECT `".$data['ColumnSort']."` FROM `".$data['table']."` ORDER BY `".$data['ColumnSort']."` DESC LIMIT 1");
        if($poz->nowa > $poz->najwieksza){
          return;
        }

        SQL::update($data['table'], [
            $data['ColumnSort'] => $poz->nowa,
          ],
          $data['id'],
          $data['ColumnId']
        );

        SQL::update($data['table'], [
            $data['ColumnSort'] => $poz->aktualna,
          ],
          $poz->aktualnie_zajmuje,
          $data['ColumnId']
        );

        break;
    }
  }
}
?>
