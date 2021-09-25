<?php
class LogiController
{
  function __construct()
  {
    $this->db = DB::getInstance();
  }

  function zmien_ss_logi_zdalne($zmienna)
  {
    if($zmienna == 1) {
      $_SESSION['ss_acp_logi'] = 0;
    }
    else {
      $_SESSION['ss_acp_logi'] = 1;
    }
  }

  function index($array)
  {
    $where = '';
    $where .= ($array['where'] == 1) ? ' WHERE '.$array['where_query'] : '';
    $where .= ($array['hide'] == 1) ? ' WHERE `user` != 0' : '';
    $where .= ($array['sort'] == 1) ? ' ORDER BY `'. $array['sort_column'] .'` '.$array['sort_type'] : '';
    $where .= ($array['limit'] == 1) ? ' LIMIT '.$array['limit_count'] : '';
    $logs = $this->db->get_results("SELECT *,`user` AS `id_user`, (SELECT `login` FROM `acp_users` WHERE `user` = `id_user`) AS `nick` FROM `acp_log` $where", true);
    foreach ($logs as $log) {
      $log->nick = (empty($log->nick) || is_null($log->nick)) ? 'Praca Zdalna' : $log->nick;

      if(empty($log->page) || $log->page == '' || is_null($log->page))
      {
        $log->page = '-';
      }
      else {
        $log->new_page = str_replace("?x=", "", substr($log->page, 0, strpos($log->page, "&")));
        $log->new_page = $this->db->get_row('SELECT `nazwa_wys` FROM `acp_moduly` WHERE `nazwa` = "'.$log->new_page.'"; ', true);

        $log->page = (empty($log->new_page) || is_null($log->new_page)) ? 'Brak danych' : $log->new_page->nazwa_wys ;
      }

      $log->tekst = ($log->link == '#') ? $log->tekst : "<a href='$log->link'>$log->tekst</a>";
    }

    return $logs;
  }

}
?>
