<?php
class UslugiController
{

  function __construct()
  {
    $this->user = $_SESSION['user'];
  }

  function dodaj_usluge($dostep)
	{
		Permission::check($dostep);

		$from = From::check([
      'n_nazwa' => 'reg',
      'n_flagi' => 'reg'
    ],[
      'n_nazwa.reg' => 'Podaj nazwe uslugi',
      'n_flagi.reg' => 'Podaj flagi dla tej usługi'
    ]);

		SQL::insert('acp_uslugi_rodzaje',[
				'nazwa' => $form->n_nazwa,
				'flags' => $form->n_flagi,
			]
		);

		Logs::log("Dodano nową usługę $from->n_nazwa", "?x=uslugi");
	}

  function zapisz_zmiany($dostep)
	{
		Permission::check($dostep);

		$from = From::check(
			[
				'nazwa' => 'reg',
				'flagi' => 'flagi'
			],
			[
				'nazwa.reg' => 'Podaj nazwę usługi',
				'flagi.reg' => 'Podaj flagi jakie nada ta usługa'
			]
		);

		SQL::update(
			'acp_uslugi_rodzaje',
			[
				'nazwa' => $from->nazwa,
				'flags' => $from->flagi
			],
			$from->id
		);

		Logs::log("Zaktualizowano usługę $from->nazwa (ID: $from->id)", "?x=uslugi");
	}

  function usun_usluge($dostep)
	{
		Permission::check($dostep);

		$from = From::check();

		SQL::query("DELETE FROM `acp_uslugi_rodzaje` WHERE `acp_uslugi_rodzaje`.`id` = $from->id");
		Logs::log("Usunięto usługę $from->nazwa (ID: $from->id)", "?x=uslugi");
	}

  function edytuj_dane_publiczne($dostep)
	{
		Permission::check($dostep);

		$from = From::check();

		SQL::update(
			'acp_uslugi_rodzaje',
			[
				'publiczna' => $from->publiczna,
				'img' => $from->img,
				'opis' => $from->opis
			],
			$from->id
		);

		Logs::log("Zaktualizowano usługę $from->nazwa (ID: $from->id)", "?x=uslugi");
	}

	function admin_dodaj_usluge($dostep)
	{
		Permission::check($dostep);

		$from = From::check(
			[
				'dni' => 'reg',
				'steam' => 'reg'
			],
			[
				'dni.reg' => 'Podaj na ile dni chcesz nadać usługę..',
				'steam.reg' => 'Aby dodac usługę należy podać steam id..'
			]
		);
		$from->steam_comunity = $Steam->toCommunityID($from->steam);

		$now = new DateTime();
		$from->koniec = $now->modify("+$from->dni day")->format('Y-m-d H:i:s');
		$from->serwery_dostepne = json_decode(SQL::one("SELECT `serwery` FROM `acp_uslugi_rodzaje` WHERE `id` = $from->rodzaj_uslugi LIMIT 1"));

		if(!in_array($from->serwer, $from->serwery_dostepne)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Wybrana Usługa nie jest włączona na wybranym serwerze. Nie możemy jej dodać."
      ]);
		}

		SQL::insert('acp_uslugi',[
				'user' => $this->user,
				'serwer' => $form->serwer,
				'steam' => $form->steam_comunity,
				'steam_id' => $form->steam,
				'koniec' => $form->koniec,
				'rodzaj' => $form->rodzaj,
			]
		);

		Logs::log("Dodano usługę $from->steam_comunity (STEAMID: $from->steam)", "?x=uslugi&xx=dodaj_usluge");
	}

	function uslugi_edytuj($dostep)
	{
		Permission::check($dostep);

		$from = From::check(
			[
				'koniec' => 'reg',
				'steam' => 'reg'
			],
			[
				'koniec.reg' => 'Podaj datę kiedy usługa ma się zakończyć.',
				'steam.reg' => 'Aby zeedytować usługę należy podać steam id..'
			]
		);
		$from->steam_comunity = $Steam->toCommunityID($from->steam);

		$from->serwery_dostepne = json_decode(SQL::one("SELECT `serwery` FROM `acp_uslugi_rodzaje` WHERE `id` = $from->rodzaj LIMIT 1"));

		if(!in_array($from->serwer, $from->serwery_dostepne)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Wybrana Usługa nie jest włączona na wybranym serwerze. Nie możemy jej dodać."
      ]);
		}

		SQL::update(
			'acp_uslugi',
			[
				'serwer' => $from->serwer,
				'steam_id' => $from->steam,
				'koniec' => $from->koniec,
				'rodzaj' => $from->rodzaj
			],
			$from->id
		);

		Logs::log("Zedytowano usługę ID: $from->id - $from->steam_comunity (STEAMID: $from->steam)", "?x=uslugi&xx=uslugi");

	}

  function uslugi_usun($id, $dostep)
	{
		Permission::check($dostep);

		$id = (int)$id;
		$usluga = SQL::row("SELECT `steam`, `steam_id`, `serwer`, `rodzaj` FROM `acp_uslugi` WHERE `id` = $id LIMIT 1");
 		SQL::query("DELETE FROM `acp_uslugi` WHERE `acp_uslugi`.`id` = $id LIMIT 1");
		Logs::log("Skasowano usługę $usluga->steam (STEAMID: $from->steam_id) Serwer ID: $usluga->serwer Rodzaj ID: $usluga->rodzaj", "?x=uslugi&xx=uslugi");
	}

  function destroyOld($limit, $day){
    return SQL::query("DELETE FROM `acp_uslugi` WHERE `koniec` < NOW() - INTERVAL $day HOUR LIMIT $limit;");
  }
}
 ?>
