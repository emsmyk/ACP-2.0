<?php
class RoundsoundUploadFTPController
{
  function __construct()
  {
    $this->setting = [
      'rs_on' => SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_on';"),
      'rs_serwery' => json_decode( SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_serwery';") ),
      'rs_cron' => SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_cron';"),
      'rs_cron_utwory' => SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_cron_utwory';"),
      'rs_roundsound' => SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound'"),
      'rs_katalog' => SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_katalog'")
    ];
  }

  function SongList()
  {
    return json_decode( SQL::one("SELECT `lista_piosenek` FROM `rs_roundsound` WHERE `id` = ".$this->setting['rs_roundsound']." LIMIT 1") );
  }


}
 ?>
