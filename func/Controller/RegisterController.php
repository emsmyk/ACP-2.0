<?php
class RegisterController
{

  function __construct()
  {
    $this->db = DB::getInstance();

    $this->register_on = $this->db->get_row('SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = "acp_rejestracja"')[0];
  }

  function register()
  {
    if($this->register_on == 1){
      return Messe::array([
        'type' => 'warning',
        'text' => "Rejestracja została zablokowana przez administora ACP"
      ]);
    }

    $from = From::check([
      'login' => 'reg|min:5|max:15',
      'pass' => 'reg|min:5',
      'mail' => 'reg',
      'steam' => 'reg',
      'regulamin' => 'reg'
    ],[
      'login.reg' => 'Pole Login jest wymagane',
      'login.min:5' => 'Login jest za krótki. Minimalna długość to 5 znaków',
      'login.max:15' => 'Login jest za długi. Maksymalna długość to 15 znaków',
      'mail.reg' => 'Pole Mail jest wymagane',
      'steam.reg' => 'Pole Steam jest wymagane',
      'pass.reg' => 'Hasło jest wymagane',
      'pass.min:5' => 'Hasło jest za krótkie. Minimalna długość to 5 znaków',
      'regulamin.reg' => 'Aby utworzyć konto musisz zakceptować regulamin strony..',
    ]);

    $from->steam_comunity  =$Steam->toCommunityID($from->steam);

    if($from->pass != $from->pass2){
      return Messe::array([
        'type' => 'danger',
        'text' => "Hasła nie są identyczne.."
      ]);
    }

    $info = $this->db->get_row("SELECT count(`login`) AS `elogin`, count(`email`) AS `eemail`, count(`steam`) AS `ssteam` FROM `acp_users` WHERE `login`='".$from->login."' OR `email`='".$from->mail."' OR `steam`='".$from->steam_comunity."'; ", true);
    if($info->elogin != 0) {
      return Messe::array([
        'type' => 'warning',
        'text' => "Istnieje już użytkownik z takim loginem ($login)"
      ]);
    }
    if($info->eemail != 0){
      return Messe::array([
        'type' => 'warning',
        'text' => "Na ten mail został zarejstrowany już jeden użytkownik"
      ]);
    }
    if($info->ssteam != 0) {
      return Messe::array([
        'type' => 'warning',
        'text' => "Istnieje juz użytkownik z takim samym Steam ID ($steam)"
      ]);
    }

    Model('Register')->new([
      'login' => $from->login,
      'pass' => $from->pass,
      'email' => $from->mail,
      'steam' => $from->steam_comunity
    ]);

    return redirect('?x=login');
  }

  function addUser()
  {
    $from = From::check([
      'login' => 'reg',
      'pass' => 'reg',
      'steam_id' => 'reg',
    ],[
      'login.reg' => 'Pole Login jest wymagane',
      'steam_id.reg' => 'Pole Steam jest wymagane',
      'pass.reg' => 'Hasło jest wymagane',
    ]);

    $from->steam_comunity = $Steam->toCommunityID($from->steam_id);

    $info = $this->db->get_row("SELECT count(`login`) as `elogin`, count(`steam`) AS `ssteam` FROM `acp_users` WHERE `login`='".$from->login."' or `steam`='".$from->steam_comunity."'", true);
    if($info->elogin != 0) {
      return Messe::array([
        'type' => 'warning',
        'text' => "Istnieje już użytkownik z takim loginem ($login)"
      ]);
    }
    if($info->ssteam != 0) {
      return Messe::array([
        'type' => 'warning',
        'text' => "Istnieje juz użytkownik z takim samym Steam ID ($steam)"
      ]);
    }

    Model('Register')->new([
      'login' => $from->login,
      'pass' => $from->pass,
      'email' => '',
      'steam' => $from->steam_comunity
    ]);
  }
}
 ?>
