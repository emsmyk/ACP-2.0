<?php
class PluginyFileController
{
  public function __construct()
  {
    $this->db = DB::getInstance();

    $this->id_pliku = Get::int('id_pliku');
    $this->user = User::get();
  }

  function store($dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'gdzie' => 'reg'
    ],[
      'gdzie.reg' => 'Należy wskazać lokalizację..'
    ]);

    $from->nazwa_pluginu = $this->db->get_row("SELECT `id`, `nazwa` FROM `acp_pluginy` WHERE `id` = $from->id LIMIT 1", true);

    $file_name = $_FILES['plik']['name'];
    $file_size = $_FILES['plik']['size'];
    $file_tmp = $_FILES['plik']['tmp_name'];
    $file_ext = strtolower(end(explode('.',$file_name)));
    $file_name = (empty($from->nazwa)) ? $file_name : $from->nazwa;

    if(!is_uploaded_file($file_tmp)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Błąd podczas przesyłania danych"
      ]);
    }
    if ($file_size > 1362150) {
      return Messe::array([
        'type' => 'warning',
        'text' => "Plik jest za duży. Makysmalna wielkość to 1362150 bajtów"
      ]);
    }

    $folder = File::is_dir($from->id, "www/server_plugins/", true);
    $nazwa_pliku = generujLosowyCiag(25);

    $katalog_nazwa_pliku = $folder.'/'.$nazwa_pliku.'.'.$file_ext;
    move_uploaded_file($file_tmp, $katalog_nazwa_pliku);

    $this->db->insert('acp_pluginy_pliki',[
        'plugin_id' => $from->id,
        'ftp_directory' => $from->gdzie,
        'ftp_source_file_name' => $katalog_nazwa_pliku,
        'ftp_dest_file_name' => $file_name
      ]
    );

    Logs::log("Wgrano nowy plik $file_name (ID: ".$this->db->lastid().") dla pluginu $nazwa_pluginu->nazwa (ID: $from->id)", "?x=pluginy&id=$from->id");
  }

  function update($dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'nazwa' => 'reg'
    ],[
      'nazwa.reg' => 'Pole nazwa jest wymagane'
    ]);

    $from->kod_zrodlowy = ($from->kod_zrodlowy == 'on') ? "'1'" : 'NULL';
    $from->starsza_wersja = ($from->starsza_wersja == 'on') ? "'1'" : 'NULL';

    $dane = $this->db->get_row("SELECT `plugin_id` FROM `acp_pluginy_pliki` WHERE `id` = $from->id LIMIT 1", true);
    $from->nazwa_pluginu = $this->db->get_row("SELECT `id`, `nazwa` FROM `acp_pluginy` WHERE `id` = $dane->plugin_id LIMIT 1", true);

    $this->db->update(
    'acp_pluginy_pliki',
    [
      'ftp_directory' => $from->gdzie_wgrac,
      'ftp_dest_file_name' => $from->nazwa,
      'starsza_wersja' => $from->starsza_wersja,
      'kod_zrodlowy' => $from->kod_zrodlowy
    ], [
      'id' => $from->id
    ]);

    Logs::log("Zedytowano plik $from->nazwa (ID: $from->id) z pluginu $nazwa_pluginu->nazwa (ID: $nazwa_pluginu->id)", "?x=pluginy&id=$nazwa_pluginu->id");
  }

  function destroy($dostep)
  {
    Permission::check($dostep);

    $dane = $this->db->get_row("SELECT `plugin_id`, `ftp_dest_file_name`, `ftp_source_file_name` FROM `acp_pluginy_pliki` WHERE `id` = $this->id_pliku LIMIT 1", true);
    $nazwa_pluginu = $this->db->get_row("SELECT `id`, `nazwa` FROM `acp_pluginy` WHERE `id` = $dane->plugin_id LIMIT 1", true);

    unlink($dane->ftp_source_file_name);
    $this->db->query("DELETE FROM `acp_pluginy_pliki` WHERE `id` = $this->id_pliku");

    Logs::log("Usunięto plik $dane->ftp_dest_file_name (ID: $this->id_pliku) z pluginu $nazwa_pluginu->nazwa (ID: $nazwa_pluginu->id)", "?x=pluginy");
  }
}
 ?>
