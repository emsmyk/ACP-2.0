<?php
function getUser($id)
{
  if(empty($id)){
    $user = new stdClass();

  	$user->user = '0';
  	$user->role = '-1';
  	$user->szablon = 'skin-blue';

    return $user;
  }

  $db = DB::getInstance();
  $user = $db->get_results("SELECT * FROM `acp_users` WHERE `user` = $id LIMIT 1", true)[0];

  $user->szablon = (empty($user->szablon)) ? 'skin-blue': $user->szablon;
  $user->uklad_16_4 = ($user->uklad_16_4 == 1) ? 'layout-boxed' : '' ;
  $user->pudelkowy = ($user->pudelkowy == 1) ? 'fixed' : '' ;
  $user->menu = ($user->menu == 1) ? 'sidebar-collapse' : '' ;
  $user->prawy_kolor = ($user->prawy_kolor == 1) ? 'control-sidebar-light' : 'control-sidebar-dark';

  $user->notyfi = Notifi::notifi($user->user);

  return $user;
}

class User
{
  public static function get()
  {
    return $_SESSION['user'];
  }

  public static function find($name)
  {
    $db = DB::getInstance();

    return $db->get_results("SELECT `user` FROM `acp_users` WHERE `user` LIKE '%$name%' LIMIT 1");
  }

  public static function Name($user)
  {
    $db = DB::getInstance();

    return $db->get_results("SELECT `login`, `steam_login` FROM `acp_users` WHERE `user` = $user LIMIT 1");
  }

  public static function Avatar($img)
  {
    if(empty($img)){
      return "./www/img/av_default.jpg";
    }
    else {
      return $img;
    }
  }

  public static function printName($user, $link=null)
  {
    $name = User::Name($user)[0];

    if(is_null($link)){
      return $name['steam_login'].' ('.$name['login'].')';
    }
    else {
      return '<a href="?x=account&id='.$user.'">'.$name['steam_login'].' ('.$name['login'].')</a>';
    }
  }

  public static function printUrlAvatar($user)
  {
    $db = DB::getInstance();

    $img = $db->get_results('SELECT `steam_avatar` FROM `acp_users` WHERE `user` = '. $user .' LIMIT 1');

    return User::Avatar($img[0]['steam_avatar']);
  }

  public static function updateLastLogin($user='')
  {
    if(strtotime($user['last_login']) < (time() - 120)) {
    	SQL::query("UPDATE `acp_users` SET `last_login` = NOW() WHERE `user` = ".$user['user']." LIMIT 1;");
    }
  }

  public static function getUserHavePermission($permission)
  {
    $user_list = array();

    $grups = SQL::all("SELECT `id` FROM `acp_users_grupy` WHERE `dostep` LIKE '%\"$permission\":\"1\"%' ");
		foreach ($grups as $grup) {
			$users = SQL::all("SELECT `user` FROM `acp_users` WHERE `grupa` = '$grup->id' ");
			foreach ($users as $user) {
				array_push($user_list, $user->user);
			}
		}

    return $user_list;
  }

  public static function LoginSteam($steam, $login)
  {
    if(!$steam)
    {
      return $login;
    }
    else
    {
      return $steam.'('.$login.')';
    }
  }

  public static function user_list($chose='-')
  {
    $users_list = array(0 => $chose);
    $users_list_q = SQL::all("SELECT `user`, `login` FROM `acp_users`");
    foreach($users_list_q as $value){
      $users_list[$value->user]="$value->login";
    }

    return $users_list;
  }
}

?>
