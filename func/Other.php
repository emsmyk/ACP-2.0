<?php
function back($POST)
{
  if(!empty($POST)){
    $_SESSION['POST'] = $POST;
  }
  header("Location: ".$_SERVER['HTTP_REFERER']);
  exit;
}
function redirect($link)
{
  header("Location: ".$link);
  exit;
}

function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'g3r2\reT\RD28YCw/%3E#4Szd';
    $secret_iv = 'NyGjxx5#mj<+dpF>bHNuUxR<>';
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
function daj_bledy($OnOff, $page, $get){
  if($OnOff === 1){
    if($page === $get){
      ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    }
  }
  else{
    $role = SQL::one("SELECT `role` FROM `acp_users` WHERE `user` = ".User::get()." LIMIT 1");
    if($role == 1){
      ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    }
  }
}
function tytul_strony($tekst){
  $nazwa = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'acp_nazwa' LIMIT 1");
  echo '<script>
   document.title = "'.$nazwa.' | '.$tekst.'";
   </script>';
}
function generujLosowyCiag($length = 10, $alfabet=true) {
  if($alfabet==true){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  }
  else {
    $characters = '0123456789';
  }
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

/* FUNKCJE DO SKASOWANIA */
function komunikaty($text, $rodzaj) {
  $rodzaj_array = array(1 => 'success', 2 => 'info', 3 => 'warning', 4 => 'danger');
  return $Messe->one($rodzaj_array[$rodzaj], $text);
}

function uprawnienia($dostep, $user){
  $more_user = row("SELECT `role`, `grupa` FROM `acp_users` WHERE `user`= $user LIMIT 1");

  // gdy nie ma ustawionej żadnej grupy pooglądowej
  if(empty($_SESSION['acp_grupa_sesja']) && empty($_SESSION['acp_grupa_sesja_nazwa'])):
    // gdy jest dostep
    if($dostep == 1):
      return 1;
    // gdy user jest rootem
    elseif($more_user->role == 1):
      return 1;
    // brak dostepu
    else:
      Messe::array([
        'type' => 'info',
        'text' => "Nie posiadasz dostępu do tej funkcji."
      ]);
      return 0;
    endif;
  // gdy mamy nadaną grupę pooglądową
  elseif((int)$_SESSION['acp_grupa_sesja'] != (int)$more_user->grupa):
    if($dostep == 1):
      return 1;
    endif;
  // gdy nic nie mamy
  else:
    return 0;
  endif;
}
?>
