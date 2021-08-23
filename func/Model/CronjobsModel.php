<?php
class CronjobsModel
{
  /*
    Funkcja sprawdzająca czy jest czas na wykonanie danego cron'a
  */
  public function ThisTime($cron, $time)
  {
    if($time == 0) {
      return false;
    }

    if(strtotime($cron) < (time() - $time)){
      return true;
    } else {
      return false;
    }
  }

  /*
    Funkcja zmienia datę wykonania na aktualną
  */
  public function UpdateTime($conf_name, $date = '')
  {
    $date = (empty($date)) ? date("Y-m-d H:i:s") : $date;

    Controller('Ustawienia')->updateConf([
      ['name' => $conf_name, 'value' => $date]
    ]);
  }

  /*
    Funkcja poprawiająca dziwne znakczki np w nazwach graczy
  */
  public function jsonRemoveUnicodeSequences($struct) {
    return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
  }
}
?>
