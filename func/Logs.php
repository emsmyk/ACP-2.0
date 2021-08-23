<?php
class Logs
{
  public static function log($text, $link='#', $user=null)
  {
    Messe::array([
      'type' => 'success',
      'text' => $text
    ]);

    SQL::insert('acp_log',[
        'page' => '?'.$_SERVER['QUERY_STRING'],
        'user' => User::get(),
        'tekst' => $text,
        'link' => $link,
      ]);

    return;
  }

  public static function server($text, $serwer=0, $user=null, $date='NOW')
  {
    $date = ($date == 'NOW')? date("Y-m-d H:i:s") : $date;
    SQL::insert(
      'acp_log_serwery',
      [
        'page' => '?'.$_SERVER['QUERY_STRING'],
        'serwer_id' => $serwer,
        'user' => User::get(),
        'tekst' => $tekst,
        'data' => $date
      ]
    );

    return;
  }

  public static function ftpServer($serverId, $modul, $text, $textAdmin)
  {
    SQL::insert('acp_serwery_bledy',[
        'serwer_id' => $serverId,
        'modul' => $modul,
        'tekst' => $text,
        'tekst_admin' => $textAdmin,
      ]);

    return;
  }
}
?>
