<?php
class ServerUploadMapController
{

  function __construct()
  {
  }

  function upload($dostep){
    Permission::check($dostep);

    $from = From::check();

    $file_name = $_FILES['plik']['name'];
    $file_size = $_FILES['plik']['size'];
    $file_tmp = $_FILES['plik']['tmp_name'];
    $file_type = $_FILES['plik']['type'];
    $file_ext = strtolower(end(explode('.',$file_name)));

    if(!is_uploaded_file($file_tmp)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Błąd podczas przesyłania danych!"
      ]);
    }
    if ($file_size > 157286400) {
      return Messe::array([
        'type' => 'warning',
        'text' => "Plik jest za duży, maksymalna wielkości pliku to 157286400 bajtów"
      ]);
    }

    if($file_ext != 'bsp'){
      return Messe::array([
        'type' => 'warning',
        'text' => "Plik w złym formacie, skompresuj plik do formatu gz."
      ]);
    }

    $folder = File::is_dir('', "www/upload/maps", true);
    $nazwa_pliku = generujLosowyCiag(25);

    $katalog_nazwa_pliku = $folder.'/'.$nazwa_pliku.'.'.$file_ext;
    move_uploaded_file($file_tmp, $katalog_nazwa_pliku);

    $ftp_directory = SQL::one("SELECT `katalog` FROM `acp_serwery_cronjobs` WHERE `serwer` = $from->serwer_id LIMIT 1");

    Model('Wgrywarka')->new(
      $from->serwer_id,
      '[{"ftp_directory":"'.$ftp_directory.'/maps","ftp_source_file_name":"'.$katalog_nazwa_pliku.'","ftp_dest_file_name":"'.$file_name.'"}]',
      'Mapa:'.$file_name
    );

    Logs::log("Mapa $file_name została dodana do zadań Wgrywarki dla serwera ".Model('Server')->mod($from->serwer_id)." (ID: $from->serwer_id)");
  }
}
 ?>
