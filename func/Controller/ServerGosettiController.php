<?php
class ServerGosettiController
{
  function stats($serwer_id){
    $serwer = Model('Server')->basic($serwer_id)->adress;

    $gosetti = file_get_html("https://gosetti.pl/serwery/$serwer");

    $info['gosetti_rank'] = $gosetti->find(".greenbar-top > div > .description, .greenbar-top > div > .value",1)->innertext;
    $info['gosetti_rank_tura'] = $gosetti->find(".greenbar-top > div > .description, .greenbar-top > div > .value",3)->innertext;
    $info['gosetti_tura_klik'] = str_replace(' ', '', $gosetti->find(".greenbar-bottom > div > .text > .row-bigger",0)->innertext);
    $info['gosetti_tura_skiny'] = str_replace(' ', '', $gosetti->find(".greenbar-bottom > div > .text > .row-bigger",1)->innertext);
    $info['gosetti_tura_wpl'] = str_replace(' ', '', $gosetti->find(".greenbar-bottom > div > .text > .row-bigger",2)->innertext);
    $info['gosetti_tura_www'] = str_replace(' ', '', $gosetti->find(".greenbar-bottom > div > .text > .row-bigger",3)->innertext);

    SQL::insert('acp_serwery_gosetti',[
      'serwer_id' => $serwer_id,
      'data' => date("Y-m-d H:i:s"),
      'gosetti_rank_all' => $info['gosetti_rank'],
      'gosetti_rank_tura' => $info['gosetti_rank_tura'],
      'gosetti_p_klik_tura' => $info['gosetti_tura_klik'],
      'gosetti_p_skiny_tura' => $info['gosetti_tura_skiny'],
      'gosetti_p_pln_tura' => $info['gosetti_tura_wpl'],
      'gosetti_p_www_tura' => $info['gosetti_tura_www'],
    ]);

  }

  function wykres($server, $count)
  {
    $retrun = new stdClass();
    $gosettiData = SQL::all("SELECT * FROM `acp_serwery_gosetti` WHERE `serwer_id` = $server ORDER BY `data` DESC LIMIT $count");
    foreach ($gosettiData as $value) {
      $retrun->rank_all .= $retrun->rank_all .'"'. $value->gosetti_rank_all .'", ';
      $retrun->rank_tura .= $retrun->rank_tura .'"'. $value->gosetti_rank_tura .'", ';
      $retrun->punkty_klikniecia .= $retrun->punkty_klikniecia .'"'. $value->gosetti_p_klik_tura .'", ';
      $retrun->punkty_skiny .= $retrun->punkty_skiny .'"'. $value->gosetti_p_skiny_tura .'", ';
      $retrun->punkty_pln .= $retrun->punkty_pln .'"'. $value->gosetti_p_pln_tura .'", ';
      $retrun->punkty_www .= $retrun->punkty_www .'"'. $value->gosetti_p_www_tura .'", ';
      $retrun->data .= $retrun->data .' "'. $value->data .'", ';
    }
    return $retrun;
  }
}
 ?>
