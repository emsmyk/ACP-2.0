<?
 class RootGrupsController
 {
   public function __construct()
   {
     $this->db = DB::getInstance();

     $this->id = Get::int('id');
     $this->co = Get::string('co');
     $this->OnOff = array(1 => 'Włączony', 0 => 'Wyłączony');
   }

   function index()
   {
     return $this->db->get_results("SELECT *, (SELECT COUNT(`login`) FROM `acp_users` WHERE `grupa` = `id`) AS `liczba_userow` FROM `acp_users_grupy` ORDER BY `id` +0 ASC;", true);
   }

   function store()
   {
     $from = From::check([
       'new_nazwa' => 'reg'
     ],[
       'new_nazwa.reg' => 'Pole nazwa grupy jest wymagane'
     ]);

     $this->db->insert('acp_users_grupy',[
         'nazwa' => $from->new_nazwa,
         'kolor' => $from->new_kolor,
       ]
     );

     Logs::log("Utworzono nową grupę $from->new_nazwa (ID: ".$this->db->lastid().")", "?x=acp_grupy");
   }

   function edit()
   {
     return $this->db->get_row("SELECT * FROM `acp_users_grupy` WHERE `id` = $this->id LIMIT 1;", true);
   }

   function update()
   {
     $from = From::check([
       'n_nazwa' => 'reg'
     ],[
       'n_nazwa.reg' => 'Pole nazwa jest wymagane'
     ]);

     $this->db->update('acp_serwery_baza',[
         'serwer_id' => $from->e_serwerid,
         'nazwa' => $from->e_nazwa,
         'd_driver' => $from->e_driver,
         'd_host' => $from->e_host,
         'd_baze' => $from->e_baza,
         'd_user' => $from->e_user,
         'd_pass' => $from->e_haslo,
         'd_timeout' => $from->e_timeout,
         'd_port' => $from->e_port,
         'd_time_port_on' => $from->e_time_out_on,
       ], [
         'id' => $from->id
       ]
     );

     Logs::log("Zaktualizowano bazę danych $from->e_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=baza&edycja=$from->id");
   }

   function destroy($id)
   {
     if($id == 0) {
       return Messe::array([
         'type' => 'warning',
         'text' => "Nie można usunąc podstawowej grupy"
       ]);
     }

     $this->db->update('acp_users',[
         'grupa' => '0',
       ], [
         'grupa' => $id
       ]
     );

     $nazwa = $this->db->get_row("SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = $id LIMIT 1;")[0];

     $this->db->query("DELETE FROM `acp_users_grupy` WHERE `id` = $id;");
     Logs::log("Grupa $nazwa (ID: $id) została usunięta", "?x=acp_grupy");
   }
 }
?>
