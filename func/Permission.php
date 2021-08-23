<?php
class Permission
{
  public static function check($dostep, $back=TRUE)
  {
    $db = DB::getInstance();
    $user_more = $db->get_row("SELECT `role`, `grupa` FROM `acp_users` WHERE `user`= ".User::get()." LIMIT 1", true);

    // gdy nie ma ustawionej żadnej grupy pooglądowej
    if(empty($_SESSION['acp_grupa_sesja']) && empty($_SESSION['acp_grupa_sesja_nazwa'])):
      // gdy jest dostep
      if($dostep == 1):
        return 1;
      // gdy user jest rootem
      elseif($user_more->role == 1):
        return 1;
      // brak dostepu
      else:
        $_SESSION['messe']->uprawnieaCheck = [
          'type' => 'danger',
          'text' => 'Nie posiadasz dostępu do tej funkcji systemu'
        ];
        if($back === TRUE){
          back();
        }
        return;
      endif;

    // gdy mamy nadaną grupę pooglądową
    elseif((int)$_SESSION['acp_grupa_sesja'] != (int)$user_more->grupa):
      if($dostep == 1):
        return 1;
      endif;
    else:
      $_SESSION['messe']->uprawnieaCheck = [
        'type' => 'danger',
        'text' => 'Nie posiadasz dostępu do tej funkcji systemu'
      ];
      if($back === TRUE){
        back();
      }
      return;
    endif;
  }
}
?>
