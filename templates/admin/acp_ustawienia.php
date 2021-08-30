<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
</style>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
    <p><?= $Messe->show(); ?></p>
	</section>
  </div>
<?
$id = Get::int('id');

if(isset($_POST['edycja_podstwowe'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'acp_nazwa', 'value' => $_POST['acp_nazwa'] ],
    [ 'name' => 'acp_wersja', 'value' => $_POST['acp_wersja'] ],
    [ 'name' => 'acp_mail', 'value' => $_POST['acp_mail'] ],
    [ 'name' => 'acp_timezone', 'value' => $_POST['acp_timezone'] ],
    [ 'name' => 'acp_special_key', 'value' => $_POST['acp_special_key'] ],
    [ 'name' => 'acp_special_iv', 'value' => $_POST['acp_special_iv'] ],
    [ 'name' => 'logo_podstawowe', 'value' => $_POST['logo_podstawowe'] ],
    [ 'name' => 'logo_logo', 'value' => $_POST['logo_logo'] ],
    [ 'name' => 'logo_napis', 'value' => $_POST['logo_napis'] ],
    [ 'name' => 'logo_prawa', 'value' => $_POST['logo_prawa'] ],
    [ 'name' => 'dev_on', 'value' => $_POST['dev_on'] ],
    [ 'name' => 'dev_modul', 'value' => $_POST['dev_modul'] ],
  ]);
  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia podstawowe"
  ]);
	redirect("?x=$x");
}
if(isset($_POST['edycja_grafiki'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'tlo_sourcebans', 'value' => $_POST['tlo_sourcebans'] ],
    [ 'name' => 'tlo_hlstats', 'value' => $_POST['tlo_hlstats'] ],
    [ 'name' => 'tlo_adminlist', 'value' => $_POST['tlo_adminlist'] ],
    [ 'name' => 'tlo_changelog', 'value' => $_POST['tlo_changelog'] ],
    [ 'name' => 'tlo_galeria_map', 'value' => $_POST['tlo_galeria_map'] ],

    [ 'name' => 'logo_sourcebans', 'value' => $_POST['logo_sourcebans'] ],
    [ 'name' => 'logo_hlstats', 'value' => $_POST['logo_hlstats'] ],
    [ 'name' => 'logo_adminlist', 'value' => $_POST['logo_adminlist'] ],
    [ 'name' => 'logo_changelog', 'value' => $_POST['logo_changelog'] ],
    [ 'name' => 'logo_galeria_map', 'value' => $_POST['logo_galeria_map'] ],

    [ 'name' => 'acp_strona_www', 'value' => $_POST['acp_strona_www'] ],
    [ 'name' => 'acp_forum', 'value' => $_POST['acp_forum'] ],
    [ 'name' => 'acp_statystyki', 'value' => $_POST['acp_statystyki'] ],

    [ 'name' => 'media_fb', 'value' => $_POST['media_fb'] ],
    [ 'name' => 'media_insta', 'value' => $_POST['media_insta'] ],
    [ 'name' => 'media_steam', 'value' => $_POST['media_steam'] ],
    [ 'name' => 'media_yt', 'value' => $_POST['media_yt'] ],
    [ 'name' => 'danepub_menu_on', 'value' => $_POST['danepub_menu_on'] ],
  ]);
  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia podstawowe"
  ]);
	redirect("?x=$x");
}
if(isset($_POST['edycja_steam'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'acp_steam_api', 'value' => $_POST['acp_steam_api'] ],
    [ 'name' => 'acp_steam_time', 'value' => $_POST['acp_steam_time'] ],
    [ 'name' => 'acp_steam_count_limit', 'value' => $_POST['acp_steam_count_limit'] ],
  ]);

  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia steam"
  ]);

  redirect("?x=$x");
}
if(isset($_POST['edycja_cronjobs'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'acp_cron_key', 'value' => $_POST['acp_cron_key'] ],
    [ 'name' => 'cron_naglowek', 'value' => $_POST['cron_naglowek'] ],
    [ 'name' => 'cron_stopka', 'value' => $_POST['cron_stopka'] ],
  ]);
  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia prac zdalnych"
  ]);
  redirect("?x=$x");
}
if(isset($_POST['edycja_cronjobs_czasy'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'time_serwery', 'value' => $_POST['time_serwery'] ],
    [ 'name' => 'time_uslugi', 'value' => $_POST['time_uslugi'] ],
    [ 'name' => 'time_rangi', 'value' => $_POST['time_rangi'] ],
    [ 'name' => 'time_reklamy', 'value' => $_POST['time_reklamy'] ],
    [ 'name' => 'time_surl', 'value' => $_POST['time_surl'] ],
    [ 'name' => 'time_baza', 'value' => $_POST['time_baza'] ],
    [ 'name' => 'time_hextags', 'value' => $_POST['time_hextags'] ],
    [ 'name' => 'time_mapy', 'value' => $_POST['time_mapy'] ],
    [ 'name' => 'time_help_menu', 'value' => $_POST['time_help_menu'] ],
    [ 'name' => 'cron_file_list_mapy', 'value' => $_POST['cron_file_list_mapy'] ],
    [ 'name' => 'cron_file_list_pluginy', 'value' => $_POST['cron_file_list_pluginy'] ],
    [ 'name' => 'cron_optym_log_serwerow_limit', 'value' => $_POST['cron_optym_log_serwerow_limit'] ],
    [ 'name' => 'cron_optym_log_serwerow_day', 'value' => $_POST['cron_optym_log_serwerow_day'] ],
    [ 'name' => 'cron_optym_stare_uslugi_limit', 'value' => $_POST['cron_optym_stare_uslugi_limit'] ],
    [ 'name' => 'cron_optym_stare_uslugi_hour', 'value' => $_POST['cron_optym_stare_uslugi_hour'] ],
    [ 'name' => 'cron_optym_stare_wiadomosc_limit', 'value' => $_POST['cron_optym_stare_wiadomosc_limit'] ],
    [ 'name' => 'cron_optym_stare_wiadomosci_day', 'value' => $_POST['cron_optym_stare_wiadomosci_day'] ],
    [ 'name' => 'cron_optym_stare_rangi_limit', 'value' => $_POST['cron_optym_stare_rangi_limit'] ],
    [ 'name' => 'cron_optym_stare_rangi_hour', 'value' => $_POST['cron_optym_stare_rangi_hour'] ],
    [ 'name' => 'cron_optym_stare_reklamy_limit', 'value' => $_POST['cron_optym_stare_reklamy_limit'] ],
    [ 'name' => 'cron_optym_stare_reklamy_hour', 'value' => $_POST['cron_optym_stare_reklamy_hour'] ],
    [ 'name' => 'cron_optym_po_logach_optym_day', 'value' => $_POST['cron_optym_po_logach_optym_day'] ],
    [ 'name' => 'cron_optym_powiadomienia_odczytane', 'value' => $_POST['cron_optym_powiadomienia_odczytane'] ],
    [ 'name' => 'cron_optym_powiadomienia_usun', 'value' => $_POST['cron_optym_powiadomienia_usun'] ],
  ]);
  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia prac zdalnych"
  ]);
  redirect("?x=$x");
}
if(isset($_POST['e_galeria_map'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'GaleriaMap_api', 'value' => $_POST['GaleriaMap_api'] ],
    [ 'name' => 'GaleriaMap_wymiary_on', 'value' => $_POST['GaleriaMap_wymiary_on'] ],
    [ 'name' => 'GaleriaMap_wymiary_wysokosc', 'value' => $_POST['GaleriaMap_wymiary_wysokosc'] ],
    [ 'name' => 'GaleriaMap_wymiary_szerokosc', 'value' => $_POST['GaleriaMap_wymiary_szerokosc'] ],
    [ 'name' => 'GaleriaMap_znak_on', 'value' => $_POST['GaleriaMap_znak_on'] ],
    [ 'name' => 'GaleriaMap_znak_tekst', 'value' => $_POST['GaleriaMap_znak_tekst'] ],
    [ 'name' => 'GaleriaMap_znak_tekst_wielkosc', 'value' => $_POST['GaleriaMap_znak_tekst_wielkosc'] ],
    [ 'name' => 'GaleriaMap_znak_tekst_kolor', 'value' => $_POST['GaleriaMap_znak_tekst_kolor'] ],
    [ 'name' => 'galeria_map_noimage', 'value' => $_POST['galeria_map_noimage'] ],
  ]);
  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia galeri map"
  ]);
  redirect("?x=$x");
}
if(isset($_POST['e_raport_opiekuna'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'AdmRaport_on', 'value' => $_POST['AdmRaport_on'] ],
    [ 'name' => 'AdmRaport_start', 'value' => $_POST['AdmRaport_start'] ],
    [ 'name' => 'AdmRaport_stop', 'value' => $_POST['AdmRaport_stop'] ],
    [ 'name' => 'AdmRaport_AdmM_Nagroda', 'value' => $_POST['AdmRaport_AdmM_Nagroda'] ],
    [ 'name' => 'AdmRaport_AdmM_Nagroda_flagi', 'value' => $_POST['AdmRaport_AdmM_Nagroda_flagi'] ],
    [ 'name' => 'AdmRaport_AdmM_Nagroda_czas', 'value' => $_POST['AdmRaport_AdmM_Nagroda_czas'] ],
    [ 'name' => 'AdmRaport_AdmM_tag', 'value' => $_POST['AdmRaport_AdmM_tag'] ],
    [ 'name' => 'AdmRaport_AdmM_tag_tabela', 'value' => $_POST['AdmRaport_AdmM_tag_tabela'] ],
    [ 'name' => 'AdmRaport_AdmM_tag_say', 'value' => $_POST['AdmRaport_AdmM_tag_say'] ],
    [ 'name' => 'AdmRaport_AdmM_color_tag', 'value' => $_POST['AdmRaport_AdmM_color_tag'] ],
    [ 'name' => 'AdmRaport_AdmM_color_nick', 'value' => $_POST['AdmRaport_AdmM_color_nick'] ],
    [ 'name' => 'AdmRaport_AdmM_color_tekst', 'value' => $_POST['AdmRaport_AdmM_color_tekst'] ],
    [ 'name' => 'AdmRaport_AdmM_ranga_czas', 'value' => $_POST['AdmRaport_AdmM_ranga_czas'] ],
  ]);
  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia galeri map"
  ]);
  redirect("?x=$x");
}
if(isset($_POST['edycja_sourcebans'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'api_sb_db', 'value' => $_POST['api_sb_db'] ],
    [ 'name' => 'api_sb_host', 'value' => $_POST['api_sb_host'] ],
    [ 'name' => 'api_sb_user', 'value' => $_POST['api_sb_user'] ],
    [ 'name' => 'api_sb_pass', 'value' => $_POST['api_sb_pass'] ],
    [ 'name' => 'sb_optymalize_time', 'value' => $_POST['sb_optymalize_time'] ],
  ]);
  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia Sourcebans"
  ]);
  redirect("?x=$x");
}
if(isset($_POST['edycja_hlstats'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'api_hlx_db', 'value' => $_POST['api_hlx_db'] ],
    [ 'name' => 'api_hlx_host', 'value' => $_POST['api_hlx_host'] ],
    [ 'name' => 'api_hlx_user', 'value' => $_POST['api_hlx_user'] ],
    [ 'name' => 'api_hlx_pass', 'value' => $_POST['api_hlx_pass'] ],
    [ 'name' => 'hlx_optymalize_time', 'value' => $_POST['hlx_optymalize_time'] ],
    [ 'name' => 'hlx_top50', 'value' => $_POST['hlx_top50'] ],
    [ 'name' => 'hlx_top_rangi', 'value' => $_POST['hlx_top_rangi'] ],
    [ 'name' => 'hlx_top50_tag_tabela', 'value' => $_POST['hlx_top50_tag_tabela'] ],
    [ 'name' => 'hlx_top50_tag_say', 'value' => $_POST['hlx_top50_tag_say'] ],
    [ 'name' => 'hlx_top50_color_tag', 'value' => $_POST['hlx_top50_color_tag'] ],
    [ 'name' => 'hlx_top50_color_nick', 'value' => $_POST['hlx_top50_color_nick'] ],
    [ 'name' => 'hlx_top50_color_tekst', 'value' => $_POST['hlx_top50_color_tekst'] ],
    [ 'name' => 'hlx_ilosc', 'value' => $_POST['hlx_ilosc'] ],
  ]);
  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia Hlstats"
  ]);
  redirect("?x=$x");
}
if(isset($_POST['edycja_wpisy'])) {
  Controller('Ustawienia')->updateConf([
    [ 'name' => 'wpisy_ilosc_wpisow', 'value' => $_POST['wpisy_ilosc_wpisow'] ],
    [ 'name' => 'wpisy_ilosc_komentarzy', 'value' => $_POST['wpisy_ilosc_komentarzy'] ],
    [ 'name' => 'wpisy_last_login_on', 'value' => $_POST['wpisy_last_login_on'] ],
    [ 'name' => 'wpisy_last_login_liczba', 'value' => $_POST['wpisy_last_login_liczba'] ],
    [ 'name' => 'wpisy_nowy_dlugosc_tekstu', 'value' => $_POST['wpisy_nowy_dlugosc_tekstu'] ],
    [ 'name' => 'wpisy_nowy_dlugosc_tytulu_min', 'value' => $_POST['wpisy_nowy_dlugosc_tytulu_min'] ],
    [ 'name' => 'wpisy_nowy_dlugosc_tytulu_max', 'value' => $_POST['wpisy_nowy_dlugosc_tytulu_max'] ],
    [ 'name' => 'wpisy_komentarz_dlugosc_min', 'value' => $_POST['wpisy_komentarz_dlugosc_min'] ],
  ]);
  Messe::array([
    'type' => 'success',
    'text' => "Zaktualizowano ustawienia Wpisów"
  ]);
  redirect("?x=$x");
}
if(isset($_POST['wpisy_kategorie_zapisz'])){
  Controller('Ustawienia')->wpisySave();
  redirect("?x=$x&id=".$id."&co=edytuj");
}
if(isset($_POST['wpisy_kategorie_dodaj'])){
  Controller('Ustawienia')->wpisyStore();
  redirect("?x=$x&id=".$id."&co=edytuj");
}
if(isset($_POST['wpisy_kategorie_usun'])){
  Controller('Ustawienia')->wpisyDestroy();
  redirect("?x=$x&id=".$id."&co=edytuj");
}
if(isset($_POST['danepub_menu_edycja_zapisz'])){
  Controller('Ustawienia')->danepub_zapisz(json_decode($acp_system['danepub_menu_list']));
  redirect("?x=$x&id=".$id."");
}
if(isset($_POST['danepub_menu_dodaj'])){
  Controller('Ustawienia')->danepub_dodaj(json_decode($acp_system['danepub_menu_list']));
  redirect("?x=$x&id=".$id."");
}
if(isset($_POST['danepub_menu_edycja_usun'])){
  Controller('Ustawienia')->danepub_usun(json_decode($acp_system['danepub_menu_list']));
  redirect("?x=$x&id=".$id."");
}

$cronOption = [
  "0" => "Wyłączony",
  "60" => "co 60 sekund",
  "1800" => "co 30 minut",
  "3600" => "co 1 godzinę",
  "7200" => "co 2 godziny",
  "14400" => "co 4 godziny",
  "43200" => "co 12 godzin",
  "86400" => "raz 1 dobę"
];
?>

	<div class="row">
		<div class="col-xs-12">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#podstawowe" data-toggle="tab">Podstawowe</a></li>
          <li><a href="#dane_publiczne" data-toggle="tab">Dane Publiczne</a></li>
          <li><a href="#cron" data-toggle="tab">Prace Zdalne</a></li>
          <li><a href="#galeria_map" data-toggle="tab">Galeria Map</a></li>
          <li><a href="#raport_opiekuna" data-toggle="tab">Raport Opiekuna</a></li>
          <li><a href="#sourcebans" data-toggle="tab">SourceBans</a></li>
          <li><a href="#hlstats" data-toggle="tab">HLstatsX:CE</a></li>
          <li><a href="#wpisy" data-toggle="tab">Wpisy</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="podstawowe">
  				  <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Podstawowe<br><small>Główne ustawienia systemu</small></h3>
    					</div>
    					<div class="box-body">
                <form name='e_podstawowe' method='post'>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa</span><input class='form-control' type='text' name='acp_nazwa' value='<?= $acp_system['acp_nazwa']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Wersja</span><input class='form-control' type='text' name='acp_wersja' value='<?= $acp_system['acp_wersja']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Mail</span><input class='form-control' type='text' name='acp_mail' value='<?= $acp_system['acp_mail']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Timezone</span><input class='form-control' type='text' name='acp_timezone' value='<?= $acp_system['acp_timezone']; ?>'/></div></p>
                 <hr>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Specjalny KLucz</span><input class='form-control' type='text' name='acp_special_key' value='<?= $acp_system['acp_special_key']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Specjalny iv (Klucz)</span><input class='form-control' type='text' name='acp_special_iv' value='<?= $acp_system['acp_special_iv']; ?>'/></div></p>
                 <hr>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Logo Podstawowe</span><input class='form-control' type='text' name='logo_podstawowe' value='<?= $acp_system['logo_podstawowe']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Logo Logo</span><input class='form-control' type='text' name='logo_logo' value='<?= $acp_system['logo_logo']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Logo Napis</span><input class='form-control' type='text' name='logo_napis' value='<?= $acp_system['logo_napis']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Logo Prawa</span><input class='form-control' type='text' name='logo_prawa' value='<?= $acp_system['logo_prawa']; ?>'/></div></p>
                 <p><input name='edycja_podstwowe' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
            	</div>
  				  </div>
            <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Steam<br><small>Ustawienia dostępu do danych steam, czasu pobierania danych dla danego usera</small></h3>
    					</div>
    					<div class="box-body">
                <form name='e_steam' method='post'>
                 <p><div class='form-group input-group'><span class='input-group-addon'>API</span><input class='form-control' type='text' name='acp_steam_api' value='<?= $acp_system['acp_steam_api']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Czas co ile sekund aktualizować profile steam</span><input class='form-control' type='text' name='acp_steam_time' value='<?= $acp_system['acp_steam_time']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ilość aktualizowanych profili ACP</span><input class='form-control' type='text' name='acp_steam_count_limit' value='<?= $acp_system['acp_steam_count_limit']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja</span><input class='form-control' type='text' value='<?= $acp_system['cron_steam_update']; ?>' disabled /></div></p>
                 <p><input name='edycja_steam' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
    					</div>
  				  </div>
  				  <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Developer<br><small>Możliwość konfigracji, wyświetalnia błędów na danych podstronach systemu ACP</small></h3>
    					</div>
    					<div class="box-body">
                <form name='edycja_podstwowe' method='post'>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Włączony</span>
                    <select class="form-control" name='dev_on'>
                       <? if($acp_system['dev_on'] == 1) { echo '<option value="1">Tak</option><option value="0">Nie</option>'; } else { echo '<option value="0">Nie</option><option value="1">Tak</option>'; } ?>
                     </select>
                     </div>
                  </p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Zakładka</span><input class='form-control' type='text' name='dev_modul' value='<?= $acp_system['dev_modul']; ?>'/></div></p>
                 <p><input name='edycja_podstwowe' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
    					</div>
  				  </div>
          </div>
          <div class="tab-pane" id="dane_publiczne">
  				  <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Strony Publiczne<br><small>Ustawienia grafik na stronach publicznych. Brak powoduje nie wyświetlanie grafiki podstrony</small></h3>
              </div>
              <div class="box-body">
                <form name='e_grafiki' method='post'>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Logo Sourcebans</span><input class='form-control' type='text' name='logo_sourcebans' value='<?= $acp_system['logo_sourcebans']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Logo Hlstats</span><input class='form-control' type='text' name='logo_hlstats' value='<?= $acp_system['logo_hlstats']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Logo Admin List</span><input class='form-control' type='text' name='logo_adminlist' value='<?= $acp_system['logo_adminlist']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Logo Changelog</span><input class='form-control' type='text' name='logo_changelog' value='<?= $acp_system['logo_changelog']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Logo Galeria Map</span><input class='form-control' type='text' name='logo_galeria_map' value='<?= $acp_system['logo_galeria_map']; ?>'/></div></p>
                 <hr>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Tło Sourcebans</span><input class='form-control' type='text' name='tlo_sourcebans' value='<?= $acp_system['tlo_sourcebans']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Tło Hlstats</span><input class='form-control' type='text' name='tlo_hlstats' value='<?= $acp_system['tlo_hlstats']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Tło Admin List</span><input class='form-control' type='text' name='tlo_adminlist' value='<?= $acp_system['tlo_adminlist']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Tło Changelog</span><input class='form-control' type='text' name='tlo_changelog' value='<?= $acp_system['tlo_changelog']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Tło Galeria Map</span><input class='form-control' type='text' name='tlo_galeria_map' value='<?= $acp_system['tlo_galeria_map']; ?>'/></div></p>
                 <hr>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Link Strona</span><input class='form-control' type='text' name='acp_strona_www' value='<?= $acp_system['acp_strona_www']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Link Forum</span><input class='form-control' type='text' name='acp_forum' value='<?= $acp_system['acp_forum']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Link Statystki</span><input class='form-control' type='text' name='acp_statystyki' value='<?= $acp_system['acp_statystyki']; ?>'/></div></p>
                 <hr>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Media - Facebook</span><input class='form-control' type='text' name='media_fb' value='<?= $acp_system['media_fb']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Media - Instagram</span><input class='form-control' type='text' name='media_insta' value='<?= $acp_system['media_insta']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Media - Grupa Steam</span><input class='form-control' type='text' name='media_steam' value='<?= $acp_system['media_steam']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Media - Youtube</span><input class='form-control' type='text' name='media_yt' value='<?= $acp_system['media_yt']; ?>'/></div></p>
                 <p><input name='edycja_grafiki' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
            	</div>
          	</div>
  				  <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Menu Publiczne<br><small>Możliwość konfigracji, ustawienia menu dla stron publicznych. Dodania edycji</small></h3>
    					</div>
    					<div class="box-body">
                <p><div class='form-group input-group'><span class='input-group-addon'>Włączne</span>
                  <select class="form-control" name='danepub_menu_on'>
                     <? if($acp_system['danepub_menu_on'] == 1) { echo '<option value="1">Tak</option><option value="0">Nie</option>'; } else { echo '<option value="0">Nie</option><option value="1">Tak</option>'; } ?>
                   </select>
                   </div>
                </p>
                <p><input name='edycja_grafiki' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                <? $acp_system['danepub_menu_list'] = json_decode($acp_system['danepub_menu_list']); ?>
                <table class="table table-hover">
                  <tr>
                    <th>Nazwa</th>
                    <th>Link</th>
                    <th>Nowa Karta</th>
                    <th></th>
                  </tr>
                  <? foreach ($acp_system['danepub_menu_list'] as $key => $value) {?>
                  <tr>
                  <form method='post'>
                    <input type="hidden" name="danepub_pen" value="<?= json_encode($acp_system['danepub_menu_list'][$key]) ?>">
                    <input type="hidden" name="danepub_id" value="<?= $key ?>">
                    <td><input type="text" class="form-control" type="text" name="danepub_page" value="<?= $value->page ?>"></td>
                    <td><input type="text" class="form-control" type="text" name="danepub_link" value="<?= $value->link ?>" ></td>
                    <td><input type="checkbox" name="danepub_blank" <?= $value->blank ?>></td>
                    <td>
                      <input name='danepub_menu_edycja_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                      <input name='danepub_menu_edycja_usun' type="submit" class="btn btn-danger" value='Usuń'>
                    </td>
                  </form>
                  </tr>
                  <? } ?>
                  <tr>
                  <form method='post'>
                    <td><input type="text" class="form-control" type="text" name="danepub_page"></td>
                    <td><input type="text" class="form-control" type="text" name="danepub_link"></td>
                    <td><input type="checkbox" name="danepub_blank"></td>
                    <td>
                      <input name='danepub_menu_dodaj' type="submit" class="btn btn-default" value='Dodaj'>
                    </td>
                  </form>
                  </tr>
                </table>
    					</div>
  				  </div>
          </div>
          <div class="tab-pane" id="cron">
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Dostęp<br><small>Kody dostępu do skryptu, oraz wszelkie zabezpieczenia przed obciążeniem</small></h3>
              </div>
              <div class="box-body">
                <form name='e_cronjobs' method='post'>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Kod dostępu</span><input class='form-control' type='text' name='acp_cron_key' value='<?= $acp_system['acp_cron_key']; ?>'/></div></p>
                  <p><input name='edycja_cronjobs' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
              </div>
            </div>
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Cronjobs: Prace Zdalne<br><small>Ograniczenia czasowe wykonywania aktualizacji plików danego modułu</small></h3>
              </div>
              <div class="box-body">
                <form name='e_cronjobs' method='post'>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Serwery</span>
                   <?= optionHtml($cronOption, ['name' => 'time_serwery', 'value' => $acp_system['time_serwery']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Serwerów</span><input class='form-control' value='<?= $acp_system['cron_serwery']; ?>' disabled /></div></p>

                 <p><div class='form-group input-group'><span class='input-group-addon'>Usługi [admins_simple]</span>
                   <?= optionHtml($cronOption, ['name' => 'time_uslugi', 'value' => $acp_system['time_uslugi']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Usług</span><input class='form-control' value='<?= $acp_system['cron_uslugi']; ?>' disabled /></div></p>

                 <p><div class='form-group input-group'><span class='input-group-addon'>Rangi [HexTags]</span>
                   <?= optionHtml($cronOption, ['name' => 'time_hextags', 'value' => $acp_system['time_hextags']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Rang [HexTags]</span><input class='form-control' value='<?= $acp_system['cron_hextags']; ?>' disabled /></div></p>

                 <p><div class='form-group input-group'><span class='input-group-addon'>Reklamy</span>
                   <?= optionHtml($cronOption, ['name' => 'time_reklamy', 'value' => $acp_system['time_reklamy']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Reklam</span><input class='form-control' value='<?= $acp_system['cron_reklamy']; ?>' disabled /></div></p>

                 <p><div class='form-group input-group'><span class='input-group-addon'>Bazy Danych</span>
                   <?= optionHtml($cronOption, ['name' => 'time_baza', 'value' => $acp_system['time_baza']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Baz Danych</span><input class='form-control' value='<?= $acp_system['cron_baza']; ?>' disabled /></div></p>

                 <p><div class='form-group input-group'><span class='input-group-addon'>Listy Map</span>
                   <?= optionHtml($cronOption, ['name' => 'time_mapy', 'value' => $acp_system['time_mapy']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Listy Map</span><input class='form-control' value='<?= $acp_system['cron_mapy']; ?>' disabled /></div></p>

                 <p><div class='form-group input-group'><span class='input-group-addon'>Cvary</span>
                   <?= optionHtml($cronOption, ['name' => 'time_cvary', 'value' => $acp_system['time_cvary']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Cvarów</span><input class='form-control' value='<?= $acp_system['cron_cvary']; ?>' disabled /></div></p>

                 <p><div class='form-group input-group'><span class='input-group-addon'>Help Menu</span>
                   <?= optionHtml($cronOption, ['name' => 'time_help_menu', 'value' => $acp_system['time_help_menu']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Help Menu</span><input class='form-control' value='<?= $acp_system['cron_help_menu']; ?>' disabled /></div></p>

                 <p><div class='form-group input-group'><span class='input-group-addon'>Lista Map Serwerów</span>
                   <?= optionHtml($cronOption, ['name' => 'cron_file_list_mapy_time', 'value' => $acp_system['cron_file_list_mapy_time']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Listy Map</span><input class='form-control' value='<?= $acp_system['cron_file_list_mapy']; ?>' disabled /></div></p>

                 <p><div class='form-group input-group'><span class='input-group-addon'>Lista Pluginów Serwerów</span>
                   <?= optionHtml($cronOption, ['name' => 'cron_file_list_pluginy_time', 'value' => $acp_system['cron_file_list_pluginy_time']]); ?></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia aktualizacja Listy Pluginów</span><input class='form-control' value='<?= $acp_system['cron_file_list_pluginy']; ?>' disabled /></div></p>

                 <p><input name='edycja_cronjobs_czasy' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
              </div>
            </div>
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Stylistyka<br><small>Wygląd nagłówka jak i stopki edytowanych plików</small></h3>
              </div>
              <div class="box-body">
                <form name='e_cronjobs' method='post'>
                  <label>Nagłówek</label>
                  <p class="help-block">Oznaczenie <b>/n</b> jest oznaczeniem nowej lini</p>
                  <textarea name="cron_naglowek" class="form-control" rows="4"><?= stripcslashes($acp_system['cron_naglowek'])?></textarea>
                  <label>Stopka</label>
                  <p class="help-block">Wartość <b>$czas</b> odpowiada za wyświetlany czas w formacie MYSQL, natomiast <b>/n</b> jest oznaczeniem nowej lini</p>
                  <textarea name="cron_stopka" class="form-control" rows="4"><?= stripcslashes($acp_system['cron_stopka']); ?></textarea>

                  <p><input name='edycja_cronjobs' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
              </div>
            </div>
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Cronjobs: Optymalizator<br><small>Ten cronjob wykonuje się raz na godzinę i kasuje logi, stare usługi czy zbęde dane statystyczne</small></h3>
              </div>
              <div class="box-body">
                <form name='e_cronjobs' method='post'>
                 <p>Logi Serwerów:</p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Starsze logi niż</span><input class='form-control' type='number' name='cron_optym_log_serwerow_day' value='<?= $acp_system['cron_optym_log_serwerow_day']; ?>'/><span class="input-group-addon">dni</span></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Limit kasownych logów</span><input class='form-control' type='number' name='cron_optym_log_serwerow_limit' value='<?= $acp_system['cron_optym_log_serwerow_limit']; ?>'/></div></p>

                 <p>Rangi:</p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Wygasłe Rangi</span><input class='form-control' type='number' name='cron_optym_stare_rangi_hour' value='<?= $acp_system['cron_optym_stare_rangi_hour']; ?>'/><span class="input-group-addon">godzin temu</span></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Limit kasowanych rang</span><input class='form-control' type='number' name='cron_optym_stare_rangi_limit' value='<?= $acp_system['cron_optym_stare_rangi_limit']; ?>'/></div></p>

                 <p>Reklamy:</p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Wygasłe Reklamy</span><input class='form-control' type='number' name='cron_optym_stare_reklamy_hour' value='<?= $acp_system['cron_optym_stare_reklamy_hour']; ?>'/><span class="input-group-addon">godzin temu</span></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Limit kasowanych reklam</span><input class='form-control' type='number' name='cron_optym_stare_reklamy_limit' value='<?= $acp_system['cron_optym_stare_reklamy_limit']; ?>'/></div></p>

                 <p>Usługi:</p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Wygasłe Usługi</span><input class='form-control' type='number' name='cron_optym_stare_uslugi_hour' value='<?= $acp_system['cron_optym_stare_uslugi_hour']; ?>'/><span class="input-group-addon">godzin temu</span></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Limit kasowanych usług</span><input class='form-control' type='number' name='cron_optym_stare_uslugi_limit' value='<?= $acp_system['cron_optym_stare_uslugi_limit']; ?>'/></div></p>

                 <p>Wiadomości:</p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Kosz Starsze niż</span><input class='form-control' type='number' name='cron_optym_stare_wiadomosci_day' value='<?= $acp_system['cron_optym_stare_wiadomosci_day']; ?>'/><span class="input-group-addon">dni</span></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Limit kasowanych usług</span><input class='form-control' type='number' name='cron_optym_stare_wiadomosc_limit' value='<?= $acp_system['cron_optym_stare_wiadomosc_limit']; ?>'/></div></p>

                 <p>Powiadomienia</p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Starsze niż</span><input class='form-control' type='number' name='cron_optym_powiadomienia_odczytane' value='<?= $acp_system['cron_optym_powiadomienia_odczytane']; ?>'/><span class="input-group-addon">dni oznacz jako odczytane</span></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Starsze niż</span><input class='form-control' type='number' name='cron_optym_powiadomienia_usun' value='<?= $acp_system['cron_optym_powiadomienia_usun']; ?>'/><span class="input-group-addon">dni skasuj</span></div></p>


                 <p><input name='edycja_cronjobs_czasy' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
              </div>
            </div>

            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Cronjobs: Statystki<br><small>Ten cronjob ma za zadanie pobrać dane statystyczne z innych stron oraz przeliczyć aktualne</small></h3>
              </div>
              <div class="box-body">
                <form name='e_cronjobs' method='post'>
                 <p>GoSetti:</p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnio</span><input class='form-control' value='<?= $acp_system['acp_cron_stats_gosetti']; ?>' disabled/></div></p>
                 <p>HLStats:</p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnio</span><input class='form-control' value='<?= $acp_system['acp_cron_stats_hlstats']; ?>' disabled/></div></p>
                 <p>GameTracker:</p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnio</span><input class='form-control' value='<?= $acp_system['acp_cron_stats_gametracker']; ?>' disabled/></div></p>
                </from>
              </div>
            </div>

          </div>
          <div class="tab-pane" id="galeria_map">
            <div class="box box-solid">
  					<div class="box-header with-border">
  					  <h3 class="box-title">Galeria Map<br><small>Ustawienia Podstawowe</small></h3>
  					</div>
  					<div class="box-body">
              <form name='e_galeria_map' method='post'>
               <p><div class='form-group input-group'><span class='input-group-addon'>API Imgur</span><input class='form-control' type='text' name='GaleriaMap_api' value='<?= $acp_system['GaleriaMap_api']; ?>'/></div></p>
               <hr>
               <p><div class='form-group input-group'><span class='input-group-addon'>Zmiana wielkości Obrazka</span>
                 <select class="form-control" name='GaleriaMap_wymiary_on'>
                    <? if($acp_system['GaleriaMap_wymiary_on'] == 1) { echo '<option value="1">Tak</option><option value="0">Nie</option>'; } else { echo '<option value="0">Nie</option><option value="1">Tak</option>'; } ?>
                  </select>
                  </div>
               </p>

               <p><div class='form-group input-group'><span class='input-group-addon'>Wysokość Obrazka</span><input class='form-control' type='number' name='GaleriaMap_wymiary_wysokosc' value='<?= $acp_system['GaleriaMap_wymiary_wysokosc']; ?>'/></div></p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Szerokość Obrazka</span><input class='form-control' type='number' name='GaleriaMap_wymiary_szerokosc' value='<?= $acp_system['GaleriaMap_wymiary_szerokosc']; ?>'/></div></p>
               <hr>
               <p><div class='form-group input-group'><span class='input-group-addon'>Znak Wodny</span>
                 <select class="form-control" name='GaleriaMap_znak_on'>
                    <? if($acp_system['GaleriaMap_znak_on'] == 1) { echo '<option value="1">Tak</option><option value="0">Nie</option>'; } else { echo '<option value="0">Nie</option><option value="1">Tak</option>'; } ?>
                  </select>
                  </div>
               </p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Tekst znaku wodnego</span><input class='form-control' type='text' name='GaleriaMap_znak_tekst' value='<?= $acp_system['GaleriaMap_znak_tekst']; ?>'/></div></p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Wielkość tekstu znaku wodnego</span><input class='form-control' type='number' name='GaleriaMap_znak_tekst_wielkosc' value='<?= $acp_system['GaleriaMap_znak_tekst_wielkosc']; ?>'/></div></p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Kolor tekstu znaku wodnego</span><input class='form-control' type='text' name='GaleriaMap_znak_tekst_kolor' value='<?= $acp_system['GaleriaMap_znak_tekst_kolor']; ?>'/></div></p>
               <p class="help-block">Dostępne kolory to: white, black, grey</p>
               <hr>
               <p><div class='form-group input-group'><span class='input-group-addon'>Obrazek Braku Mapy</span><input class='form-control' type='text' name='galeria_map_noimage' value='<?= $acp_system['galeria_map_noimage']; ?>'/></div></p>
               <p><input name='e_galeria_map' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
              </from>
          	</div>
  				  </div>
          </div>
          <div class="tab-pane" id="raport_opiekuna">
            <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Raport Opiekna<br><small>Ustawienia składania raportu opiekuna</small></h3>
    					</div>
    					<div class="box-body">
                <form name='e_raport_opiekuna' method='post'>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Włączony</span>
                    <select class="form-control" name='AdmRaport_on'>
                       <? if($acp_system['AdmRaport_on'] == 1) { echo '<option value="1">Tak</option><option value="0">Nie</option>'; } else { echo '<option value="0">Nie</option><option value="1">Tak</option>'; } ?>
                     </select>
                     </div>
                  </p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Raport Początek</span><input class='form-control' type='text' name='AdmRaport_start' value='<?= $acp_system['AdmRaport_start']; ?>'/></div></p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Raport Koniec</span><input class='form-control' type='text' name='AdmRaport_stop' value='<?= $acp_system['AdmRaport_stop']; ?>'/></div></p>
                 <p><input name='e_raport_opiekuna' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
            	</div>
  				  </div>
            <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Najlepszy Admin<br><small>Ustawienia nagradzania najlepszego admina wyznaczonego przez Opiekuna</small></h3>
    					</div>
    					<div class="box-body">
                <form name='e_raport_opiekuna' method='post'>
                  <p><div class='form-group input-group'><span class='input-group-addon'>[USŁUGA] Włączna</span>
                    <select class="form-control" name='AdmRaport_AdmM_Nagroda'>
                       <? if($acp_system['AdmRaport_AdmM_Nagroda'] == 1) { echo '<option value="1">Tak</option><option value="0">Nie</option>'; } else { echo '<option value="0">Nie</option><option value="1">Tak</option>'; } ?>
                     </select>
                     </div>
                  </p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>[USŁUGA] Flaga Nagrody</span>
                    <select class="form-control" name='AdmRaport_AdmM_Nagroda_flagi'>
                      <option value="<?= $acp_system['AdmRaport_AdmM_Nagroda_flagi'] ?>"><?= SQL::one("SELECT `nazwa` FROM `acp_uslugi_rodzaje` WHERE `id` = ".$acp_system['AdmRaport_AdmM_Nagroda_flagi']." LIMIT 1"); ?></option>
                      <?
                      $AdmRaport_AdmM_Nagroda_uslugi = SQL::all("SELECT `id`, `nazwa` FROM `acp_uslugi_rodzaje`");
                      foreach($AdmRaport_AdmM_Nagroda_uslugi as $value){
                        if( $value->id != $acp_system['AdmRaport_AdmM_Nagroda_flagi']){
                      ?>

                        <option value="<?= $value->id ?>"><?= $value->nazwa ?></option>
                      <? }
                      } ?>
                     </select>
                     </div>
                  </p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>[USŁUGA]Czas</span><input class='form-control' type='text' name='AdmRaport_AdmM_Nagroda_czas' value='<?= $acp_system['AdmRaport_AdmM_Nagroda_czas']; ?>'/></div></p>

                  <hr>

                  <p><div class='form-group input-group'><span class='input-group-addon'>[RANGA] Włączna</span>
                    <select class="form-control" name='AdmRaport_AdmM_tag'>
                       <? if($acp_system['AdmRaport_AdmM_tag'] == 1) { echo '<option value="1">Tak</option><option value="0">Nie</option>'; } else { echo '<option value="0">Nie</option><option value="1">Tak</option>'; } ?>
                     </select>
                     </div>
                  </p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>[RANGA]Ranga Tabela</span><input class='form-control' type='text' name='AdmRaport_AdmM_tag_tabela' value='<?= $acp_system['AdmRaport_AdmM_tag_tabela']; ?>'/></div></p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>[RANGA]Ranga Chat</span><input class='form-control' type='text' name='AdmRaport_AdmM_tag_say' value='<?= $acp_system['AdmRaport_AdmM_tag_say']; ?>'/></div></p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>[RANGA]Ranga Kolor Tag</span><input class='form-control' type='text' name='AdmRaport_AdmM_color_tag' value='<?= $acp_system['AdmRaport_AdmM_color_tag']; ?>'/></div></p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>[RANGA]Ranga Kolor Nick</span><input class='form-control' type='text' name='AdmRaport_AdmM_color_nick' value='<?= $acp_system['AdmRaport_AdmM_color_nick']; ?>'/></div></p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>[RANGA]Ranga Kolor Tekst</span><input class='form-control' type='text' name='AdmRaport_AdmM_color_tekst' value='<?= $acp_system['AdmRaport_AdmM_color_tekst']; ?>'/></div></p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>[RANGA] Czas</span><input class='form-control' type='text' name='AdmRaport_AdmM_ranga_czas' value='<?= $acp_system['AdmRaport_AdmM_ranga_czas']; ?>'/></div></p>

                  <p><input name='e_raport_opiekuna' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
            	</div>
  				  </div>
          </div>
          <div class="tab-pane" id="sourcebans">
            <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Sourcebans<br><small>Połaczenie bazy danych</small></h3>
    					</div>
    					<div class="box-body">
                <form name='e_sourcebans' method='post'>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Host</span><input class='form-control' type='text' name='api_sb_host' value='<?= $acp_system['api_sb_host']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Bazy Danych</span><input class='form-control' type='text' name='api_sb_db' value='<?= $acp_system['api_sb_db']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Użytkownik</span><input class='form-control' type='text' name='api_sb_user' value='<?= $acp_system['api_sb_user']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Hasło</span><input class='form-control' type='password' name='api_sb_pass' autocomplete='new-password' value='<?= $acp_system['api_sb_pass']; ?>'/></div></p>
                 <p><input name='edycja_sourcebans' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
            	</div>
  				  </div>
            <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Optymalizacja<br><small>Optymalizacja mysql</small></h3>
    					</div>
    					<div class="box-body">
                <form name='e_sourcebans' method='post'>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Czas</span>
                    <?= optionHtml($cronOption, ['name' => 'sb_optymalize_time', 'value' => $acp_system['sb_optymalize_time']]); ?></div></p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia optymalizacja</span><input class='form-control' value='<?= $acp_system['sb_optymalize_last']; ?>' disabled /></div></p>
                 <p><input name='edycja_sourcebans' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
            	</div>
  				  </div>
          </div>
          <div class="tab-pane" id="hlstats">
            <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">HLstatsX<br><small>Połaczenie bazy danych</small></h3>
    					</div>
    					<div class="box-body">
                <form name='edycja_hlstats' method='post'>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Host</span><input class='form-control' type='text' name='api_hlx_host' value='<?= $acp_system['api_hlx_host']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Bazy Danych</span><input class='form-control' type='text' name='api_hlx_db' value='<?= $acp_system['api_hlx_db']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Użytkownik</span><input class='form-control' type='text' name='api_hlx_user' value='<?= $acp_system['api_hlx_user']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Hasło</span><input class='form-control' type='password' name='api_hlx_pass' autocomplete='new-password' value='<?= $acp_system['api_hlx_pass']; ?>'/></div></p>
                 <p><input name='edycja_hlstats' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
            	</div>
  				  </div>
            <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Optymalizacja<br><small>Optymalizacja mysql</small></h3>
    					</div>
    					<div class="box-body">
                <form name='edycja_hlstats' method='post'>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Czas</span>
                    <?= optionHtml($cronOption, ['name' => 'hlx_optymalize_time', 'value' => $acp_system['hlx_optymalize_time']]); ?></div></p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia optymalizacja</span><input class='form-control' value='<?= $acp_system['hlx_optymalize_last']; ?>' disabled /></div></p>
                 <p><input name='edycja_hlstats' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
            	</div>
  				  </div>
            <div class="box box-solid">
    					<div class="box-header with-border">
    					  <h3 class="box-title">Statystki Top50<br><small>Ustawienia pobierania tabel, konwersji nadania nagrody</small></h3>
    					</div>
    					<div class="box-body">
                <form name='edycja_hlstats' method='post'>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Włączony</span>
                    <select class="form-control" name='hlx_top50'>
                       <? if($acp_system['hlx_top50'] == 1) { echo '<option value="1">Tak</option><option value="0">Nie</option>'; } else { echo '<option value="0">Nie</option><option value="1">Tak</option>'; } ?>
                     </select>
                     </div>
                  </p>
                  <p><div class='form-group input-group'><span class='input-group-addon'>Nagrody TOPx</span>
                    <select class="form-control" name='hlx_top_rangi'>
                       <? if($acp_system['hlx_top_rangi'] == 1) { echo '<option value="1">Tak</option><option value="0">Nie</option>'; } else { echo '<option value="0">Nie</option><option value="1">Tak</option>'; } ?>
                     </select>
                     </div>
                  </p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Tag Tabela</span><input class='form-control' type='text' name='hlx_top50_tag_tabela' value='<?= $acp_system['hlx_top50_tag_tabela']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Tag Chat</span><input class='form-control' type='text' name='hlx_top50_tag_say' value='<?= $acp_system['hlx_top50_tag_say']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Tagu</span><input class='form-control' type='text' name='hlx_top50_color_tag' value='<?= $acp_system['hlx_top50_color_tag']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Nicku</span><input class='form-control' type='text' name='hlx_top50_color_nick' value='<?= $acp_system['hlx_top50_color_nick']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Tekstu</span><input class='form-control' type='text' name='hlx_top50_color_tekst' value='<?= $acp_system['hlx_top50_color_tekst']; ?>'/></div></p>
                 <p><div class='form-group input-group'><span class='input-group-addon'>Ilość Nagród</span><input class='form-control' type='number' min="1" max="10" name='hlx_ilosc' value='<?= $acp_system['hlx_ilosc']; ?>'/></div></p>
                 <p><input name='edycja_hlstats' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
                </from>
            	</div>
  				  </div>
          </div>
          <div class="tab-pane" id="wpisy">
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Wpisy<br><small>Ustawienia podstawowe</small></h3>
              </div>
              <div class="box-body">
              <form name='edycja_wpisy' method='post'>
               <p>Wygląd</p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Ilość wpisów na stronie głównej</span><input class='form-control' type='number' name='wpisy_ilosc_wpisow' value='<?= $acp_system['wpisy_ilosc_wpisow']; ?>'/></div></p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Liczba komentarzy pod wpisem na stronie głównej</span><input class='form-control' type='number' name='wpisy_ilosc_komentarzy' value='<?= $acp_system['wpisy_ilosc_komentarzy']; ?>'/></div></p>

               <p>Ostatnio Logowani<br><small>Navbar po prawej stronie</small></p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Włączony</span>
                 <select class="form-control" name='wpisy_last_login_on'>
                    <?
                    if($acp_system['wpisy_last_login_on'] == 1) { $wpisy_last_login_on = '<option value="1">Tak</option><option value="0">Nie</option>'; } else { $wpisy_last_login_on = '<option value="0">Nie</option><option value="1">Tak</option>'; }
                    echo $wpisy_last_login_on;
                    ?>
                  </select>
                  </div>
               </p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Liczba osob</span><input class='form-control' type='number' name='wpisy_last_login_liczba' value='<?= $acp_system['wpisy_last_login_liczba']; ?>'/></div></p>

               <p>Ograniczenia<br><small>Minimalna oraz maksymalna ilośc znaków</small></p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Minimalna długość komentarza</span><input class='form-control' type='number' name='wpisy_komentarz_dlugosc_min' value='<?= $acp_system['wpisy_komentarz_dlugosc_min']; ?>'/></div></p>
               <p><div class='form-group input-group'><span class='input-group-addon'> Minimalna długość tytułu</span><input class='form-control' type='number' name='wpisy_nowy_dlugosc_tytulu_min' value='<?= $acp_system['wpisy_nowy_dlugosc_tytulu_min']; ?>'/></div></p>
               <p><div class='form-group input-group'><span class='input-group-addon'> Maksymalna długość tytułu</span><input class='form-control' type='number' name='wpisy_nowy_dlugosc_tytulu_max' value='<?= $acp_system['wpisy_nowy_dlugosc_tytulu_max']; ?>'/></div></p>
               <p><div class='form-group input-group'><span class='input-group-addon'> Minimalna długość tekstu wpisu</span><input class='form-control' type='number' name='wpisy_nowy_dlugosc_tekstu' value='<?= $acp_system['wpisy_nowy_dlugosc_tekstu']; ?>'/></div></p>

               <p><input name='edycja_wpisy' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
              </from>
            </div>
            </div>
            <div class="box box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Kategorie<br><small>Możliwośc edycji, usunięcie oraz dodania kategorii tematycznych wpisów</small></h3>
              </div>
              <div class="box-body">
              <table class="table table-hover">
                <tr>
                  <th width="5%">ID</th>
                  <th>Nazwa</th>
                  <th></th>
                </tr>
                <?
                $wpisy_kategorie_q = SQL::all("SELECT `id`, `nazwa` FROM `acp_wpisy_kategorie`; ");
                foreach ($wpisy_kategorie_q as $wpisy_kategorie) { ?>
                <tr>
                <form name='edycja_from_menu' method='post' action='<? echo "?x=$x"; ?>'>
                  <input type="hidden" name="wpisy_kategorie_id" value="<? echo $wpisy_kategorie->id ?>">
                  <td><input type="text" class="form-control" type="text" value="<? echo $wpisy_kategorie->id ?>" disabled></td>
                  <td><input type="text" class="form-control" type="text" name="wpisy_kategorie_nazwa" value="<? echo $wpisy_kategorie->nazwa ?>" ></td>
                  <td>
                    <input name='wpisy_kategorie_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                    <input name='wpisy_kategorie_usun' type="submit" class="btn btn-danger" value='Usuń'>
                  </td>
                </form>
                </tr>
                <? } ?>
                <tr>
                <form name='edycja_from_menu_add' method='post' action='<? echo "?x=$x"; ?>'>
                  <td><input type="text" class="form-control" value="-" disabled></td>
                  <td><input type="text" type="text" name="wpisy_kategorie_nazwa" class="form-control"></td>
                  <td>
                    <input name='wpisy_kategorie_dodaj' type="submit" class="btn btn-default" value='Dodaj'>
                  </td>
                </form>
                </tr>
              </table>
            </div>
              <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj-serwer"><i class="fa fa-plus"></i> Dodaj</button>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	</div>

</section>
</div>
<? require_once("./templates/user/stopka.php");  ?>


<div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="./www/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.responsive.js"></script>
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
<!-- SZABLONY -->
<script src="./www/dist/js/demo.js"></script>
<!-- page script -->
<?= Model('DataTable')->table([
  [
    'name' => '#wpisy_kategorie',
    'sort' => '0',
    'sort_type' => 'desc'
  ]
]); ?>
</body>
</html>
