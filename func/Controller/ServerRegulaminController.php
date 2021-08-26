<?php
class ServerRegulaminController
{

  function __construct()
  {
    $this->db = DB::getInstance();
  }

  function edit($serwer)
  {
    $from = From::check();
    $from->tekst = htmlspecialchars($from->tekst);

    if( $this->db->exists('acp_serwery_regulamin', 'id', [ 'id' => $from->id ]) ){
      $this->db->update('acp_serwery_regulamin',[
        'tekst' => $from->tekst,
        'link' => $from->link
      ], [
        'serwer_id' => $serwer
      ]);

      Logs::log("Zaktualizowano regulamin (ID: $from->id) serwera ID: $serwer", "?x=serwery_det&serwer_id=$serwer");
      return;
    }

    $this->db->insert('acp_serwery_regulamin',[
      'serwer_id' => $serwer,
      'tekst' => $from->tekst,
      'link' => $from->link
    ]);

    Logs::log("Dodano nowy regulamin dla serwera ID: $serwer", "?x=serwery_det&serwer_id=$serwer");
    return;
  }
}
 ?>
