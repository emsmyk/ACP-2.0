<?php
class RoundsoundModel
{

  function __construct()
  {
  }

  function zmien_wartosc($wartosc, $conf_name, $dostep)
  {
    Permission::check($dostep);

    $co_bylo = SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = '$conf_name' LIMIT 1");
    if($wartosc == $co_bylo){
      return;
    }

    SQL::query("UPDATE `rs_ustawienia` SET `conf_value` = '$wartosc' WHERE `conf_name` = '$conf_name'; ");
    Logs::log("Zmieniono $co_bylo na $wartosc dla ustawienia $conf_name", "?x=roundsound&xx=ustawienia");
  }

  function ustawienia_OnOff($lista, $id, $OnOff, $dostep)
  {
    Permission::check($dostep);

    if($OnOff == 'on'){
      $lista[] = (int)$id;
    }
    else if($OnOff = 'off'){
      $kasujemy = [$id];
      $lista = array_diff($lista, $kasujemy);
    }
    $lista = json_encode(array_values($lista));
    SQL::query("UPDATE `rs_ustawienia` SET `conf_value` = '$lista' WHERE `conf_name` = 'rs_serwery'; ");
    Logs::log("Zaktualizowano ustawienie serwera ".Model('Server')->mod($id)." (ID: $id)", "?x=roundsound&xx=ustawienia");
  }
}
 ?>
