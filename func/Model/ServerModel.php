<?php
class ServerModel
{
  public function __construct()
  {
    $this->db = DB::getInstance();
  }

  function exist($id, $redirect='?x=default')
  {
    $exist = $this->db->get_row("SELECT `serwer_id` FROM `acp_serwery` WHERE `serwer_id` = $id ");
    if(!$exist){
      Messe::array([
        'type' => 'warning',
        'text' => "Serwer ID: $id nie istnieje.. Zostałeś przenisiony na stronę główną."
      ]);
      return redirect($redirect);
    }
  }

  function mod($id)
  {
    if($id === 0){
      return '<i>Wszystkie</i>';
    }
    if( $this->db->exists('acp_serwery', 'serwer_id', ['serwer_id' => $id ]) ){
      return '<i>Serwer nie istnieje</i>';
    }

    $srv_data = $this->db->get_row("SELECT `serwer_id`, `mod` FROM `acp_serwery` WHERE `serwer_id` = $id");

    if(empty($srv_data->mod)){
      return '<i>Brak danych</i>';
    }

    return $srv_data->mod;
  }

  function basic($id)
  {
    $data = $this->db->get_row("SELECT `serwer_id`, `mod`, `ip`, `port`, `nazwa` FROM `acp_serwery` WHERE `serwer_id` = $id ", true);
    $data->adress = $data->ip.':'.$data->port;

    return $data;
  }

  function more($id)
  {
    return $this->db->get_row("SELECT `serwer_id`, `mod`, `ip`, `port`, `prefix_sb` FROM `acp_serwery` WHERE `serwer_id` = $id ", true);
  }

  function map_img($map)
  {
    $query = $this->db->get_row("SELECT `id` as `id_map`, (SELECT `imgur_url` FROM `acp_serwery_mapy_img` WHERE `id_mapy` = `id_map`) AS `img` FROM `acp_serwery_mapy_det` WHERE `nazwa` = '$map' LIMIT 1", true);

    if($query->img == '#' || is_null($query->img) || empty($query->img)){
      return 'https://acp.sloneczny-dust.pl/www/maps/nomap.jpg';
    }

    $src_headers = @get_headers($query->img);
    if($src_headers[0] == 'HTTP/1.1 404 Not Found') {
      $map = 'https://acp.sloneczny-dust.pl/www/maps/nomap.jpg';
    }

    return $query->img;
  }

  function kontrola($serwer_id)
  {
    $dane = $this->db->get_row("SELECT `serwer_id`, `mod`, `status`, `status_data`,`rcon`, `ftp_user`, `ftp_haslo`,`ftp_host` FROM `acp_serwery` WHERE `serwer_id` = $serwer_id LIMIT 1; ", true);

    $return = '';
    if($dane->status == 1){
      $return .= Messe::expanded($type='danger', $text='Serwer nie odpowiada! Sprawdź jego status..', $title='Serwer OFF!', $icon='fa fa-info');
    }
    if(!$dane->rcon) {
      $return .= Messe::expanded(
        $type = 'info',
        $text = 'Serwer '.$dane->mod.' (ID:'.$dane->serwer_id.') nie posiada podanego hasła RCON, aby je uzupełnić przejdź do <a href="?x=serwery_ust&edycja='.$serwer_id.'">Serwery Ustawienia</a>',
        $title = 'Kontrola Systemu - RCON',
        $icon = 'fa fa-info'
      );
    }
    if(!$dane->ftp_user || !$dane->ftp_haslo || !$dane->ftp_host){
      $return .= Messe::expanded(
        $type = 'danger',
        $text = 'Serwer '.$dane->mod.' (ID:'.$dane->serwer_id.') nie posiada skonfigurowanego połączenia FTP, aby je uzupełnić przejdź do <a href="?x=serwery_ust&edycja='.$serwer_id.'">Serwery Ustawienia</a>',
        $title = 'Kontrola Systemu - FTP',
        $icon = 'fa fa-info'
      );
    }

    return $return;
  }

  function dostep($server, $player)
  {
    $this->exist($server, '?x=serwery');

    if($this->db->get_row("SELECT `role` FROM `acp_users` WHERE `user` = $player", true) == 1){
      return;
    }

    if(empty( $this->db->get_row("SELECT `serwer_id` FROM `acp_serwery` WHERE `serwer_id` = $server AND `ser_a_jr` = $player OR  `ser_a_opiekun` = $player OR  `ser_a_copiekun` = $player LIMIT 1", true) )){
      header('Location: ?x=serwery');
    }
  }
}

?>
