<?php
class ServerConTagiController
{

  function __construct()
  {
    // code...
  }

  function index()
  {
    $data = SQL::all('SELECT * FROM `acp_serwery_tagi`');

    foreach ($data as $dat) {
      $dat->serwer_nazwa = ($dat->serwer==0) ? 'Wszystkie' : Model('Server')->mod($dat->serwer);
      $dat->serwer_nazwa = (empty($dat->serwer_nazwa)) ? '<i>Serwer nie istnieje</i>' : $dat->serwer_nazwa;
      $dat->staly = ($dat->staly == 1) ? 'Tak' : 'Nie';
    }

    return $data;
  }

  function edit($id)
  {
    return SQL::row('SELECT * FROM `acp_serwery_tagi` WHERE `id` = '.$id.' LIMIT 1');
  }

  function store($dostep)
  {
    Permission::check($dostep);

    $from = From::check();
    $form->staly = ($form->staly == 'on') ? '1' : '0';


    $last_insert = SQL::insert('acp_serwery_tagi', [
      'serwer' => $from->serwer,
      'tekst' => $from->tag,
      'staly' => $from->staly,
    ]);

    Logs::log("Dodano nowy Tag: $form->tag ($last_insert) dla serwera ID: $form->serwer", "?x=serwery_konfiguracja&xx=tagi&edycja=$last_insert");
  }

  function update($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check();
    $form->staly = ($form->staly == 'on') ? '1' : '0';

    SQL::update('acp_serwery_tagi', [
      'serwer' => $from->serwer,
      'tekst' => $from->tag,
      'staly' => $from->staly,
      ],
      $from->id
    );

    Logs::log("Zaktualizowano Tag: $form->tag (ID: $form->id) dla serwera ID: $form->serwer", "?x=serwery_konfiguracja&xx=tagi");
  }

  function destroy($id, $dostep)
  {
    Permission::check($dostep);

    $dane = SQL::row("SELECT `tekst` FROM `acp_serwery_tagi` WHERE `id` = $id LIMIT 1");
    SQL::query("DELETE FROM `acp_serwery_tagi` WHERE `id` = $id LIMIT 1;");

    Logs::log("Usunięto Tag $dane->tekst (ID: $id)", "?x=serwery_konfiguracja&xx=tagi");
  }

}
 ?>
