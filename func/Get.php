<?php
class Get
{
  public static function int($get){
    $get = (isset($_GET[$get])) ? (int)$_GET[$get] : null;
    return $get;
  }

  public static function string($get){
    $get = (isset($_GET[$get])) ? $_GET[$get] : null;
    return $get;
  }
}
?>
