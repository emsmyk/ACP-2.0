<?php
class ChangelogController
{
  function __construct()
  {
    $this->db = DB::getInstance();
  }

  function index()
  {
    return $this->db->get_results("SELECT *, `user` AS `user_id`, (SELECT `login` FROM `acp_users` WHERE `user` = `user_id` LIMIT 1) AS `user_name` FROM `acp_log_serwery`; ", true);
  }

  function store($co)
  {
    $from = From::check([
      'nick' => 'reg',
    ],[
      'nick.reg' => 'Pole Nick nie może być puste..'
    ]);

    switch ($co) {

      case 'awans_deg_rez':
        $from->steam = $Steam->toCommunityID($from->steam);
        if(!is_numeric($from->steam)) {
          return Messe::array([
            'type' => 'warning',
            'text' => "Changelog: Nie poprawny steam id"
          ]);
        }

        if($from->czynnosc == 0){
          return Messe::array([
            'type' => 'success',
            'text' => "Changelog: Wybierz czynność.."
          ]);
        }
        switch ($from->czynnosc) {
          case 1:
            Logs::server("Przyznano Awans $from->nick (ID: $from->steam)", $from->serwer);
            break;
          case 2:
            Logs::server("Zdegradowano Admina $from->nick (ID: $from->steam)", $from->serwer);
            break;
          case 3:
            Logs::server("Admin $from->nick zrezygnował z funkcji. (ID: $from->steam)", $from->serwer);
            break;
        }
        break;
      case 'wlasny':
        if(empty($from->tekst) || empty($from->data)) {
          return Messe::array([
            'type' => 'warning',
            'text' => "Changelog: Pole tekst lub data nie może być puste.."
          ]);
        }

        Logs::server($from->tekst, $from->serwer, 0, $from->data);
        break;
    }
    return;
  }

  function edit($id)
  {
    return $this->db->get_row("SELECT * FROM `acp_log_serwery` WHERE `id` = $id LIMIT 1;", true);
  }

  function update($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check();

    $this->db->update('acp_log_serwery',[
        'tekst' => $from->tekst,
        'data' => $from->data
      ],[
        'id' => $id
      ]);

    Logs::log("Zaktualizowano wpis chanelogu ID: $from->id", "?x=changelog_edit&xx=&edycja=$from->id");
  }

  function destroy($id, $dostep)
  {
    Permission::check($dostep);

    $db = DB::getInstance();
    $db->delete('acp_log_serwery', ['id' => $id], 1);

    Logs::log("Usunięto wpis w changelog ID: $id", "?x=changelog_edit");
  }
}
  ?>
