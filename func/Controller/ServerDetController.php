<?php
class ServerDetController
{

  function __construct()
  {

  }

  function editUstPodstawowe($server, $dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'mod' => 'reg|text'
    ],[
      'mod.reg' => 'Pole mod jest wymagane',
      'mod.text' => 'Pole mod musi być tekstem..'
    ]);

    SQL::update('acp_serwery', [
      'mod' => $from->mod,
      'czas_reklam' => $from->czas_reklam,
      'liczba_map' => $from->liczba_map,
      'fastdl' => $from->fastdl,
      'link_gotv' => $from->link_gotv,
      'ser_a_copiekun' => User::find($from->ser_a_copiekun),
     ],
     $server,
     'serwer_id'
    );
    Logs::log("Zaktualizowano ustawienia serwera $from->mod (ID: $server)", "?x=serwery_det&serwer_id=$server");
  }

  function get_table_serwer_file($i, $serwer_id, $dostep, $dostep_all)
  {
		if($dostep == 1 || $dostep_all == 1) {
			$tekst = '';
			switch ($i) {
				case 'rangi':
					$query = SQL::all("SELECT * FROM `acp_serwery_rangi` WHERE `serwer_id` IN (0, $serwer_id) AND `czasowa` != 1 ORDER BY `istotnosc` DESC");
					foreach ($query as $rec) {
						$rec->komentarz = (empty($rec->komentarz)) ? 'Brak' : $rec->komentarz;

						$tekst .= '<tr>';
						$tekst .= "<td>$rec->flags</td>";
						$tekst .= "<td>$rec->tag_tabela</td>";
						$tekst .= "<td>$rec->tag_say</td>";
						$tekst .= "<td>$rec->komentarz</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=rangi&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
					}
					break;
				case 'hextags':
					$query = SQL::all("SELECT * FROM `acp_serwery_hextags` WHERE `serwer_id` IN (0, $serwer_id) AND `czasowa` != 1 ORDER BY `istotnosc` DESC");
					foreach ($query as $rec) {
						$rec->komentarz = (empty($rec->komentarz)) ? 'Brak' : $rec->komentarz;

						$tekst .= '<tr>';
						$tekst .= "<td>$rec->hextags</td>";
						$tekst .= "<td>$rec->ScoreTag</td>";
						$tekst .= "<td>$rec->ChatTag</td>";
						$tekst .= "<td>$rec->komentarz</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=hextags&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
					}
					break;
				case 'reklamy':
					$gdzie_array = array('S' => 'Tekst w Say', 'C' => 'Tekst w Csay', 'M' => 'Menu');
					$query = SQL::all("SELECT * FROM `acp_serwery_reklamy` WHERE `serwer_id` IN (0, $serwer_id)");
					foreach ($query as $rec) {
						$rec->komentarz = (empty($rec->komentarz)) ? 'Brak' : $rec->komentarz;
						$rec->gdzie = $gdzie_array[$rec->gdzie];

						$tekst .= '<tr>';
						$tekst .= "<td>$rec->gdzie</td>";
						$tekst .= "<td>$rec->tekst</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=reklamy&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
					}
					break;
				case 'bazydanych':
					$query = SQL::all("SELECT * FROM `acp_serwery_baza` WHERE `serwer_id` IN (0, $serwer_id)");
					foreach ($query as $rec) {
						$rec->komentarz = (empty($rec->komentarz)) ? 'Brak' : $rec->komentarz;

						$tekst .= '<tr>';
						$tekst .= "<td>$rec->nazwa</td>";
						$tekst .= "<td>$rec->d_driver</td>";
						$tekst .= "<td>$rec->d_baze</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=baza&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
					}
					break;
				case 'mapy':
					$query = SQL::all("SELECT * FROM `acp_serwery_mapy` WHERE `serwer_id` IN (0, $serwer_id)");
					foreach ($query as $rec) {
						$tekst .= '<tr>';
						$tekst .= "<td>$rec->nazwa</td>";
						$tekst .= "<td>$rec->display_template</td>";
						$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=mapy&edycja=$rec->id'><i class='fa fa-search'></i></a></td>";
						$tekst .= '</tr>';
							$tekst .= '<table class="table table-hover">';
							$tekst .= '<tr>';
							$tekst .= '<th>Mapa</th>';
							$tekst .= '<th>Nazwa</th>';
							$tekst .= '<th>Max/Min Graczy</th>';
							$tekst .= '<th></th>';
							$tekst .= '</tr>';
							$query2 = SQL::all("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $rec->id");
								foreach ($query2 as $rec2) {
									$rec2->max_players = (empty($rec2->max_players)) ? '-' : $rec2->max_players ;
									$rec2->min_players = (empty($rec2->min_players)) ? '-' : $rec2->max_players ;
									$tekst .= "<td>$rec2->nazwa</td>";
									$tekst .= "<td>$rec2->display</td>";
									$tekst .= "<td>$rec2->max_players/$rec2->min_players</td>";
									$tekst .= "<td><a href='?x=serwery_konfiguracja&xx=mapy&edycja_mapy=$rec2->id'><i class='fa fa-search'></i></a></td>";
									$tekst .= '</tr>';
								}
							$tekst .= '</table>';
					}
					break;
			}
		}
		else {
			$tekst = "Nie posiadasz dostępu aby wyświetlić detale $i";
		}
		return $tekst;
	}

  /*
  FUNKCJA DO PRZENIESIENIA DO ODDZIELNEGO MODULU GENERUJACEGO DANE DO WYKRESOW
  */
  function wykres_pobierz_dane($i='', $co='', $jakie=NULL, $serwer_id=0, $ile=5){
    switch ($i) {
      case 'wykres_gosetti':
        $retrun = [];
        $gosettiData = SQL::all("SELECT * FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
        foreach ($gosettiData as $value) {
          $retrun['data'][] = $value->data;
          $retrun['rank_all'][] = $value->gosetti_rank_all;
          $retrun['rank_tura'][] = $value->gosetti_rank_tura;
          $retrun['punkty_klikniecia'][] = $value->gosetti_p_klik_tura;
          $retrun['punkty_skiny'][] = $value->gosetti_p_skiny_tura;
          $retrun['punkty_pln'][] = $value->gosetti_p_pln_tura;
          $retrun['punkty_www'][] = $value->gosetti_p_www_tura;
        }
        return $retrun[$co];
      break;

      case 'wykres_graczy_morris':
        $query = SQL::all("SELECT `graczy`, `sloty`, `data` FROM `acp_serwery_logs_$jakie` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
        foreach ($query as $dane) {
          switch ($jakie) {
            case 'hour':
              $dane->data = substr($dane->data, 0, -3);
              break;
            case 'day':
              $dane->data = substr($dane->data, 0, -8);
              break;
            case 'month':
              $dane->data = substr($dane->data, 0, -9);
              break;
          }
          $dane->wolne_sloty = round($dane->sloty - $dane->graczy);
          $dane_zwort = $dane_zwort . "{y: '$dane->data', item1: $dane->graczy, item2: $dane->wolne_sloty},";
        }
        return $dane_zwort;
        break;

      case 'wykres_hlstats':
        $query = SQL::all("SELECT `hls_graczy`, `hls_nowych_graczy`, `hls_zabojstw`, `hls_nowych_zabojstw`, `hls_hs`, `hls_nowych_hs`, `data` FROM `acp_serwery_hlstats` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT 20");
        foreach ($query as $dane) {
          $dane_zwort = $dane_zwort . "{y: '$dane->data', item1: $dane->hls_graczy, item2: $dane->hls_nowych_graczy, item3: $dane->hls_zabojstw, item4: $dane->hls_nowych_zabojstw, item5: $dane->hls_hs, item6: $dane->hls_nowych_hs },";
        }
        return $dane_zwort;
        break;

      case 'gosetti':
        switch ($co) {
          case 'rank':
            $query = SQL::all("SELECT `gosetti_rank_all`, `gosetti_rank_tura`, `data` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
            foreach ($query as $dane) {
              $dane_zwort = $dane_zwort . "{y: '$dane->data', item1: $dane->gosetti_rank_all, item2: $dane->gosetti_rank_tura},";
            }
            return $dane_zwort;
            break;
          case 'punkty':
            $query = SQL::all("SELECT `gosetti_p_klik_tura`, `gosetti_p_skiny_tura`, `gosetti_p_pln_tura`, `gosetti_p_www_tura`, `data` FROM `acp_serwery_gosetti` WHERE `serwer_id` = $serwer_id ORDER BY `data` DESC LIMIT $ile");
            foreach ($query as $dane) {
              $dane_zwort = $dane_zwort . "{y: '$dane->data', item1: $dane->gosetti_p_klik_tura, item2: $dane->gosetti_p_skiny_tura, item3: $dane->gosetti_p_pln_tura, item4: $dane->gosetti_p_www_tura},";
            }
            return $dane_zwort;
            break;

        }
        break;
    }
  }
}
 ?>
