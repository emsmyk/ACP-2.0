<?php
class GaleriaMapController
{
  function __construct()
  {
    $this->db = DB::getInstance();

    $this->DefaultImg = $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'galeria_map_noimage' LIMIT 1")[0];

    $this->UstGaleriaMap_wymiary_on = $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'GaleriaMap_wymiary_on' LIMIT 1")[0];
    $this->UstGaleriaMap_wymiary_szerokosc = $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'GaleriaMap_wymiary_szerokosc' LIMIT 1")[0];
    $this->UstGaleriaMap_wymiary_wysokosc = $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'GaleriaMap_wymiary_wysokosc' LIMIT 1")[0];
    $this->UstGaleriaMap_znak_on = $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'GaleriaMap_znak_on' LIMIT 1")[0];
    $this->UstGaleriaMap_znak_tekst_kolor = $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'GaleriaMap_znak_tekst_kolor' LIMIT 1")[0];
    $this->UstGaleriaMap_znak_tekst_wielkosc = $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'GaleriaMap_znak_tekst_wielkosc' LIMIT 1")[0];
    $this->UstGaleriaMap_znak_tekst_kolor = $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'GaleriaMap_znak_tekst_kolor' LIMIT 1")[0];
    $this->UstGaleriaMap_znak_tekst = $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'GaleriaMap_znak_tekst' LIMIT 1")[0];
  }

  function indexPublic($server)
  {
    $galeria_map = $this->db->get_row("SELECT `serwer_id` AS `srv_id`, `nazwa`, `mod`, (SELECT `id` FROM `acp_serwery_mapy` WHERE `serwer_id` = `srv_id` LIMIT 1) AS `id_grupy_map` FROM `acp_serwery` WHERE `serwer_on` = 1 AND `serwer_id` = $server", true);
    $lista_map_q = $this->db->get_results("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $galeria_map->id_grupy_map", true);
    foreach ($lista_map_q as $lista_map):
      $galeria_map->mapa_id[] = $lista_map->id;
      $galeria_map->mapa_nazwa[] = $lista_map->nazwa;
    endforeach;

    foreach ($galeria_map->mapa_id as $key => $value) {
      $img = $this->db->get_row("SELECT `imgur_url` FROM `acp_serwery_mapy_img` WHERE `id_mapy` = $value LIMIT 1")[0];
      $img = (empty($img)) ? $this->DefaultImg : $img;
      $galeria_map->mapa_img[] = $img;
    }

    return $galeria_map;
  }

  function store($dostep)
  {
    Permission::check($dostep);

    $from = From::check();
    $img = $_FILES['img'];

    if($img['name']==''){
      return Messe::array([
        'type' => 'warning',
        'text' => "Wybierz obrazek do wgrania."
      ]);
    }



    // Wgranie go do katalogu, nazwa pliku po edycji
    $target = "www/galeria_map/".$img['name'];
    move_uploaded_file($img['tmp_name'], $target);
    $save = "www/galeria_map/resize_" . $img['name']; //This is the new file you saving
    $water_mark = "www/galeria_map/watermark_" . $img['name']; //This is the new file you saving

    // Zmiana rozmarów obrazu mapy
    if($this->UstGaleriaMap_wymiary_on == 1){
      list($width, $height) = getimagesize($target);

      $tn = imagecreatetruecolor($this->UstGaleriaMap_wymiary_szerokosc, $this->UstGaleriaMap_wymiary_wysokosc) ;
      $image = imagecreatefromjpeg($target) ;
      imagecopyresampled($tn, $image, 0, 0, 0, 0, $this->UstGaleriaMap_wymiary_szerokosc, $this->UstGaleriaMap_wymiary_wysokosc, $width, $height) ;

      imagejpeg($tn, $save, 100);
      imagedestroy($image);

      // adres pliku do wyslania
      $file_to_upload_imgur = $save;
    }
    // znak wodny
    if($this->GaleriaMap_znak_on == 1){
      $obrazek = (file_exists($save)) ? $save : $target;

      $image = imagecreatefromjpeg($obrazek);
      switch ($this->UstGaleriaMap_znak_tekst_kolor) {
        case 'white':
          $textcolor = imagecolorallocate($image, 255, 255, 255);
          break;
        case 'black':
          $textcolor = imagecolorallocate($image, 0, 0, 0);
          break;
        case 'grey':
          $textcolor = imagecolorallocate($image, 128, 128, 128);
          break;
      }
      $font_file = 'www/galeria_map/Roboto-Bold.ttf';
      $custom_text = "Watermark Text";
      imagettftext($image, $this->UstGaleriaMap_znak_tekst_wielkosc, 0, 0+25, 0+25, $textcolor, $font_file, $this->UstGaleriaMap_znak_tekst);
      imagejpeg($image, $water_mark, 100);
      imagedestroy($image);

      // adres pliku do wyslania
      $file_to_upload_imgur = $water_mark;
    }


    // Wgrywanie obrazka na imgura
    $url = Model('ImgurUploadFile')->upload($filename);

    if(empty($url)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Błąd, podczas wgrywania pliku. Spróbuj raz jeszcze!"
      ]);
    }

    if( $this->db->exists('acp_serwery_mapy_img', 'id', [ 'id_mapy' => $from->id ]) ){
      $this->db->update(
        'acp_serwery_mapy_img',
        [
          'imgur_url' => $url
        ],[
          'id_mapy' => $from->id
        ]);
    }
    else {
      $this->db->insert('acp_serwery_mapy_img',[
        'id_mapy' => $from->id,
        'imgur_url' => $url
      ]);
    }
    
    Logs::log("Zaktualizowano galerię mapy $from->mapa (ID: $from->id)", "?x=galeria_map&id=$from->id");

    // Skasowanie plików z acp
    unlink($water_mark);
    unlink($target);
    unlink($save);
  }
}

?>
