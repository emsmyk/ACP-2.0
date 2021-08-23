<?
class ServerConReklamyController
{
   public function __construct()
   {
     $this->id = Get::int('id');

     $this->gdzie = array('S' => 'Tekst w Say', 'C' => 'Tekst w Csay', 'M' => 'Menu');
     $this->YesNo = array(1 => 'Tak', 0 => 'Nie');
   }

   function index()
   {
     return SQL::all("SELECT * FROM `acp_serwery_reklamy`");
   }

   function store($dostep)
   {
     Permission::check($dostep);

     $from = From::check([
       'n_tekst' => 'reg',
     ],[
       'n_tekst.reg' => 'Wymagany jest tekst reklamy'
     ]);

     $last_insert = SQL::insert('acp_serwery_reklamy',[
         'serwer_id' => $from->n_serwer,
         'tekst' => $from->n_tekst,
         'gdzie' => $from->n_gdzie
       ]
     );

     Logs::log("Dodano reklamę: $from->n_tekst (ID: $last_insert)", "?x=serwery_konfiguracja&xx=reklamy&edycja=$last_insert");
   }

   function edit()
   {
     return SQL::row("SELECT * FROM `acp_serwery_reklamy` WHERE `id` = $this->id LIMIT 1;");
   }

   function update($dostep)
   {
     Permission::check($dostep);

     $from = From::check([
       'e_tekst' => 'reg',
     ],[
       'e_tekst.reg' => 'Wymagany jest tekst reklamy'
     ]);

     SQL::update('acp_serwery_reklamy',[
         'serwer_id' => $from->e_serwerid,
         'tekst' => $from->e_tekst,
         'gdzie' => $from->e_gdzie,
         'czasowa' => $from->e_gdze_czasowaie,
         'czasowa_end' => $from->e_czasowa_end,
         'zakres' => $from->e_zakres,
         'zakres_start' => $from->e_zakres_start,
         'zakres_stop' => $from->e_zakres_koniec,
       ],
       $from->id
     );

     Logs::log("Zaktualizowano reklamę: $from->e_tekst (ID: $from->id)", "?x=serwery_konfiguracja&xx=reklamy&edycja=$from->id");
   }

   function destroy($dostep)
   {
     Permission::check($dostep);

     query("DELETE FROM `acp_serwery_reklamy` WHERE `id` = $this->id LIMIT 1;");
     Logs::log("Usunięto reklamę ID: $this->id", "?x=serwery_konfiguracja&xx=reklamy");
   }

   function destroyOld($limit, $day)
   {
     return SQL::query("DELETE FROM `acp_serwery_reklamy` WHERE `czasowa_end` < NOW() - INTERVAL $day DAY AND `czasowa` = 1");
   }
}
?>
