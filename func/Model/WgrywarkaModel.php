<?php
class WgrywarkaModel
{

  function __construct()
  {
    $this->user = User::get();
  }

  function new($servers = [], $file='', $name)
  {
    for ($i = 0; $i < count($servers); $i++):
      $last_insert = SQL::insert('acp_wgrywarka', [
        'serwer_id' => $servers[$i],
        'u_id' => $this->user,
        'nazwa' => $name,
        'kat' => 'Pluginy',
        'file' => $file
      ]);

      Logs::log("Wgrywaka: Zlecono pracę $name ID: $last_insert dla serwera ID:".$servers[$i]."");
    endfor;
  }
}
 ?>
