<?php
class PageBulid
{
  function __construct()
  {
    $user = getUser($_SESSION['user']);

    $this->user1 = [
      'user' => $user->user,
      'role' => $user->role
    ];

    $this->UserSessionGrup = (empty($_SESSION['acp_grupa_sesja'])) ? '' : $_SESSION['acp_grupa_sesja'];

    $this->moduls = [
      'podstawa' => [ "blad", "login", "register", "forget_password", "logout", "ajax", "default", "cronjobs", "cronjobs_stats", "cronjobs_optym", "cronjobs_serwer", "api", "plugins_lic", "pub_sourcebans", "pub_admin_list", "pub_galeria_map", "pub_changelog", "pub_serwery", "pub_hlstats_top", "pub_roundsound", "pub_zadanie", "pub_iframe", "test" ],
      'podstawa_clear' => [ 'download' ],

      'user' => [ "wiadomosci", "powiadomienia" ],
      'user_grup' => json_decode( SQL::one('SELECT `moduly` FROM `acp_users_grupy` WHERE `id` = '.$user->user.' LIMIT 1')  )
    ];
  }

  function show($x)
  {
    //moduly dla goscia
    if($this->idGuest()){
      $test = 'guest';
      $moduls = array_merge($this->moduls['podstawa'], $this->moduls['podstawa_clear']);
      $page = [
        'header' => 'templates/master/przybornik/header.php',
        'menu' => 'templates/master/przybornik/menu.php',
        'footer' => 'templates/master/przybornik/footer.php',
      ];
    }

    // moduly dla root'a
    elseif($this->ifRoot()){
      $test = 'root';
      $moduls = $this->UserModulesRoot();
      $page = [
        'header' => 'templates/user/header.php',
        'menu' => 'templates/user/menu.php',
        'footer' => 'templates/user/footer.php',
      ];
    }

    // moduly dla grupy session 'testowej'
    elseif(!empty($this->UserSessionGrup)){
      $test = 'session id grups'.$this->UserSessionGrup;
      $moduls = $this->UserModulesSessionGrup();
      $page = [
        'header' => 'templates/user/header.php',
        'menu' => 'templates/user/menu.php',
        'footer' => 'templates/user/footer.php',
      ];
    }

    // dla usera zwyklego..
    else {
      $test = 'user';
      $moduls = array_merge($this->moduls['user'], $this->moduls['user_grup'], $this->moduls['podstawa'], $this->moduls['podstawa_clear']);
      $page = [
        'header' => 'templates/user/header.php',
        'menu' => 'templates/user/menu.php',
        'footer' => 'templates/user/footer.php',
      ];
    }

    // show([ 'role_user' => $this->user1['role'], 'nazwa' => $test, 'a' => $moduls], false);

    return $this->bulid($x, $moduls, $page);
  }

  function idGuest()
  {
    if(empty($this->user1['user']) || !isset($this->user1['user']) || $this->user1['user'] = '' || $this->user1['user'] === 0 ){
      return true;
    }

    return false;
  }

  function ifRoot()
  {
    // jeśli jest sesja dajemy odrazu grupe sesyjna
    if(!empty($this->UserSessionGrup)){
      return false;
    }

    //jeśli mamy roota
    if($this->user1['role'] === '1'){
      return true;
    }

    return false;
  }

  function UserModulesRoot()
  {
    $modules = [];

    $AllGrupsModules = SQL::all('SELECT `moduly` FROM `acp_users_grupy`');
    foreach ($AllGrupsModules as $value) {
      $modules = array_merge($modules, json_decode($value->moduly) );
    }

    return array_merge($modules, $this->moduls['podstawa'], $this->moduls['podstawa_clear'] );
  }

  function UserModulesSessionGrup()
  {
    $GrupsModules = json_decode( SQL::one('SELECT `moduly` FROM `acp_users_grupy` WHERE `id` = '.$this->UserSessionGrup.' LIMIT 1')  );

    return array_merge($this->moduls['user'], $GrupsModules, $this->moduls['podstawa'], $this->moduls['podstawa_clear'] );
  }


  function bulid($x, $moduls, $page)
  {
    if(empty($x) || !in_Array($x, $moduls)){
      return redirect('?x=default');
    }

    if(empty($page)){
      $page = [
        'header' => 'templates/master/przybornik/header.php',
        'menu' => 'templates/master/przybornik/menu.php',
        'footer' => 'templates/master/przybornik/footer.php',
      ];
    }

    if(in_Array($x,$moduls) && $this->ifRoot() ){
      $page = [
        'header' => 'templates/user/header.php',
        'menu' => 'templates/user/menu.php',
        'footer' => 'templates/user/footer.php'
      ];

      $page['page'] = (file_exists("templates/admin/$x.php")) ? "templates/admin/$x.php" : "templates/user/$x.php";
    }

    elseif(in_Array($x,$moduls) && in_Array($x, $this->moduls['user']) && !$this->ifRoot()){
      if(in_Array($x, $this->moduls['user']) || in_Array($x, $this->moduls['user_grup'])){
        $page = [
          'header' => 'templates/user/header.php',
          'menu' => 'templates/user/menu.php',
          'footer' => 'templates/user/footer.php'
        ];
      }
      $page = [
        'header' => 'templates/user/header.php',
        'menu' => 'templates/user/menu.php',
        'footer' => 'templates/user/footer.php'
      ];

      $page['page'] = 'templates/user/'.$x.'.php';
    }

    elseif(in_Array($x, $this->moduls['podstawa_clear'])){
      $page['page'] = 'templates/master/'.$x.'.php';
    }

    elseif(in_Array($x, $this->moduls['podstawa']) ){
      $page = [
        'header' => 'templates/master/przybornik/header.php',
        'menu' => 'templates/master/przybornik/menu.php',
        'footer' => 'templates/master/przybornik/footer.php',
      ];

      $page['page'] = 'templates/master/'.$x.'.php';
    }

    else {
      $page = [
        'header' => 'templates/master/przybornik/header.php',
        'menu' => 'templates/master/przybornik/menu.php',
        'footer' => 'templates/master/przybornik/footer.php',
        'page' => 'templates/master/default.php'
      ];
    }

    return $page;
  }
}
 ?>
