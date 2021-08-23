<?php
class UstawieniaController
{
  /*
    $confs=[
      'name' => 'nazwa wiersza',
      'value' => 'wartosc'
    ],
    $table = nazwa tabeli
  */
  function updateConf($confs='', $table='acp_system')
  {
    foreach ($confs as $conf) {
      SQL::update(
        $table, [
          'conf_value' => $conf['value']
        ],
        $conf['name'],
        'conf_name'
      );

    }
  }

  function wpisySave()
  {
    $from = From::check([
      'wpisy_kategorie_nazwa' => 'reg'
    ],[
      'wpisy_kategorie_nazwa.reg' => 'Nazwa kategorii musi być wypełniona'
    ]);

    SQL::query("UPDATE `acp_wpisy_kategorie` SET `nazwa` = '$from->wpisy_kategorie_nazwa' WHERE `id` = $from->wpisy_kategorie_id;");
    Logs::log("Zaktualizowanoa nazwę kategorii wpisów $from->wpisy_kategorie_nazwa ($from->wpisy_kategorie_id)");
  }

  function wpisyStore()
  {
    $from = From::check([
      'wpisy_kategorie_nazwa' => 'reg'
		],[
			'wpisy_kategorie_nazwa.reg' => 'Nazwa kategorii musi być wypełniona'
		]);

    SQL::query("INSERT INTO `acp_wpisy_kategorie` (`id`, `nazwa`) VALUES (NULL, '$from->wpisy_kategorie_nazwa');");
    Logs::log("Dodano nową katerogię wpisów: $from->wpisy_kategorie_nazwa");
  }

  function wpisyDestroy()
  {
    $from = From::check();

    SQL::query("DELETE FROM `acp_wpisy_kategorie` WHERE `id` = $from->wpisy_kategorie_id ");
    Logs::log("Usunięto kategorię wpisu $from->wpisy_kategorie_nazwa ($from->wpisy_kategorie_id)");
  }

  function danepub_zapisz($menu)
  {
    $from = From::check();
    $from->danepub_blank = ($from->danepub_blank == 'on') ? "target='_blank'": '';

    $menu[$from->danepub_id]->page = "$from->danepub_page";
    $menu[$from->danepub_id]->link = $from->danepub_link;
    $menu[$from->danepub_id]->blank = $from->danepub_blank;

    $this->updateConf([
      [ 'name' => 'danepub_menu_list', 'value' => json_encode($menu) ]
    ]);

    Logs::log("Zeedytowano pozycję menu publicznego");
  }

  function danepub_usun($menu)
  {
    $from = From::check();

    unset($menu[$from->danepub_id]);

    $this->updateConf([
      [ 'name' => 'danepub_menu_list', 'value' => json_encode($menu) ]
    ]);

    Logs::log("Skasowano pozycję menu publicznego");
  }

  function danepub_dodaj($menu)
  {
    $from = From::check();
    $from->danepub_blank = ($from->danepub_blank == 'on') ? "target='_blank'": '';

    $menu2 = new stdClass();
    $menu2->page = $from->danepub_page;
    $menu2->link = $from->danepub_link;
    $menu2->blank = $from->danepub_blank;
    $menu[] = $menu2;

    $this->updateConf([
      [ 'name' => 'danepub_menu_list', 'value' => json_encode($menu) ]
    ]);

    Logs::log("Dodano nową pozycję do menu publicznego");
  }

  function zmien_moduly($post_moduly) {
    $array = array();
    foreach ($post_moduly as $post_moduly=>$value) {
      array_push($array, $value);
    }
    return json_encode($array);
  }

  function zmien_dostep($post_dostep) {
    $object = new stdClass();
    foreach ($post_dostep as $result) {
      $result = explode("-", $result);
      $object->{$result[0]} = $result[1];

    }
    return json_encode($object);
  }


  function edycja_from_uprawnienia_usun()
  {
    $from = From::check();

    SQL::query("DELETE FROM `acp_moduly_akcje` WHERE `id` = $from->e_n_id AND `modul_id` = $from->e_n_idmodulu; ");
    Logs::log("Usunięto uprawnienie $from->e_n_akcja (ID: $from->e_n_id) z modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)");
  }

  function edycja_from_uprawnienia_zapisz()
  {
    $from = From::check([
      'e_n_akcja' => 'reg',
      'e_n_akcja_wys' => 'reg'
    ],[
      'e_n_akcja.reg' => 'Pola Akcja(PHP) nie może byc puste',
      'e_n_akcja_wys.reg' => 'Pola Akcja Nazwa nie może byc puste'
    ]);

    SQL::query("UPDATE `acp_moduly_akcje` SET `akcja` = '$from->e_n_akcja', `akcja_wys` = '$from->e_n_akcja_wys', `opis` = '$from->e_n_opis' WHERE `id` = $from->e_n_id AND `modul_id` = $from->e_n_idmodulu; ");
    Logs::log("Zedytowano uprawnienie $from->e_n_akcja (ID: $from->e_n_id) dla modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)");
  }

  function edycja_from_uprawnienia_add()
  {
    $from = From::check([
      'e_n_akcja' => 'reg',
      'e_n_akcja_wys' => 'reg'
    ],[
      'e_n_akcja.reg' => 'Pola Akcja(PHP) nie może byc puste',
      'e_n_akcja_wys.reg' => 'Pola Akcja Nazwa nie może byc puste'
    ]);

    SQL::query("INSERT INTO `acp_moduly_akcje` ( `modul_id`, `akcja`, `akcja_wys`, `opis` ) VALUES( $from->e_new_idmodulu, '$from->e_new_akcja', '$from->e_new_akcja_wys', '$from->e_new_opis') ");
    Logs::log("Dodano nowe uprawnienie $from->e_new_akcja_wys ($from->e_new_akcja) dla modułu $from->e_new_nazamodulu  (ID:$from->e_new_idmodulu) ");
  }

  function edycja_from_menu_add()
  {
    $from = From::check([
      'e_new_nazwa' => 'reg'
    ],[
      'e_new_nazwa.reg' => 'Nazwa jest niezbędna'
    ]);

    SQL::query("INSERT INTO `acp_moduly_menu` ( `modul_id`, `ikona`, `nazwa`, `link` ) VALUES ( $from->e_new_idmodulu, '$from->e_new_ikona', '$from->e_new_nazwa', '$from->e_new_link') ");
    Logs::log("Dodano pozycję $from->e_new_nazwa dla rozwijanego menu dla modulu $from->e_new_nazamodulu  (ID:$from->e_new_idmodulu) ");
  }

  function edycja_from_menu_zapisz()
  {
    $from = From::check([
      'e_n_nazwa' => 'reg'
    ],[
      'e_n_nazwa.reg' => 'Nazwa jest niezbędna'
    ]);

    SQL::query("UPDATE `acp_moduly_menu` SET `ikona` = '$from->e_n_ikona', `nazwa` = '$from->e_n_nazwa', `link` = '$from->e_n_link' WHERE `id` = $from->e_n_id AND `modul_id` = $from->e_n_idmodulu; ");
    Logs::log("Zedytowano pozycję $from->e_n_nazwa (ID: $from->e_n_id) rozwijanego menu dla modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)");
  }

  function edycja_from_menu_usun()
  {
    $from = From::check();

    SQL::query("DELETE FROM `acp_moduly_menu` WHERE `id` = $from->e_n_id AND `modul_id` = $from->e_n_idmodulu; ");
    Logs::log("Usunięto pozycję $from->e_n_nazwa (ID: $from->e_n_id) rozwijanego menu dla modułu $from->e_n_nazamodulu (ID: $from->e_n_idmodulu)");
  }

  function acp_moduly_dodaj()
  {
    $from = From::check([
      'n_nazwa' => 'reg',
      'n_nazwa_wys' => 'reg'
    ],[
      'n_nazwa.reg' => 'Nazwa modulu (PHP) jest wymagana do poprawnego dodania modulu, uzupełnij je!',
      'n_nazwa_wys.reg' => 'Nazwa Wyświetlana jest wymagana do poprawnego dodania modulu, uzupełnij je!'
    ]);

    SQL::query("INSERT INTO `acp_moduly` ( `nazwa`, `nazwa_wys`, `opis` ) VALUES( '$from->n_nazwa', '$from->n_nazwa_wys', '$from->n_opis') ");
    Logs::log("Dodano nowy moduł: $from->n_nazwa_wys ($from->n_nazwa)");
  }

  function acp_moduly_usun($id)
  {
    $id = (int)$id;

    SQL::query("DELETE FROM `acp_moduly` WHERE `id` = $id; ");
    SQL::query("DELETE FROM `acp_moduly_akcje` WHERE `modul_id` = $id; ");

    Logs::log("Usunięto moduł ID: $id");
  }

  function acp_moduly_edytuj_modul($post)
  {
    $from = From::check([
      'e_nazwa' => 'reg',
      'e_nazwa_wys' => 'reg'
    ],[
      'e_nazwa.reg' => 'Nazwa modulu (PHP) jest wymagana do poprawnego dodania modulu, uzupełnij je!',
      'e_nazwa_wys.reg' => 'Nazwa Wyświetlana jest wymagana do poprawnego dodania modulu, uzupełnij je!'
    ]);

    SQL::query("UPDATE `acp_moduly` SET `nazwa` = '$from->e_nazwa', `nazwa_wys` = '$from->e_nazwa_wys', `ikona` = '$from->e_ikona', `menu` = $from->e_menu, `menu_kategoria`= '$from->e_menu_kategoria', `opis` = '$from->e_opis' WHERE `id` = $from->e_id; ");
    Logs::log("Zedytowano moduł: $from->e_nazwa ID: $from->e_id");
  }
}
 ?>
