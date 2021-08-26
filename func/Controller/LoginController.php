<?php
class LoginController
{
  function __construct()
  {
    $this->db = DB::getInstance();
  }

  function login()
  {
    $from = From::check([
      'login' => 'reg',
      'pass' => 'reg'
    ],[
      'login.reg' => 'Podaj login..',
      'pass.reg' => 'Podaj hasło..',
    ]);

   	$from->pass = md5($from->pass);

   	$user = $this->db->get_row("SELECT `user`, `banned` FROM `acp_users` WHERE `login` = '$from->login' AND `pass` = '$from->pass' LIMIT 1", true);
    if(empty($user) && !is_numeric($user->user) && ($user->user > 0)){
      $this->lastLogin([ 'user' => $user->user, 'success' => '0' ]);

      return Messe::array([
        'type' => 'warning',
        'text' => "Wprowadzone błędne dane."
      ]);
    }

    if($user->banned == 0){
      return Messe::array([
        'type' => 'danger',
        'text' => "Twoje konto zostało zablokowane z powodu łamania zasad społeczności."
      ]);
    }

    $_SESSION = [
      'user' => $user->user
    ];
    $this->lastLogin([ 'user' => $user->user, 'success' => '1' ]);

    return redirect('?x=wpisy');
  }

  function lastLogin($user)
  {
    $this->db->insert('acp_users_login_logs', [
      'user_id' => $user['user'],
      'ip' => $_SERVER['REMOTE_ADDR'],
      'przegladarka' => $_SERVER['HTTP_USER_AGENT'],
      'poprawne' => $user['succes'],
      'date' => 'NOW'
    ]);
  }
}
 ?>
