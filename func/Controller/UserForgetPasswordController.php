<?php
class UserForgetPasswordController
{

  function __construct()
  {
  }

  function forget_password($login, $mail, $acp_mail)
  {
    if(empty($login) || empty($mail)){
      $_SESSION['msg'] = komunikaty("Wypełnij wszystkie pola poprawnie..", 3);
      return;
    }

    $dane = SQL::row("SELECT `user`, `pass_hash`, `email` FROM `acp_users` WHERE `login` = '$login' AND `email` = '$mail' LIMIT 1");
    if(empty($dane->email)){
      $_SESSION['msg'] = komunikaty("Nie został dodany adres email, skontaktuj się z administratorem..", 3);
      return;
    }

    if(empty($dane->pass_hash)){
      $hash = substr(md5(mt_rand()), 0, 30);
      query("UPDATE `acp_users` SET `pass_hash` = '$hash' WHERE `user` = $dane->user;");

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

      $_SESSION['msg'] = komunikaty("$hash", 2);
      return;
    }
    else{
      $_SESSION['msg'] = komunikaty("Wygenerowany został już link, sprawdź pocztę.", 2);
      return;
    }
  }

  public function forget_password_new($pass, $pass2, $hash)
  {
    if(strlen($pass) < 5 ){
      $_SESSION['msg'] = komunikaty("Hasło za krótkie (Minimalnie 5 znaków)", 3);
      return;
    }

    if($pass != $pass2){
      $_SESSION['msg'] = komunikaty("Hasła nie są identyczne..", 3);
      return;
    }
    $user_id = SQL::one("SELECT `user` FROM `acp_users` WHERE `pass_hash` = '$hash' LIMIT 1");
    query("UPDATE `acp_users` SET `pass` = '".md5($pass)."', `pass_hash` = NULL WHERE `user` = $user_id;");
    $_SESSION['msg'] = komunikaty("Zaktualizowano hasło <a href='?x=login>'>przejdz tutaj</a> aby się zalgować", 1);
  }

}
 ?>
