<?php
class ServerRegulaminController
{

  function __construct()
  {
    // code...
  }

  function edit($serwer)
  {
    $from = From::check();
    $from->tekst = htmlspecialchars($from->tekst);

    $czy_jest = SQL::one("SELECT `id` FROM `acp_serwery_regulamin` WHERE `id` = $from->id");
    if(empty($czy_jest)){
      SQL::insert('acp_serwery_regulamin',[
        'serwer_id' => $serwer,
        'tekst' => $from->tekst,
        'link' => $from->link
      ]);

      Logs::log("Dodano nowy regulamin dla serwera ID: $serwer", "?x=serwery_det&serwer_id=$serwer");
      return;
    }
    else {
      SQL::update('acp_serwery_regulamin',[
        'tekst' => $from->tekst,
        'link' => $from->link
      ],
      $serwer,
      'serwer_id'
      );

      Logs::log("Zaktualizowano regulamin (ID: $from->id) serwera ID: $serwer", "?x=serwery_det&serwer_id=$serwer");
    }
  }
}
 ?>
