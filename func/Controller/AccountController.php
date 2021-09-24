<?php
class AccountController
{
  function __construct() {
		$this->id = Get::int('id');
    $this->db = DB::getInstance();
	}

  public function index($id)
  {
    if(empty($id) || $id == 0){
      redirect("?x=wpisy");
    }

    $user = $this->db->get_row("SELECT `user`, `login`, `role`, `grupa`, `last_login`, `data_rejestracji`, `urodziny`, `banned`, `cash`, `ulubiony_serwer`, `lokalizacja`, `wyksztalcenie`, wirepusher,  `steam`, `steam_update`, `steam_avatar`, `steam_login`, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `ulubiony_serwer` LIMIT 1) AS `ulubiony_serwer_nazwa`, (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa` lIMIT 1) AS `nazwa_grupy`, (SELECT COUNT(*) FROM `acp_wpisy` WHERE `u_id` = `user`) AS `ilosc_wpisow` FROM `acp_users` WHERE `user` = $this->id;", true);

    if(empty($user->user)){
      redirect("?x=wpisy");
    }

    $activiti = [];

    $wpisy = $this->db->get_results("SELECT *, (SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = `kategoria`) AS `kategoria_nazwa`, (SELECT COUNT(*) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = `id`) AS `komentarzy` FROM `acp_wpisy` WHERE `u_id` = $this->id ORDER BY `id` DESC LIMIT 10", true);
    foreach ($wpisy as $value) {
      $activiti[] = [
        'user' => $value->u_id,
        'description' => $value->kategoria_nazwa,
        'more_right' => '<i class="fa fa-comments-o margin-r-5"></i> '. $value->komentarzy .' Komentarzy',
        'text' => str_replace(array("\r\n", "\n", "\r"), "<br>", Text::limit(strip_tags($value->text), 1500)),
        'link' => '?x=wpisy&xx=wpis&wpis='. Text::clean($value->tytul) .'&wpisid='.$value->id,
        'data' => $value->data
      ];
    }

    foreach (Controller('Logi')->index(
      ['hide' => 0, 'sort' => 1, 'sort_type' => 'DESC', 'sort_column' => 'id', 'limit' => 1, 'limit_count' => 10 ]
    ) as $key => $value) {
      if($value->user = $this->id){
        $activiti[] = [
          'user' => $value->user,
          'description' => 'Moduł: '.$value->page,
          'more_right' => '',
          'text' => str_replace(array("\r\n", "\n", "\r"), "<br>", Text::limit(strip_tags($value->tekst), 1500)),
          'link' => $value->link,
          'data' => $value->data
        ];
      }
    }

    return [
      'user' => $user,
      'activiti' => $this->array_msort($activiti, [ 'data' => SORT_DESC ])
    ];
  }

  public function get_browser_name($user_agent)
  {
    $t = strtolower($user_agent);
    $t = " " . $t;

    if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return '<i class="fa fa-fw fa-opera"></i> Opera'            ;
    elseif (strpos($t, 'edge'      )                           ) return '<i class="fa fa-fw fa-internet-explorer"></i> Edge'             ;
    elseif (strpos($t, 'chrome'    )                           ) return '<i class="fa fa-fw fa-chrome"></i> Chrome'           ;
    elseif (strpos($t, 'safari'    )                           ) return '<i class="fa fa-fw fa-safari"></i> Safari'           ;
    elseif (strpos($t, 'firefox'   )                           ) return '<i class="fa fa-fw fa-firefox"></i> Firefox'          ;
    elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return '<i class="fa fa-fw fa-internet-explorer"></i> Internet Explorer';
    elseif (strpos($t, 'google'    )                           ) return '[Bot] Googlebot'   ;
    elseif (strpos($t, 'bing'      )                           ) return '[Bot] Bingbot'     ;
    elseif (strpos($t, 'slurp'     )                           ) return '[Bot] Yahoo! Slurp';
    elseif (strpos($t, 'duckduckgo')                           ) return '[Bot] DuckDuckBot' ;
    elseif (strpos($t, 'baidu'     )                           ) return '[Bot] Baidu'       ;
    elseif (strpos($t, 'yandex'    )                           ) return '[Bot] Yandex'      ;
    elseif (strpos($t, 'sogou'     )                           ) return '[Bot] Sogou'       ;
    elseif (strpos($t, 'exabot'    )                           ) return '[Bot] Exabot'      ;
    elseif (strpos($t, 'msn'       )                           ) return '[Bot] MSN'         ;
    elseif (strpos($t, 'mj12bot'   )                           ) return '[Bot] Majestic'     ;
    elseif (strpos($t, 'ahrefs'    )                           ) return '[Bot] Ahrefs'       ;
    elseif (strpos($t, 'semrush'   )                           ) return '[Bot] SEMRush'      ;
    elseif (strpos($t, 'rogerbot'  ) || strpos($t, 'dotbot')   ) return '[Bot] Moz or OpenSiteExplorer';
    elseif (strpos($t, 'frog'      ) || strpos($t, 'screaming')) return '[Bot] Screaming Frog';
    elseif (strpos($t, 'facebook'  )                           ) return '[Bot] Facebook'     ;
    elseif (strpos($t, 'pinterest' )                           ) return '[Bot] Pinterest'    ;
    elseif (strpos($t, 'crawler' ) || strpos($t, 'api'    ) ||
            strpos($t, 'spider'  ) || strpos($t, 'http'   ) ||
            strpos($t, 'bot'     ) || strpos($t, 'archive') ||
            strpos($t, 'info'    ) || strpos($t, 'data'   )    ) return '[Bot] Other'   ;

    return 'Inna (Nieznana)';
  }

  public function user_password()
  {
    $from = From::check([
      'haslo' => 'reg',
    ],[
      'haslo.reg' => 'Nie poprawne hasło. Aby zedytować profil wpisz poprawne hasło logowania..',
    ]);

    $from->user = getPlayer(User::get());
    $from->haslo = md5($from->haslo);
    $from->new_haslo = md5($from->new_haslo);
    $from->steam = $Steam->toCommunityID($from->steam);

    if($from->haslo != $from->user->pass) {
      return Messe::array([
        'type' => 'warning',
        'text' => "Nie poprawne hasło. Aby zedytować profil wpisz poprawne hasło logowania.."
      ]);
    }

    $this->db->update('acp_users',[
      'pass' => $from->new_haslo,
      'urodziny' => $from->urodziny,
      'ulubiony_serwer' => $from->ulubiony_serwer,
      'wyksztalcenie' => $from->wyksztalcenie,
      'lokalizacja' => $from->lokalizacja,
      'steam' => $from->steam,
      'wirepusher' => $from->wirepusher,
    ], [
      'user' => $from->user->user
    ]);

    return Messe::array([
      'type' => 'success',
      'text' => 'Zaktualizowano twoje ustawienia'
    ]);
  }

  function array_msort($array, $cols)
  {
      $colarr = array();
      foreach ($cols as $col => $order) {
          $colarr[$col] = array();
          foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
      }
      $eval = 'array_multisort(';
      foreach ($cols as $col => $order) {
          $eval .= '$colarr[\''.$col.'\'],'.$order.',';
      }
      $eval = substr($eval,0,-1).');';
      eval($eval);
      $ret = array();
      foreach ($colarr as $col => $arr) {
          foreach ($arr as $k => $v) {
              $k = substr($k,1);
              if (!isset($ret[$k])) $ret[$k] = $array[$k];
              $ret[$k][$col] = $array[$k][$col];
          }
      }
      return $ret;

  }
}
?>
