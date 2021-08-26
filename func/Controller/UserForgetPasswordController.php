<?php
class UserForgetPasswordController
{

  function __construct()
  {
  }

  function forget_password($login, $mail, $acp_mail)
  {
    if(empty($login) || empty($mail)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Wypełnij wszystkie pola poprawnie"
      ]);
    }

    $dane = SQL::row("SELECT `user`, `pass_hash`, `email` FROM `acp_users` WHERE `login` = '$login' AND `email` = '$mail' LIMIT 1");
    if(empty($dane->email)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Nie został dodany adres mail, skontaktuj się z administratorem sieci."
      ]);
    }

    if(empty($dane->pass_hash)){
      $hash = substr(md5(mt_rand()), 0, 30);
      SQL::query("UPDATE `acp_users` SET `pass_hash` = '$hash' WHERE `user` = $dane->user;");

      $subject = 'Przypomnienie hasła';
      $message = "<html>
      <head>
        <title>Przypomnienie hasła</title>
      </head>
      <body>
        <p>Witaj $login!</p>
        <p>Aby zrestartować hasło wejdź na link poniżej:</p>
        <p>https://".$_SERVER['HTTP_HOST']."/?x=forget_password&hash=$hash</p>
      </body>
      </html>";
      $headers[] = 'MIME-Version: 1.0';
      $headers[] = 'Content-type: text/html; charset=utf-8';
      $headers[] = "From: ACP <$acp_mail>";

      mail($dane->email, $subject, $message, implode("\r\n", $headers));


      return Messe::array([
        'type' => 'success',
        'text' => $hash
      ]);
    }
    else{
      return Messe::array([
        'type' => 'info',
        'text' => "Link został wygenerowany i przesłany na adres mail."
      ]);
    }
  }

  public function forget_password_new($pass, $pass2, $hash)
  {
    if(strlen($pass) < 5 ){
      return Messe::array([
        'type' => 'warning',
        'text' => "Hasło jest za krótkie (min: 5 znaków)"
      ]);
    }

    if($pass != $pass2){
      return Messe::array([
        'type' => 'warning',
        'text' => "Podane hasła nie są identyczne.."
      ]);
    }

    $user_id = SQL::one("SELECT `user` FROM `acp_users` WHERE `pass_hash` = '$hash' LIMIT 1");
    SQL::query("UPDATE `acp_users` SET `pass` = '".md5($pass)."', `pass_hash` = NULL WHERE `user` = $user_id;");

    return Messe::array([
      'type' => 'success',
      'text' => "Zaktualizowano hasło <a href='?x=login>'>przejdz tutaj</a> aby się zalgować"
    ]);

  }

}
 ?>
