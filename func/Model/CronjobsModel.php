<?php
class CronjobsModel
{
  /*
    Funkcja sprawdzająca czy jest czas na wykonanie danego cron'a
  */
  function ThisTime($cron, $time)
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
  function UpdateTime($conf_name, $date = '')
  {
    $date = (empty($date)) ? date("Y-m-d H:i:s") : $date;

    Controller('Ustawienia')->updateConf([
      ['name' => $conf_name, 'value' => $date]
    ]);
  }

  /*
    Funkcja poprawiająca dziwne znakczki np w nazwach graczy
  */
  function jsonRemoveUnicodeSequences($struct) {
    return $this->raw_json_encode($struct);
  }

  function raw_json_encode($input, $flags = 0) {
    $fails = implode('|', array_filter(array(
        '\\\\',
        $flags & JSON_HEX_TAG ? 'u003[CE]' : '',
        $flags & JSON_HEX_AMP ? 'u0026' : '',
        $flags & JSON_HEX_APOS ? 'u0027' : '',
        $flags & JSON_HEX_QUOT ? 'u0022' : '',
    )));
    $pattern = "/\\\\(?:(?:$fails)(*SKIP)(*FAIL)|u([0-9a-fA-F]{4}))/";
    $callback = function ($m) {
        return html_entity_decode("&#x$m[1];", ENT_QUOTES, 'UTF-8');
    };
    return preg_replace_callback($pattern, $callback, json_encode($input, $flags));
}

}
?>
