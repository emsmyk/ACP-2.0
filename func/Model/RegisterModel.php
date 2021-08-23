<?php
class Register
{

  function __construct()
  {

  }

  function new($user)
  {
    $last_insert = SQL::insert('acp_users', [
      'login' => $user['login'],
      'pass' => md5($user['pass']),
      'mail' => $user['mail'],
      'data_rejestracji' => 'NOW',
      'steam' => $user['steam_comunity']
    ]);
    Logs::log("Nowy użytkownik ".$user['login']." (STEAM: ".$user['steam_comunity'].")", "?x=account&id=$last_insert");
  }
}
?>
