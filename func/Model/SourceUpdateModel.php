<?php
class SourceUpdateModel
{

  function __construct()
  {

  }

  public function sprawdz_dostepne($wymus){
    $godzina = (int) date('G');
    $last = SQL::row("SELECT * FROM `acp_sourceupdate` ORDER BY `id` DESC LIMIT 1");

    if($godzina == 3 || $wymus == 1 && strtotime($last->data) < (time() - 60*60)){
      $sm = new stdClass();
      $mm = new stdClass();
      $sm->last = file_get_contents("http://www.sourcemod.net/smdrop/1.11/sourcemod-latest-linux");
      $mm->last = file_get_contents("http://www.metamodsource.net/mmsdrop/1.11/mmsource-latest-linux");

      if($last->sm != $sm->last || $last->mm != $mm->last){
        $losowe_znaczki = generujLosowyCiag();

        SQL::insert('acp_sourceupdate',[
            'sm' => $sm->last,
            'mm' => $mm->last,
            'code' => $losowe_znaczki,
          ]
        );

        // pobranie i wypakowanie sm i mm
        if (!file_exists("www/sourceupdate/".$losowe_znaczki."-sm.tar.gz")) {
          $sm->sourcemod__pobierz = file_put_contents("www/sourceupdate/$losowe_znaczki-sm.tar.gz", fopen("http://www.sourcemod.net/smdrop/1.11/".$sm->last, 'r'));
          if(!is_dir("www/sourceupdate/$losowe_znaczki-sm")){
            ini_set('memory_limit', '-1');
            $archive = new PharData("www/sourceupdate/$losowe_znaczki-sm.tar.gz");
            mkdir("www/sourceupdate/$losowe_znaczki-sm");
            $archive->extractTo("www/sourceupdate/$losowe_znaczki-sm");

            Powiadomienia::new(
              User::getUserHavePermission('SourceUpdate'),
              [],
              "?x=sourceupdate",
              "SourceUpdate | Wygryto nowe wersje systemu SM ($sm->last), wersje jest gotowa do wgrania na serwery",
              "fa fa-server"
            );

          }
        }
        if (!file_exists("www/sourceupdate/".$losowe_znaczki."-mm.tar.gz")) {
          $mm->sourcemod__pobierz = file_put_contents("www/sourceupdate/$losowe_znaczki-mm.tar.gz", fopen("http://www.metamodsource.net/mmsdrop/1.11/".$mm->last, 'r'));
          if(!is_dir("www/sourceupdate/$losowe_znaczki-mm")){
            ini_set('memory_limit', '-1');
            $archive = new PharData("www/sourceupdate/$losowe_znaczki-mm.tar.gz");
            mkdir("www/sourceupdate/$losowe_znaczki-mm");
            $archive->extractTo("www/sourceupdate/$losowe_znaczki-mm");

            Powiadomienia::new(
              User::getUserHavePermission('SourceUpdate'),
              [],
              "?x=sourceupdate",
              "Wygryto nowe wersje systemu MM ($mm->last), wersje jest gotowa do wgrania na serwery",
              "fa fa-server"
            );

          }
        }
      }
    }

    Controller('Ustawienia')->updateConf([
      ['name' => 'sourceupdate_wymus', 'value' => '0']
    ]);

  }

  public function aktualizuj($user, $dostep){
    Permission::check($dostep);

    $from = From::check();
    $from->serwer_dane = Model('Server')->basic($from->serwer_id);
    $from->sourcemod_nazwa = SQL::one("SELECT `sm` FROM `acp_sourceupdate` WHERE `code` = '$from->sourcemod' LIMIT 1");
    $from->metamod_nazwa = SQL::one("SELECT `mm` FROM `acp_sourceupdate` WHERE `code` = '$from->metamod' LIMIT 1");

    $from->sourcemod_katalogi = [];
    $from->sm_bin = (isset($from->sm_bin)) ? $from->sm_bin : null;
    $from->sourcemod_katalogi[] = ($from->sm_bin == 'on') ? '/sourcemod/bin/' : 'gowno&213&dsadewr34sdfdfgh';
    $from->sm_configs = (isset($from->sm_configs)) ? $from->sm_configs : null;
    $from->sourcemod_katalogi[] = ($from->sm_configs == 'on') ? '/sourcemod/configs/' : 'gowno&213&dsadewr34sdfdfgh';

    $from->sm_extensions = (isset($from->sm_extensions )) ? $from->sm_extensions : null;
    $from->sourcemod_katalogi[] = ($from->sm_extensions == 'on') ? 'addons/sourcemod/extensions/' : 'gowno&213&dsadewr34sdfdfgh';
    $from->sm_gamedata = (isset($from->sm_gamedata)) ? $from->sm_gamedata : null;
    $from->sourcemod_katalogi[] = ($from->sm_gamedata == 'on') ? 'addons/sourcemod/gamedata/' : 'gowno&213&dsadewr34sdfdfgh';
    $from->sm_plugins = (isset($from->sm_plugins)) ? $from->sm_plugins : null;
    $from->sourcemod_katalogi[] = ($from->sm_plugins == 'on') ? 'addons/sourcemod/plugins/' : 'gowno&213&dsadewr34sdfdfgh';
    $from->sm_scripting = (isset($from->sm_scripting)) ? $from->sm_scripting : null;
    $from->sourcemod_katalogi[] = ($from->sm_scripting == 'on') ? 'addons/sourcemod/scripting/' : 'gowno&213&dsadewr34sdfdfgh';
    $from->sm_translations = (isset($from->sm_translations)) ? $from->sm_translations : null;
    $from->sourcemod_katalogi[] = ($from->sm_translations == 'on') ? 'addons/sourcemod/translations/' : 'gowno&213&dsadewr34sdfdfgh';
    $from->sm_cfg = (isset($from->sm_cfg)) ? $from->sm_cfg : null;
    $from->sourcemod_katalogi[] = ($from->sm_cfg == 'on') ? 'addons/cfg/sourcemod/' : 'gowno&213&dsadewr34sdfdfgh';

    if($from->sourcemod == '0' && $from->metamod == '0'){
      return Messe::array([
        'type' => 'warning',
        'text' => "Wybierz co chcesz zaktualizować."
      ]);
    }

    if($from->sourcemod != '0'){
      $pliki = array();
      $path = "www/sourceupdate/".$from->sourcemod."-sm";
      if(is_dir($path)){
        $files = getDirContents($path);
        foreach ($files as $key => $value) {
          if(is_file($value) && str_replace($from->sourcemod_katalogi, '', $value) != $value) {
            $nazwa = explode('/', $value);
            $nazwa = end($nazwa);
            $katalog = str_replace($nazwa, '', $value);
            $katalog = str_replace("www/sourceupdate/".$from->sourcemod."-sm", '', $katalog);

            array_push($pliki, (object)array('ftp_directory' => "$katalog", 'ftp_source_file_name' => "$value", 'ftp_dest_file_name'=> "$nazwa") );
          }
        }

        $pliki = json_encode($pliki);

        SQL::insert('acp_wgrywarka',[
            'serwer_id' => $from->serwer_id,
            'u_id' => $user,
            'nazwa' => $from->sourcemod_nazwa,
            'kat' => 'Sourcemod',
            'file' => $pliki,
          ]
        );

        Logs::log("Zlecono aktualizację $from->sourcemod_nazwa dla serwera ".$from->serwer_dane->mod."(ID: $from->serwer_id)");

        $serwer_update = SQL::one("SELECT `id` FROM `acp_serwery_update` WHERE `serwer_id` = $from->serwer_id");
        if(empty($serwer_update)){
          SQL::insert('acp_serwery_update',[
              'serwer_id' => $from->serwer_id,
              'sm' => $from->sourcemod_nazw,
            ]
          );
        }
        else {
          SQL::update('acp_serwery_update',[
              'sm' => $from->sourcemod_nazwa,
            ],
            $from->serwer_id,
            'serwer_id'
          );
        }
      }
    }
    if($from->metamod != '0'){
      $pliki = array();
      $path = "www/sourceupdate/".$from->metamod."-mm";
      if(is_dir($path)){
        $files = getDirContents($path);
        foreach ($files as $key => $value) {
          if(is_file($value)) {
            $nazwa = explode('/', $value);
            $nazwa = end($nazwa);
            $katalog = str_replace($nazwa, '', $value);
            $katalog = str_replace("www/sourceupdate/".$from->metamod."-sm", '', $katalog);

            array_push($pliki, (object)array('ftp_directory' => "$katalog", 'ftp_source_file_name' => "$value", 'ftp_dest_file_name'=> "$nazwa") );
          }
        }
        $pliki = json_encode($pliki);

        SQL::insert('acp_wgrywarka',[
            'serwer_id' => $from->serwer_id,
            'u_id' => $user,
            'nazwa' => $from->metamod_nazwa,
            'kat' => 'Metamod',
            'file' => $pliki,
          ]
        );

        Logs::log("Zlecono aktualizację $from->metamod_nazwa dla serwera ".$from->serwer_dane->mod."(ID: $from->serwer_id)");

        $serwer_update = SQL::one("SELECT `id` FROM `acp_serwery_update` WHERE `serwer_id` = $from->serwer_id");
        if(empty($serwer_update)){
          SQL::insert('acp_serwery_update',[
              'serwer_id' => $from->serwer_id,
              'mm' => $from->metamod_nazwa,
            ]
          );
        }
        else {
          SQL::update('acp_serwery_update',[
              'mm' => $from->metamod_nazwa,
            ],
            $from->serwer_id,
            'serwer_id'
          );
        }
      }
    }

    return Messe::array([
      'type' => 'success',
      'text' => "Dodano zlecenie na aktualizację plików Source. Więcej informacji znajdziesz w zakładce Wgrywarka"
    ]);
  }
}
 ?>
