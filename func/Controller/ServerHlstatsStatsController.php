<?php
class ServerHlstatsController
{
  function __construct()
  {
    // code...
  }

  public function stats($serwer_id){
    $hlstats = SQL::one("SELECT `istotnosc` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id;");
    if($hlstats == '0' || empty($hlstats)) {
      return;
    }
    $hlstats = file_get_html("http://hlstats.sloneczny-dust.pl/hlstats.php?game=$hlstats");

    $hls_graczy = str_replace(',', '', $hlstats->find(".data-table-head b",0)->innertext );
		$hls_nowychgraczy = str_replace('+', '', str_replace(',', '', $hlstats->find(".data-table-head b",1)->innertext ) );
		$hls_zab = str_replace(',', '', $hlstats->find(".data-table-head b",2)->innertext );
		$hls_nowychzab = str_replace('+', '', str_replace(',', '', $hlstats->find(".data-table-head b",3)->innertext ) );
		$hls_hs = str_replace(',', '', $hlstats->find(".data-table-head b",4)->innertext );
		$hls_nowychhs = str_replace('+', '', str_replace(',', '', $hlstats->find(".data-table-head b",3)->innertext ) );

    SQL::insert('acp_serwery_hlstats', [
      'serwer_id' => $serwer_id,
      'data' => date("Y-m-d H:i:s"),
      'hls_graczy' => $hls_graczy,
      'hls_nowych_graczy' => $hls_nowychgraczy,
      'hls_zabojstw' => $hls_zab,
      'hls_nowych_zabojstw' => $hls_nowychzab,
      'hls_hs' => $hls_hs,
      'hls_nowych_hs' => $hls_nowychhs,
    ]);
  }
}
 ?>
