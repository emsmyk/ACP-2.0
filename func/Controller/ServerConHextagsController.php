<?
 class ServerConHextagsController
 {
     public function __construct()
     {
       $this->id = Get::int('id');

       $this->YesNo = array(1 => 'Tak', 0 => 'Nie');
       $this->type = array(1 => 'Tymczasowa', 0 => 'Stała');
       $this->colors = array('default' => 'Domyślny', 'teamcolor' =>'Kolor Teamu', 'red' =>'Czarwony', 'lightred' =>'Jasny Czerwony', 'darkred' =>'Ciemno Czerwony', 'bluegrey' =>'Niebisko Szary', 'blue' =>'Niebieski', 'darkblue' =>'Ciemny Niebieski', 'orchid' =>'Fioletowy', 'yellow' =>'Żółty', 'gold' =>'Złoty', 'lightgreen' =>'Jasny Zielony', 'green' =>'Zielony', 'lime' =>'Limonkowy', 'grey' =>'Szary', 'grey2' =>'Szary 2', 'orange' => 'Pomarańczowy');
     }

     function index()
     {
       return SQL::all("SELECT * FROM `acp_serwery_hextags`");
     }

     function store($dostep)
     {
       Permission::check($dostep);

       $from = From::check([
         'n_tag_tabela' => 'reg',
         'n_tag_say' => 'reg',
       ],[
         'n_tag_tabela.reg' => 'Tag Tabela nie może być pusty..',
         'n_tag_say.reg' => 'Tak Say nie może być pusty..'
       ]);

       $najwieksza_wartosc = SQL::one("SELECT `istotnosc` FROM `acp_serwery_hextags` ORDER BY `istotnosc` DESC LIMIT 1");
       $najwieksza_wartosc = (int)$najwieksza_wartosc +1;

       $last_insert = SQL::insert('acp_serwery_hextags',[
           'serwer_id' => $from->n_serwer,
           'hextags' => $from->n_typ,
           'ScoreTag' => $from->n_tag_tabela,
           'TagColor' => $from->n_kolor_tag_tag,
           'ChatTag' => $from->n_tag_say,
           'ChatColor' => $from->n_kolor_tag,
           'NameColor' => $from->n_kolor_nick,
           'Force' => $from->n_force,
           'istotnosc' => $najwieksza_wartosc,
           'komentarz' => $from->n_komentarz,
         ]
       );

       Logs::log("Dodano HexTags $from->n_typ (ID: $last_insert) [Ranga: $from->n_tag_tabela]", "?x=serwery_konfiguracja&xx=hextags&edycja=$last_insert");
     }

     function edit()
     {
       return SQL::row("SELECT * FROM `acp_serwery_hextags` WHERE `id` = $this->id LIMIT 1;");
     }

     function update($dostep)
     {
       Permission::check($dostep);

       $from = From::check();

       SQL::update('acp_serwery_hextags',[
           'serwer_id' => $from->e_serwerid,
           'hextags' => $from->e_hextags,
           'TagName' => $from->e_TagName,
           'ScoreTag' => $from->e_ScoreTag,
           'TagColor' => $from->e_TagColor,
           'ChatTag' => $from->e_ChatTag,
           'ChatColor' => $from->e_ChatColor,
           'NameColor' => $from->e_NameColor,
           'Force' => $from->e_Force,
           'komentarz' => $from->e_komentarz,
         ],
         $from->id
       );

       Logs::log("Zaktualizowano HexTags $from->e_hextags (ID: $from->id) [Ranga: $from->e_ScoreTag]", "?x=serwery_konfiguracja&xx=hextags&edycja=$from->id");
     }

     function destroy($dostep)
     {
       Permission::check($dostep);

       query("DELETE FROM `acp_serwery_hextags` WHERE `id` = $this->id LIMIT 1;");
       Logs::log("Usunięto HexTags ID: $this->id", "?x=serwery_konfiguracja&xx=hextags");
     }

     function destroyOld($limit, $day){
       return SQL::query("DELETE FROM `acp_serwery_hextags` WHERE `czasowa_end` < NOW() - INTERVAL $day HOUR AND `czasowa` = 1");
     }

 }
?>
