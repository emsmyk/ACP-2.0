<?php
class Wpisycontroller
{

  function __construct()
  {
    $this->user = User::get();
    $this->WpisId =  Get::int('wpisid');
    $this->page =  Get::int('str');


    $this->limitPerPage = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'wpisy_ilosc_wpisow' LIMIT 1");
    $this->limitComents = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'wpisy_ilosc_komentarzy' LIMIT 1");

    $this->Limit = new stdClass();
    $this->Limit->TytulMin = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'wpisy_nowy_dlugosc_tytulu_min' LIMIT 1");
    $this->Limit->TytulMax = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'wpisy_nowy_dlugosc_tytulu_max' LIMIT 1");
    $this->Limit->TextMin = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'wpisy_nowy_dlugosc_tekstu' LIMIT 1");
  }

  function index($limit = '')
  {
    $wpisy = SQL::all("SELECT *,
  	(SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS steam_avatar,
  	(SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS login,
  	(SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS steam_login,

  	(SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = `kategoria`) AS kategoria_nazwa
  	FROM `acp_wpisy` ORDER BY `id` DESC LIMIT ".$limit[0].", ".$limit[1]." ");

    foreach ($wpisy as $wpis) {
      $wpis->komentarzy = SQL::one("SELECT COUNT(`id`) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $wpis->id");
  		$wpis->komentowalo = SQL::one("SELECT COUNT(DISTINCT `user_id`) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $wpis->id");
      $wpis->kategoria_nazwa = (empty($wpis->kategoria_nazwa)) ? 'Brak kategorii' : $wpis->kategoria_nazwa;
      $wpis->ComentsCount = SQL::one("SELECT COUNT(*) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $wpis->id;");

      $limit_komentarzy = 3;
      //comments
      $wpis->ComentsCountOffset = ($wpis->ComentsCount > $limit_komentarzy) ? $wpis->ComentsCount - $limit_komentarzy : 0;
      $wpis->comments = SQL::all("SELECT *,
      (SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `user_id`) AS koemntujacy_steam_avatar,
      (SELECT `login` FROM `acp_users` WHERE `user` = `user_id`) AS koemntujacy_login,
      (SELECT `steam_login` FROM `acp_users` WHERE `user` = `user_id`) AS koemntujacy_steam_login
      FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $wpis->id ORDER BY `id` ASC LIMIT $limit_komentarzy OFFSET $wpis->ComentsCountOffset;");
    }


    return [
      'wpisy' => $wpisy,
      'COUNTWpisy' => SQL::one("SELECT COUNT(*) FROM `acp_wpisy`;"),
      'COUNTComment' => SQL::one("SELECT COUNT(*) FROM `acp_wpisy_komentarze`;"),
    ];
  }

  function indexComments()
  {
    $comments = SQL::all("SELECT *, (SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `user_id`) AS `koemntujacy_steam_avatar`, (SELECT `login` FROM `acp_users` WHERE `user` = `user_id`) AS `koemntujacy_login`, (SELECT `steam_login` FROM `acp_users` WHERE `user` = `user_id`) AS `koemntujacy_steam_login` FROM `acp_wpisy_komentarze` WHERE `wpis_id` = ".$this->WpisId." ORDER BY `id` ASC;");

    return ['comments' => $comments ];
  }

  function store()
  {
    $from = From::check([
      'nowy_tytul' => "reg|min:".$this->wpis->TytulMin."|max:".$this->wpis->TytulMax."",
      'nowy_tekst' => "reg|min:".$this->wpis->TextMin.""
    ],[
      'nowy_tytul.reg' => 'Tytuł wpisu nie może być pusty',
      'nowy_tytul.min:'.$this->wpis->TytulMin => 'Tytuł musi mieć minimalnie '.$this->wpis->TytulMin.' znaków',
      'nowy_tytul.max:'.$this->wpis->TytulMax => 'Tytuł może mieć maksymalnie '.$this->wpis->TytulMax.' znaków',
      'nowy_tekst.reg' => 'Tekstu wpisu nie może być pusty',
      'nowy_tekst.min:'.$this->wpis->TextMin => 'Tekst wpisu musi być dłuższy niż '.$this->wpis->TextMin.' znaków',
    ]);

    $last_insert = SQL::insert('acp_wpisy',[
        'u_id' => $this->user,
        'tytul' => $from->nowy_tytul,
        'text' => $from->nowy_tekst,
        'kategoria' => $from->nowy_kategoria
      ]
    );

		//powiadomienie
		$user_list = array();
		$uzytkownicy_q = SQL::all("SELECT `user` FROM `acp_users`");
		foreach ($uzytkownicy_q as $uzytkownicy) {
				array_push($user_list, $uzytkownicy->user);
		}

    Powiadomienia::new(
      $user_list,
      [$this->user],
      "?x=wpisy&xx=wpis&wpis=".Text::clean($from->nowy_tytul)."&wpisid=$last_insert",
      "Wpisy | $from->nowy_tytul",
      "fa fa-comment fa-fw"
    );

    Logs::log("Dodano nowy wpis $from->nowy_tytul (ID: $last_insert)", "?x=wpisy&xx=wpis&wpisid=$last_insert");
  }

  function edit()
  {
    return SQL::row("SELECT *,
    (SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_avatar`,
    (SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS `login`,
    (SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_login`,

    (SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = `kategoria`) AS `kategoria_nazwa`
    FROM `acp_wpisy` WHERE `id` = ".$this->WpisId." LIMIT 1;");
  }

  function update($id, $dostep)
  {
    Permission::check($dostep);

		$from = From::check();

		SQL::update(
			'acp_wpisy',
			[
				'tytul' => $from->tytul,
				'text' => $from->tekst,
			],
			$from->id
		);

		Logs::log("Zedytowano wpis ".Model('Wpisy')->wpis($id)->tytul." (ID: $from->id)", "?x=wpisy&xx=wpis&wpisid=$from->id", "?x=wpisy&xx=wpis&wpisid=$from->id");
  }

  function storeComment()
  {
    $from = From::check([
      'komentarz_tekst' => "reg|min:".$this->TextMin."",
    ],[
      'komentarz_tekst.reg' => 'Komentarz nie może być pusty',
      'komentarz_tekst.min:'.$this->TextMin => 'Komentarz musi być dłuższy niż '.$this->TextMin.' znaków',
    ]);


    $wpis = SQL::row("SELECT `tytul`, `u_id` FROM `acp_wpisy` WHERE `id`= $from->komentarz_id LIMIT 1;");

    SQL::insert('acp_wpisy_komentarze',[
      'wpis_id' => $from->komentarz_id,
      'user_id' => $this->user,
      'text' => $from->komentarz_tekst
    ]);

    //powiadomienie
    $user_list = array();
    ($this->user == $wpis->u_id) ? '' : array_push($user_list, $wpis->u_id);
    $uzytkownicy_q = SQL::all("SELECT `user_id` FROM `acp_wpisy_komentarze`");
    foreach ($uzytkownicy_q as $uzytkownicy) {
      if($this->user != $uzytkownicy->user_id && $wpis->u_id != $uzytkownicy->user_id) {
        array_push($user_list, $uzytkownicy->user_id);
      }
    }

    Powiadomienia::new(
      $user_list,
      [],
      "?x=wpisy&xx=wpis&wpis=".Text::clean($wpis->tytul)."&wpisid=$from->komentarz_id",
      "Wpisy | ".$this->user_det->steam_login." (".$this->user_det->login.") napisał komentarz w $wpis->tytul",
      "fa fa-comment fa-fw"
    );

    Logs::log("Dodano nowy komentarz do wpisu $wpis->tytul (ID: $from->komentarz_id)", "?x=wpisy&xx=wpis&wpisid=$from->komentarz_id");
  }

  function destroy($id, $dostep)
  {
    Permission::check($dostep);

    SQL::query("DELETE FROM `acp_wpisy_komentarze` WHERE `f_id` = $id;");
    SQL::query("DELETE FROM `acp_wpisy` WHERE `id` = $id;");

    Logs::log("Wpis ".Model('Wpisy')->wpis($id)->tytul." (ID: $id) zostal usunięty", "?x=wpisy");
  }

  function ogloszenie($id, $dostep)
  {
    Permission::check($dostep);
    SQL::update(
      'acp_wpisy',
      [
        'ogloszenie' => '1'
      ],
      $id
    );
    Logs::log("Wpis ".Model('Wpisy')->wpis($id)->tytul." (ID: $id) zostal oznaczony jako ogłoszenie", "?x=wpisy&xx=wpis&wpisid=$id");
  }

  function close($id, $dostep)
  {
    Permission::check($dostep);

    $close['closed'] = (Model('Wpisy')->wpis($id)->closed == 1) ? 0 : 1;
    $close['closed_data'] = (Model('Wpisy')->wpis($id)->closed == 1) ? 'NULL' : date("Y-m-d H:i:s");
    $close['text'] = (Model('Wpisy')->wpis($id)->closed == 1) ? 'otwarty' : 'zamknięty';

    SQL::update(
      'acp_wpisy',
      [
        'closed' => $close['closed'],
        'closed_data' => $close['closed_data']
      ],
      $id
    );
    Logs::log("Wpis ".Model('Wpisy')->wpis($id)->tytul." (ID: $id) zostal ".$close['text'], "?x=wpisy&xx=wpis&wpisid=$id");
  }

  function kategoria($id, $dostep)
  {
    Permission::check($dostep);

		$from = From::check();
		$dane_kat = SQL::row("SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = $from->id LIMIT 1;");

		SQL::update(
			'acp_wpisy',
			[
				'kategoria' => $from->kategoria
			],
			$id
		);

		Logs::log("Zmieniono kategorię dla wpisu ".Model('Wpisy')->wpis($id)->tytul." (ID: $from->id) na $dane_kat->nazwa (ID: $from->kategoria)", "?x=wpisy&xx=wpis&wpisid=$from->id");
  }
}
 ?>
