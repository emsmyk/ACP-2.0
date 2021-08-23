<?php
class RoundsoundController
{
  function __construct()
  {
    $this->user = User::get();

    $this->setting = [
      'rs_roundsound' => SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound';"),
      'rs_roundsound_c' => SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound_c';"),
    ];
  }

  function store($dostep)
  {
    Permission::check($dostep);

    $from = From::check(
      [
        'nazwa' => 'reg'
      ],
      [
        'nazwa.reg' => 'Pusta nazwa. Aby dodać nową musisz wpisać coś..'
      ]
    );

    $last = SQL::insert(
      'rs_roundsound',
      [
        'nazwa' => $from->nazwa,
        'u_id' => $this->user,
      ]
    );

    Logs::log("Dodano nową listę utworów Nazwa: $from->nazwa (ID: $last)", "?x=roundsound&xx=lista_edit&id=$last");
  }

  function destroy($id, $dostep)
  {
    Permission::check($dostep);

    $from->rs = SQL::row("SELECT * FROM `rs_roundsound` WHERE `id` = $id LIMIT 1");

    SQL::query("DELETE FROM `rs_roundsound` WHERE `id` = $id LIMIT 1");
    Logs::log("Usunięto listę ".$from->rs->nazwa." (ID: $id)", "?x=roundsound&xx=lista");
  }

  function edit($id)
  {
    $data = SQL::row("SELECT *, (SELECT `login` FROM `acp_users` WHERE `user` = `u_id` LIMIT 1) AS `user_name` FROM `rs_roundsound` WHERE `id` = $id LIMIT 1");
    $data->aktualnie_grany_rs  = $this->setting['rs_roundsound'];
    $data->w_przygotowaniu  = $this->setting['rs_roundsound_c'];

    return $data;
  }

  function update($id, $dostep)
  {
    Permission::check($dostep);
    $from = From::check(
      [
        'nazwa' => 'reg'
      ],
      [
        'nazwa.reg' => 'Pusta nazwa. Aby dodać nową musisz wpisać coś..'
      ]
    );

    SQL::update(
      'rs_roundsound',
      [
        'nazwa' => $from->nazwa,
      ],
      $id
    );

    Logs::log("Zaktualizowano Listę Nazwa: $from->nazwa (ID: $id)", "?x=roundsound&xx=lista_edit&id=$id");
  }

  function status($id, $jaki, $dostep)
  {
    Permission::check($dostep);

    $from = new stdClass();
    $from->rs = $this->edit($id);

    switch ($jaki) {
      case 'aktualna':
        SQL::query("UPDATE `rs_ustawienia` SET `conf_value` = '$id' WHERE `conf_name` = 'rs_roundsound' LIMIT 1");
        SQL::query("UPDATE `rs_ustawienia` SET `conf_value` = '' WHERE `conf_name` = 'rs_roundsound_c' LIMIT 1");
        Logs::log("Ustawiono listę utworów: ".$from->rs->nazwa." (ID: $id) jako Aktualnie Graną", "?x=roundsound&xx=lista_edit&id=$id");
        break;
      case 'w_przygotowaniu':
        SQL::query("UPDATE `rs_ustawienia` SET `conf_value` = '$id' WHERE `conf_name` = 'rs_roundsound_c' LIMIT 1");
        Logs::log("Ustawiono listę utworów: ".$from->rs->nazwa." (ID: $id) jako W przygotowaniu", "?x=roundsound&xx=lista_edit&id=$id");
        break;
    }
  }
}

?>
