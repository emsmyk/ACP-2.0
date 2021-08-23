<?
 class ServerConHelpMenuController
 {
     public function __construct()
     {
       $this->id = Get::int('id');
       $this->OnOff = array(1 => 'Włączony', 0 => 'Wyłączony');
     }

     function index()
     {
       return SQL::all("SELECT *, `serwer_id` AS `serwer_id_table`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer_id_table`) AS `serwer_nazwa` FROM `acp_serwery_helpmenu`");
     }

     function store($dostep)
     {
       Permission::check($dostep);
       $from = From::check();

       $serwer_nazwa = Model('Server')->mod($from->serwer);
       $czy_serwer_istenieje = SQL::one("SELECT COUNT(`id`) FROM `acp_serwery_helpmenu` WHERE `serwer_id` = $from->serwer");
       if($czy_serwer_istenieje != 0){
         return Messe::array([
           'type' => 'success',
           'text' => "Serwer $serwer_nazwa (ID: $from->serwer) posiada już menu."
         ]);
       }

       $last_insert = SQL::insert('acp_serwery_helpmenu',[
           'serwer_id' => $from->serwer,
           'lista_serwerow' => $from->lista_serwerow,
           'lista_adminow' => $from->lista_adminow,
           'opis_vipa' => $from->opis_vipa,
           'lista_komend' => $from->lista_komend,
           'statystyki' => $from->statystyki,
         ]
       );

       Logs::log("Dodano nowe Help Menu (ID: $last_insert) dla serwera $serwer_nazwa (ID: $from->serwer)", "?x=serwery_konfiguracja&xx=help_menu");
     }

     function edit()
     {
       return SQL::row("SELECT * FROM `acp_serwery_helpmenu` WHERE `id` = $this->id LIMIT 1;");
     }

     function update($dostep)
     {
       Permission::check($dostep);

       $from = From::check();

       $serwer_nazwa = Model('Server')->mod($from->serwer);

       SQL::update('acp_serwery_helpmenu',[
           'lista_serwerow' => $from->lista_serwerow,
           'lista_adminow' => $from->lista_adminow,
           'opis_vipa' => $from->opis_vipa,
           'lista_komend' => $from->lista_komend,
           'statystyki' => $from->statystyki,
         ],
         $from->id
       );

       Logs::log("Zaktualizowano Help Menu (ID: $from->id) dla serwera $serwer_nazwa (ID: $from->serwer)", "?x=serwery_konfiguracja&xx=help_menu");
     }

     function destroy($dostep)
     {
       Permission::check($dostep);

       query("DELETE FROM `acp_serwery_helpmenu` WHERE `id` = $this->id");
       query("DELETE FROM `acp_serwery_helpmenu_komendy` WHERE `helpmenu_id` = $this->id");
       query("DELETE FROM `acp_serwery_helpmenu_vip` WHERE `helpmenu_id` = $this->id");

       Logs::log("Usunięto Help Menu (ID: $this->id)", "?x=serwery_konfiguracja&xx=help_menu");
     }

     function storeVip($dostep)
     {
       Permission::check($dostep);

       $from = From::check([
         'tekst' => 'reg'
       ],[
         'tekst.reg' => 'Pole tekst nie może być puste, uzupełnij je..'
       ]);

       $najwieksza_wartosc = SQL::one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_vip` WHERE `helpmenu_id` = $from->helpmenu_id ORDER BY `acp_serwery_helpmenu_vip`.`kolejnosc` DESC LIMIT 1");
       $serwer_nazwa = Model('Server')->mod($serwer);

       $last_insert = SQL::insert('acp_serwery_helpmenu_vip',[
           'serwer_id' => $from->serwer_id,
           'helpmenu_id' => $from->helpmenu_id,
           'tekst' => $from->tekst,
           'kolejnosc' => $najwieksza_wartosc +1,
         ]
       );

       Logs::log("Dodano nową pozycję dla Opisu Vipa - Help Menu (ID: $from->helpmenu_id) dla serwera $serwer_nazwa (ID: $from->serwer_id)");
     }

     function updateVip($dostep)
     {
       Permission::check($dostep);

       $from = From::check([
         'tekst' => 'reg'
       ],[
         'tekst.reg' => 'Pole tekst nie może być puste, uzupełnij je..'
       ]);

       SQL::insert('acp_serwery_helpmenu_vip',[
           'tekst' => $from->tekst,
         ],
         $from->id
       );

       Logs::log("Zeedytowano pozycję Help Menu (ID: $from->helpmenu_id) opis vipa ID: $from->id");
     }

     function destroyVip($dostep)
     {
       Permission::check($dostep);

       $from = From::check();

       SQL::query("DELETE FROM `acp_serwery_helpmenu_vip` WHERE `id` = $from->id LIMIT 1");
       Logs::log("Usunięto pozycję Opis Vipa (ID: $from->id) dla Help Menu (ID: $from->helpmenu_id)");

     }

     function storeKomenda($dostep)
     {
       Permission::check($dostep);

       $from = From::check(
         [
           'tekst' => 'reg',
           'komenda' => 'reg'
         ],
         [
           'tekst.reg' => 'Pole tekst jest wymagane',
           'komenda.reg' => 'Pole komenda jest wymagane'
         ]
       );
       $najwieksza_wartosc = SQL::one("SELECT `kolejnosc` FROM `acp_serwery_helpmenu_komendy` WHERE `helpmenu_id` = $from->helpmenu_id ORDER BY `kolejnosc` DESC LIMIT 1");
       $serwer_nazwa = Model('Server')->mod($serwer);

       $last_insert = SQL::insert(
         'acp_serwery_helpmenu_komendy',
         [
           'serwer_id' => $from->serwer_id,
           'helpmenu_id' => $from->helpmenu_id,
           'komenda' => $from->komenda,
           'tekst' => $from->tekst,
           'kolejnosc' => $najwieksza_wartosc +1
         ]
       );

       Logs::log("Dodano nową pozycję dla Komendy - Help Menu (ID: $from->helpmenu_id) dla serwera $serwer_nazwa (ID: $from->serwer_id)", "?x=serwery_konfiguracja&xx=help_menu");
     }

     function updateKomenda($dostep)
     {
       Permission::check($dostep);

       $serwer_nazwa = Model('Server')->mod($serwer);

       $from = From::check(
         [
           'tekst' => 'reg',
           'komenda' => 'reg'
         ],
         [
           'tekst.reg' => 'Pole tekst jest wymagane',
           'komenda.reg' => 'Pole komenda jest wymagane'
         ]
       );

       SQL::update(
         'acp_serwery_helpmenu_komendy',
         [
           'komenda' => $from->komenda,
           'tekst' => $from->tekst,
         ],
         $from->id
       );

       Logs::log("Zaktualizowano komendę - Help Menu (ID: $from->helpmenu_id) dla serwera $serwer_nazwa (ID: $from->serwer_id)", "?x=serwery_konfiguracja&xx=help_menu");
     }

     function destroyKomenda($dostep)
     {
       Permission::check($dostep);

       $from = From::check();

       SQl::query("DELETE FROM `acp_serwery_helpmenu_komendy` WHERE `id` = $from->id LIMIT 1");
       Logs::log("Usunięto pozycję Lista Komend (ID: $from->id) dla Help Menu (ID: $from->helpmenu_id)", "?x=serwery_konfiguracja&xx=help_menu");
     }
 }
?>
