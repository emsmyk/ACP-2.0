<?php
class ServerConMapyController
{

  function __construct()
  {

  }

  function storeMap($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check(
      [
        'mapy_nazwa' => 'reg'
      ],
      [
        'mapy_nazwa.reg' => 'Aby dodać mapę, wymagana jest jej nazwa.'
      ]
    );

    $last_insert = SQL::insert(
      'acp_serwery_mapy_det',
      [
        'mapy_id' => $from->id,
        'nazwa' => $from->mapy_nazwa,
        'display' => $from->mapy_display
      ]
    );

    Logs::log("Dodano mapę: $from->mapy_nazwa (ID: $last_insert) do grupy map $from->nazwa_grupy ($from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }

  function updateMap($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check(
      [
        'mapy_nazwa' => 'reg'
      ],
      [
        'mapy_nazwa.reg' => 'Aby dodać mapę, wymagana jest jej nazwa.'
      ]
    );

    SQL::update(
      'acp_serwery_mapy_det',
      [
        'nazwa' => $from->mapy_nazwa,
        'display' => $from->mapy_display
      ],
      $from->id
    );

    Logs::log("Zedytowano mapę: $from->mapy_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }

  function destroyMap($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check();

    SQL::query("DELETE FROM `acp_serwery_mapy_det` WHERE `id` = $from->id; ");
    Logs::log("Usunięto mapę: $from->mapy_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }

  function editMap($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check();

    SQL::update(
      'acp_serwery_mapy_det',
      [
        'nazwa' => $from->e_nazwa,
        'display' => $from->e_display,
        'weight' => $from->e_weight,
        'next_mapgroup' => $from->e_next_mapgroup,
        'min_players' => $from->e_min_players,
        'max_players' => $from->e_max_players,
        'min_time' => $from->e_min_time,
        'max_time' => $from->e_max_time,
        'allow_every' => $from->e_allow_every,
        'command' => $from->e_command,
        'nominate_flags' => $from->e_nominate_flags,
        'adminmenu_flags' => $from->e_adminmenu_flag,
      ],
      $from->id
    );
    Logs::log("Zaktualizowano mapę $from->e_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }

  function storeMapsGrup($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check(
      [
        'n_nazwa' => 'reg'
      ],
      [
        'n_nazwa.reg' => 'Nazwa grupy map nie może być pusta, uzupełnij ją..'
      ]
    );

    $last_insert = SQL::insert(
      'acp_serwery_mapy',
      [
        'serwer_id' => $from->n_serwer,
        'nazwa' => $from->n_nazwa
      ]
    );

    Logs::log("Dodano grupę map: $from->n_nazwa (ID: $last_insert)", "?x=serwery_konfiguracja&xx=mapy");
  }

  function updateMapsGrup($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check();

    SQL::query("UPDATE `acp_serwery_mapy` SET `serwer_id` = '$from->e_serwerid', `nazwa` = '$from->e_nazwa', `display_template` = '$from->e_display_template', `maps_invote` = '$from->e_maps_invote', `next_mapgroup` = '$from->e_next_mapgroup', `nominate_flags` = '$from->e_nominate_flags', `adminmenu_flag` = '$from->e_adminmenu_flag', `command` = '$from->e_command', `group_weight` = '$from->e_group_weight', `default_min_players` = '$from->e_default_min_players', `default_max_players` = '$from->e_default_max_players', `default_min_time` = '$from->e_default_min_time', `default_max_time` = '$from->e_default_max_time', `default_allow_every` = '$from->e_default_allow_every' WHERE `id` = $from->id; ");
    Logs::log("Zaktualizowano grupę map $from->e_nazwa (ID: $from->id)", "?x=serwery_konfiguracja&xx=mapy");
  }

  function destroyMapsGrup($id, $dostep)
  {
    Permission::check($dostep);

    $dane = SQL::row("SELECT `nazwa` FROM `acp_serwery_mapy` WHERE `id` = $id LIMIT 1");
    SQL::query("DELETE FROM `acp_serwery_mapy` WHERE `id` = $id LIMIT 1");
    SQL::query("DELETE FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $id");

    Logs::log("Usunięto grupę map $dane->nazwa (ID: $id)", "?x=serwery_konfiguracja&xx=mapy");
  }

}
?>
