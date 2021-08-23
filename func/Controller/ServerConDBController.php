<?
 class ServerConDBController
 {
     public function __construct()
     {
       $this->db = DB::getInstance();

       $this->id = Get::int('id');
       $this->OnOff = array(1 => 'Włączony', 0 => 'Wyłączony');
     }

     function index()
     {
       return SQL::all("SELECT * FROM `acp_serwery_baza`");
     }

     function store($dostep)
     {
       Permission::check($dostep);

       $from = From::check([
         'n_nazwa' => 'reg'
       ],[
         'n_nazwa.reg' => 'Pole nazwa jest wymagane'
       ]);

       $last_insert = SQL::insert('acp_serwery_baza',[
           'serwer_id' => $from->n_serwer,
           'nazwa' => $from->n_nazwa,
           'd_driver' => $from->n_driver,
           'd_host' => $from->n_host,
           'd_baze' => $from->n_baza,
           'd_user' => $from->n_user,
           'd_pass' => $from->n_haslo,
           'd_timeout' => $from->n_timeout,
           'd_port' => $from->n_port,
           'd_time_port_on' => $from->n_time_out_on,
         ]
       );

       Logs::log("Dodano bazę danych $from->n_nazwa (ID: $last_insert)", "?x=serwery_konfiguracja&xx=baza&edycja=$last_insert");
     }

     function edit()
     {
       return SQL::row("SELECT * FROM `acp_serwery_baza` WHERE `id` = $this->id LIMIT 1;");
     }

     function update($dostep)
     {
       Permission::check($dostep);

       $from = From::check([
         'n_nazwa' => 'reg'
       ],[
         'n_nazwa.reg' => 'Pole nazwa jest wymagane'
       ]);

       SQL::update('acp_serwery_baza',[
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
         ],
         $from->id
       );

       Logs::log("Zaktualizowano bazę danych $from->e_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=baza&edycja=$from->id");
     }

     function destroy($dostep)
     {
       Permission::check($dostep);

       $this->db->delete('acp_serwery_baza', ['id' => $this->id], 1);

       Logs::log("Usunięto bazę danych ID: $this->id", "?x=serwery_konfiguracja&xx=baza");
     }
 }
?>
