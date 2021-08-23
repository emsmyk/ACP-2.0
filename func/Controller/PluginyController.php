<?
class PluginyController
{
   public function __construct()
   {
     $this->id = Get::int('id');
     $this->co = Get::string('co');

     $this->user = User::get();

     $this->db = DB::getInstance();
   }

   function index()
   {
     return $this->db->get_results("SELECT *,(SELECT `login` FROM `acp_users` WHERE `user` = `u_id` LIMIT 1) AS login FROM `acp_pluginy` ORDER BY `id` DESC", true);
   }

   function store($dostep)
   {
     Permission::check($dostep);

     $from = From::check([
       'nazwa' => 'reg'
     ],[
       'nazwa.reg' => 'Pole nazwa jest wymagane'
     ]);

     $last_insert = $this->db->insert('acp_pluginy',[
       'nazwa' => $from->nazwa,
       'opis' => $from->opis,
       'cvary' => $from->cvary,
       'notatki' => $from->notatki,
       'u_id' => $this->user
     ]);

     Logs::log("Dodano nowy plugin $from->nazwa (ID: $last_insert)", "?x=pluginy");
   }

   function edit()
   {
     $dane = $this->db->get_row("SELECT *,
       (SELECT `login` FROM `acp_users` WHERE `user` = `u_id` LIMIT 1) AS `login`
     FROM `acp_pluginy` WHERE `id` = $this->id", true);

     $dane->opis = (empty($dane->opis)) ? 'brak': $dane->opis;
     $dane->cvary = (empty($dane->cvary)) ? 'brak': $dane->cvary;
     $dane->notatki = (empty($dane->notatki)) ? 'brak': $dane->notatki;

     $dane->ilosc_plikow = $this->db->get_row("SELECT COUNT(`id`) FROM `acp_pluginy_pliki` WHERE `plugin_id` = $dane->id ")[0];

     $dane->pliki = $this->db->get_results("SELECT * FROM `acp_pluginy_pliki` WHERE `plugin_id` = $dane->id ORDER BY `starsza_wersja`, `kod_zrodlowy` ASC", true);

     return $dane;
   }

   function update($dostep)
   {
     Permission::check($dostep);

     $from = From::check([
       'nazwa' => 'reg'
     ],[
       'nazwa.reg' => 'Pole nazwa jest wymagane'
     ]);

     $this->db->update('acp_pluginy',[
         'nazwa' => $from->nazwa,
         'opis' => $from->opis,
         'cvary' => $from->cvary,
         'notatki' => $from->notatki,
         'lic_name' => $from->lic_name,
         'lic_hash' => $from->lic_hash,
         'u_id' => $this->user
       ], [
         'id' => $from->id
       ]);

     Logs::log("Zedytowano plugin $from->nazwa (ID: $from->id)", "?x=pluginy&id=$from->id");
   }

   function destroy($dostep)
   {
     Permission::check($dostep);

     $dane = $this->db->get_row("SELECT `nazwa` FROM `acp_pluginy` WHERE `id` = $id LIMIT 1", true);

     $usun_pliki_q = $this->db->get_results("SELECT * FROM `acp_pluginy_pliki` WHERE `plugin_id` = $id", true);
     foreach ($usun_pliki_q as $usun_pliki):
       unlink($usun_pliki->ftp_source_file_name);
       $this->db->delete('acp_pluginy_pliki', ['id' => $usun_pliki->id], 1);
     endforeach;
     rmdir("www/server_plugins/$id");

     $this->db->delete('acp_pluginy', ['id' => $this->id], 1);

     Logs::log("Usunięto plugin $dane->nazwa (ID: $this->id)", "?x=pluginy");
   }

   function uploadServer($dostep)
   {
     Permission::check($dostep);

     $from = From::check();

     //Dane pluginu
     $plugin = $this->db->get_row("SELECT `nazwa` FROM `acp_pluginy` WHERE `id` = $from->id", true);
     //Dane pliki pluginu
     $pliki = $this->db->get_results("SELECT `ftp_directory`, `ftp_source_file_name`, `ftp_dest_file_name` FROM `acp_pluginy_pliki` WHERE `plugin_id` = $from->id AND `ftp_source_file_name` != '' AND `ftp_directory` != '' AND `ftp_dest_file_name` != '' AND `starsza_wersja` IS NULL AND `kod_zrodlowy` IS NULL;", true);
     $pliki = json_encode($pliki);

     Model('Wgrywarka')->new($from->serwery, $pliki, $plugin->nazwa);

     Logs::log("Plugin $plugin->nazwa został dodany do zadań Wgrywarki.");
   }
}
?>
