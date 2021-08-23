<?php
class FTP
{
  function __construct($ftp)
  {
    $this->ftp = $ftp;
    $this->server = $ftp['sever'];

    if(empty($this->ftp)){
      return '[FTP] Brak danych ftp..';
    }

    if(empty($this->ftp['ftp_user'])){
      return '[FTP] Brak Loginu do połączenia FTP';
    }
    if(empty($this->ftp['ftp_password'])){
      return '[FTP] Brak Hasła do połączenia FTP';
    }
    if(empty($this->ftp['ftp_host'])){
      return '[FTP] Brak Serwera do połączenia FTP';
    }

    $this->conn = $this->connect();
  }

  function connect()
  {
    $conn = ftp_connect($this->ftp['ftp_host']);
    if($conn == false){
      // Logs::ftpServer($serwer, "?x=cronjobs_serwer", "[FTP] Błąd połaczenia (Brak odpowiedzi serwera)", "FTP connection has failed! Attempted to connect to $ftp->serwer");
  		return '[FTP] Błąd połaczenia ftp';
    }

    $login = ftp_login($conn, $this->ftp['ftp_user'], $this->ftp['ftp_password']);
    if(!$login){
      // Logs::ftpServer($serwer, "?x=cronjobs_serwer", "[FTP] Błąd połaczenia (Złe Hasło/Login)", "FTP connection has failed! Attempted to connect to $ftp->serwer for user $ftp->user");
      return '[FTP] Błąd logowania, złe hasło lub login..';
    }
    return $conn;
  }

  function fileList($folder='/', $type='nlist')
  {
    if($type == 'nlist'){
      return ftp_nlist($this->conn, $folder);
    }
    if($type == 'rawlist'){
      return ftp_rawlist($this->conn, $folder);
    }
  }

  /*
    [
    'ftp_directory' => 'katalog do którego ma być wgrany plik',
    'ftp_dest_file_name' => 'nazwa pliku wgrywanego na serwer ftp',
    'ftp_source_file_name' => 'lokalizacja pliku na serwerze acp',
    'type_upload' => 'FTP_ASCII lub FTP_BINARY',
    'modul' => "nazwa modulu z którego generowany jest plik np. ?x=uslugi",
    'info_wykonanie' => 'nazwa ustawienia zapamiętującego datę wykonania',
    'special_table' => "nazwa tabeli w brazie danych gdzie jest zlokalizowana info_wykonanie, domyślnie acp_system"
    ]
  */
  function upload($file)
  {
    $this->directory($file['ftp_directory']);

    ftp_pasv($this->conn, true);

    if($file['type_upload'] == 'FTP_ASCII'){
      $upload = ftp_put($this->conn, $file['ftp_directory'].'/'.$file['ftp_dest_file_name'], $file['ftp_source_file_name'], FTP_ASCII);
    }
    if($file['type_upload'] == 'FTP_BINARY'){
      $upload = ftp_put($this->conn, $file['ftp_directory'].'/'.$file['ftp_dest_file_name'], $file['ftp_source_file_name'], FTP_BINARY);
    }

    if(!$upload){
      // Logs::ftpServer($value->serwer_id, $value->modul, "[FTP] Przesłanie pliku ".$file['ftp_dest_file_name']." nie powiodło się", "$ftp->serwer: Wysłanie pliku $value->ftp_dest_file_name nie zrealizowane poprawnie");

      // dodatkowe powiadomienie wgryawrki ze sie nie udało wgrać pliku
      if(isset($file['wgrywarka_file_id'])){
        SQL::query("UPDATE `acp_wgrywarka` SET `status` = '-1' WHERE `id` = ".$file['wgrywarka_file_id']."; ");
      }

      return false;
    }

    if(isset($file['info_wykonanie']) && empty($file['special_table'])){
      $this->UpdateDateCONF($file['info_wykonanie']);
    }
    elseif(isset($file['info_wykonanie'])){
      $this->UpdateDateCONF($file['info_wykonanie'], $file['special_table']);
    }

    if(isset($file['wgrywarka_file_id'])){
      SQL::query("UPDATE `acp_wgrywarka` SET `status` = '1' WHERE `id` = ".$file['wgrywarka_file_id']."; ");
    }

    return true;
  }

  /*
    [
    'katalog' => 'katalog do skanowania',
    'type' => 'nlist lub rawlist',
    'acp_cache_api' => 'nazwa wiersza w tabli z cache',
    'info_wykonanie' => 'nazwa ustawienia zapamiętującego datę wykonania'
    ]
  */
  function scan($scan)
  {
    $data = $this->fileList($scan['katalog'], $scan['type']);

    SQL::query("DELETE FROM `acp_cache_api` WHERE `acp_cache_api`.`get` = ".$scan['acp_cache_api']."; ");
    SQL::insert('acp_cache_api', [
      'get' => $scan['acp_cache_api'],
      'dane' => json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR)
    ]);

    if( isset( $scan['info_wykonanie'] ) )
    {
      UpdateDateCONF($scan['info_wykonanie']);
    }

    return;
  }

  function directory($directory = '')
  {
    if(empty($directory)){
      return;
    }

    if(strlen($directory) > 0){
      $fullpath = "";

      foreach(explode("/", $directory) as $part){
        if(empty($part)){
          $fullpath .= "/";
          continue;
        }
        $fullpath .= $part."/";
        if( @ftp_chdir($this->conn, $fullpath) ){
           ftp_chdir($this->conn, $fullpath );
        }
        else {
          if( @ftp_mkdir($this->conn, $part) ){
            ftp_chdir($this->conn, $part);
          }
        }
      }
      // Logs::ftpServer($value->serwer_id, $value->modul, "[FTP] Wystąpił problem ze zmianą katalogu przy wgrywaniu pliku $value->ftp_dest_file_name", "Błąd zmiany katalogu ($value->ftp_directory), pliku $value->ftp_dest_file_name na serwerze ftp $ftp->serwer [Serwer ID: $serwer]");
    }


    // powrót do katalogu pierwszego.
    $aPath = explode('/',ftp_pwd($this->conn));
    $sHomeDir = str_repeat('../', count($aPath) - 1);
    ftp_chdir($this->conn, $sHomeDir);
  }

  function UpdateDateCONF($conf_name, $special_table='acp_system')
  {
    SQL::query("UPDATE `$special_table` SET `conf_value` = '".date("Y-m-d H:i:s")."' WHERE `$special_table`.`conf_name` = '$conf_name';");
  }

  function __destruct()
  {
   ftp_close($this->conn);
   return;
 }
}
 ?>
