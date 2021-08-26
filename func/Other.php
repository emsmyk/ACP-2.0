<?php
function optionHtml($array, $data)
{
  $tekst = '<select class="form-control" name="'.$data['name'].'">';

  if(!in_array($data['value'], $array) && !empty($data['value'])){
    $tekst .= '<option value="'.$data['value'].'">Brak Danych.. (ID: '.$data['value'].')</option>';
  }

  elseif(!empty($data['value'])){
    $tekst .= '<option value="'.$data['value'].'">'.$array[ $data['value'] ].'</option>';
  }

  foreach ($array as $key => $value) {
    if($data['value'] != $key){
      $tekst .= '<option value="'.$key.'">'.$value.'</option>';
    }
  }
  $tekst .= '</select>';

  return $tekst;
}

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

function encrypt_decrypt($action, $string)
{
  $output = false;
  $encrypt_method = "AES-256-CBC";
  $secret_key = $acp_system['acp_special_key'];
  $secret_iv = $acp_system['acp_special_iv'];
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

function daj_bledy($OnOff, $page, $get)
{
  if($OnOff === 1){
    if($page === $get){
      ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    }
  }
  else{
    if(User::get() == 0){
      return;
    }

    $role = SQL::one("SELECT `role` FROM `acp_users` WHERE `user` = ".User::get()." LIMIT 1");
    if($role == 1){
      ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    }
  }
}

function tytul_strony($tekst)
{
  $nazwa = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'acp_nazwa' LIMIT 1");
  echo '<script>
   document.title = "'.$nazwa.' | '.$tekst.'";
   </script>';
}

function generujLosowyCiag($length = 10, $alfabet=true)
{
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

function show($array, $die=true)
{
  // foreach ($array as $value) {
  //   print_r($value);
  // };

  echo "<pre><span style='color: red'>ACP | Admin Control Panel</br><small>return text/data</br></br></small></span>";
  print_r($array);
  echo "</pre>";

  if($die==true) { die; }
}

?>
