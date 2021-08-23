<?php
class KonkurencjaController
{
  function __construct()
  {
    $this->db = DB::getInstance();

    $this->CoIle = array('-5 minutes' => 'co 5 minut', '-10 minutes' => 'co 10 minut', '-30 minutes' => 'co 30 minut');
    $this->colors = array('default' => 'Podstawowy', 'primary' => 'Niebieski', 'info' => 'Niebieski Jasny', 'success' => 'Zielony', 'yellow' => 'Żółty', 'danger' => 'Czerwony', 'gray' => 'Szary', 'navy' => 'Ciemny Niebieski', 'teal' => 'Herbaciany', 'purple' => 'Fioletowy', 'orange' => 'Pomarańczowy', 'maroon' => 'Różowy', 'black' => 'Czarny');
  }

  function rss($kanal_rss, $nazwa_pliku, $czas_cache = '-60 minutes', $ilość_rss = 10)
  {
    $file = "www/rss/".$nazwa_pliku.".htm";
    if(is_file($file) && (date('d-m-Y H:i:s', filemtime($file))) >=  date('d-m-Y H:i:s', strtotime($czas_cache))){
      return;
    }
    else {
      $doc = new DOMDocument();
      $doc->load($kanal_rss);
      $i=0;

      if(is_file($file)){
        $wyczysc = file_put_contents($file, '');
      }
      $fp = fopen($file, "w+");
      $array = array();
      foreach ($doc->getElementsByTagName('item') as $node) {
        if($i>=$ilość_rss){
          break;
        }
        $i++;
        array_push($array,
          array (
            'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
            'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
            'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
            'pubDate' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
            'pubDate_srt' => strtotime($node->getElementsByTagName('pubDate')->item(0)->nodeValue),
            'code' => $nazwa_pliku,
            )
        );
      }
      $array = json_encode($array);
      fwrite($fp, $array);
      fclose($fp);
      return;
    }
  }

  function update($dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'nazwa' => 'reg'
    ],[
      'nazwa.reg' => 'Pole nazwa jest wymagane'
    ]);

    $from->dane_id = $this->db->get_row("SELECT * FROM `acp_konkurencja` WHERE `id` = '$from->id' LIMIT 1", true);

    if($this->db->exists('acp_konkurencja', 'id', [ 'nazwa' => $from->nazwa ]) ){
      return Messe::array([
        'type' => 'danger',
        'text' => "Strona o takiej nazwie nie istnieje"
      ]);
    }

    $this->db->update('acp_konkurencja',
    [
      'nazwa' => $from->nazwa,
      'color' => $from->kolor,
      'url' => $from->url,
      'ilosc' => $from->ilosc,
      'dane_time' => $from->aktualizacja
    ],[
      'id' => $from->id
    ]);

    Logs::log("Zedytowano stronę $from->nazwa (ID: $from->id)", "?x=konkurencja");
  }
  function store($dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'nazwa' => 'reg'
    ],[
      'nazwa.reg' => 'Pole nazwa jest wymagane'
    ]);

    if($this->db->exists('acp_konkurencja', 'id', [ 'nazwa' => $from->nazwa ]) ){
      return Messe::array([
        'type' => 'danger',
        'text' => "Strona o takiej nazwie nie istnieje"
      ]);
    }

    $this->db->insert('acp_konkurencja',[
        'nazwa' => $from->nazwa,
        'color' => $from->kolor,
        'code'=> Text::clean($from->nazwa),
        'url' => $from->url,
        'ilosc' => $from->ilosc,
        'dane_time' => $from->aktualizacja
      ]);
    Logs::log("Dodano nową stronę $from->nazwa (ID: ".$this->db->lastid.")", "?x=konkurencja");
  }
  function destroy($id, $dostep)
  {
    Permission::check($dostep);

    $id = (int)$id;
    $strona = $this->db->get_row("SELECT `nazwa`, `code` FROM `acp_konkurencja` WHERE `id` = $id LIMIT 1", true);

    $db = DB::getInstance();
    $db->delete('acp_konkurencja', ['id' => $id], 1);

    Logs::log("Strona  $strona->nazwa [code: $strona->code] (ID: $id) została usunięta", "?x=konkurencja");
  }
  function usun_cache($dostep)
  {
    Permission::check($dostep);

    $files = glob('www/rss/*');
    foreach($files as $file){
      if(is_file($file))
        unlink($file);
    }
  }

}
 ?>
