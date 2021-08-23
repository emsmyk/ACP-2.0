<?php
class DefaultController
{
  function __construct()
  {
    $this->db = DB::getInstance();
  }

  function index()
  {
    $lastWpis = $this->db->get_row("SELECT *, (SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS steam_avatar, (SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS login, (SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS steam_login, (SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = `kategoria`) AS kategoria_nazwa FROM `acp_wpisy` WHERE `ogloszenie` = '1' ORDER BY `id` DESC LIMIT 1", true);

    return [
      'LastWpis' => $lastWpis,
      'LastWpisComents' => $this->db->get_row("SELECT COUNT(*) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $lastWpis->id")[0],
      'LastWpisComentsUsers' => $this->db->get_row("SELECT COUNT(DISTINCT `user_id`) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $lastWpis->id")[0],

      'FiveLastWpis' => $this->db->get_results("SELECT `id`, `tytul`, `data`  FROM `acp_wpisy` WHERE `ogloszenie` = 1 ORDER BY `data` DESC LIMIT 5", true),
    ];
  }
}
?>
