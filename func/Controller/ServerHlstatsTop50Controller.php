<?php
class ServerHlstatsTop50Controller
{
  function __construct()
  {
    $this->hlx_top50 = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50' LIMIT 1");
    $this->hlx_top_rangi = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top_rangi' LIMIT 1");
    $this->hlx_ilosc = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_ilosc' LIMIT 1");
    $this->hlx_top50_tag_tabela = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_tag_tabela' LIMIT 1");
    $this->hlx_top50_tag_say = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_tag_say' LIMIT 1");
    $this->hlx_top50_color_tag = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_color_tag' LIMIT 1");
    $this->hlx_top50_color_nick = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_color_nick' LIMIT 1");
    $this->hlx_top50_color_tekst = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'hlx_top50_color_tekst' LIMIT 1");
  }

  public function top50($serwer_id){
    if($this->hlx_top50 != '1'){
      return;
    }

    $server_hls = SQL::one("SELECT `prefix_hls` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id LIMIT 1");
    if(empty($server_hls)){
      return 'brak prefixu hlstats serwera ';
    }

    $data = Model('Hlstats')->top50($server_hls);
    $dataTop50 = preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($data));

    SQL::insert('acp_serwery_hlstats_top',[
      'serwer_id' => $serwer_id,
      'data' => date("Y-m-d H:i:s"),
      'dane' => $data
    ]);

    //
    // nadanie top3 rang
    //
    if($this->hlx_top_rangi != '1'){
      return "<p>HLstatsX Top50: Nagrody dla top X zostały wyłączone</p>";
    }

    $i=0;
    $top=1;
    foreach ($data as $dane) {
      if($i < $this->hlx_ilosc) {
        SQL::insert('acp_serwery_hextags',[
          'serwer_id' => $serwer_id,
          'hextags' => 'STEAM_0:'.$dane->steam,
          'ScoreTag' => '♜ '.$this->hlx_top50_tag_tabela.$top,
          'TagColor' => $this->hlx_top50_color_tag,
          'ChatTag' => '♜ '.$this->hlx_top50_tag_say.$top,
          'ChatColor' => $this->hlx_top50_color_nick,
          'NameColor' => $this->hlx_top50_color_tekst,
          'Force' => '0',
          'istotnosc' => '2',
          'czasowa' => '1',
          'czasowa_end' => 'NOW()+INTERVAL 1 DAY',
          'komentarz' => 'Hlx Top50 ('.$dane->lastName.')',
        ]);
        $i +=1;
        $top +=1;
      }
    }
    return "<p>HLstatsX Top50: Przyznano nagrody za top x (SERWER ID: $serwer_id)</p>";
  }

}
?>
