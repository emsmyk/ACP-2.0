<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('func/x_old_mysql.php');
require_once('func/SQL.php');

require_once('var/config.php');

require_once('func/Notifi.php');
require_once('func/User.php');
require_once('func/Servers.php');
require_once('func/Steam.php');

require_once('func/Date.php');
require_once('func/From.php');
require_once('func/Logs.php');
require_once('func/Permission.php');
require_once('func/Text.php');
require_once('func/Get.php');
require_once('func/Powiadomienia.php');
require_once('func/Messe.php');
require_once('func/File.php');
require_once('func/Other.php');

function Controller($name){ require_once('func/Controller/'.$name.'Controller.php');  eval('$class  = new '.$name.'Controller;'); return $class; }
function Model($name){ require_once('func/Model/'.$name.'Model.php');  eval('$class  = new '.$name.'Model;'); return $class; }

ob_start();
if(!isset($_SESSION)) { session_start(); }

if(!empty($_SESSION['user']) && is_numeric($_SESSION['user']))
{
	$player = getUser($_SESSION['user']);

	if($player->banned === 0)
	{
		$_SESSION = array(); session_destroy(); redirect('?x=login');
	}

	$player->szablon = (empty($player->szablon)) ? 'skin-blue': $player->szablon;
}
else {
	$_SESSION['user'] = 0;
}

if(empty($player))
{
	$player = new stdClass();
	$player->user = 0;
	$player->role = -1;
	$player->szablon = 'skin-blue';
}
//moduly dostepu
$podstawa = [ "blad", "login", "register", "forget_password", "logout", "ajax",
"default", "cronjobs", "cronjobs_stats", "cronjobs_optym", "cronjobs_serwer", "api", "plugins_lic",
"pub_sourcebans", "pub_admin_list", "pub_galeria_map", "pub_changelog", "pub_serwery", "pub_hlstats_top", "pub_roundsound", "pub_zadanie", "pub_iframe", "test"
];
$podstawa_clear = ['download'];
$gosc = array_merge($podstawa, $podstawa_clear);
if($player->role != -1 ) {
	$podstawa_user = array("wiadomosci", "powiadomienia");
	if(!isset($_SESSION['acp_grupa_sesja'])){
		$_SESSION['acp_grupa_sesja'] = '';
	}
	if((int)$_SESSION['acp_grupa_sesja'] != (int)$player->grupa && $_SESSION['acp_grupa_sesja'] != ''){
		$grupa_dane = SQL::row("SELECT `id`, `nazwa`, `moduly`, `dostep` FROM `acp_users_grupy` WHERE `id` = ".$_SESSION['acp_grupa_sesja']." LIMIT 1");
		$moduly_grupa = json_decode($grupa_dane->moduly);
		$dostep = json_decode($grupa_dane->dostep)[0];

		$_SESSION['acp_grupa_sesja_nazwa'] = SQL::one("SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = ".$_SESSION['acp_grupa_sesja']." LIMIT 1");
		$_SESSION['msg'] = $Messe->expanded('warning', "Jesteś aktualnie w trakcie podglądu jao grupa <b>".$grupa_dane->nazwa."</b> (ID: ".$grupa_dane->id."), aby wyjść wyloguj się albo zmień w ustawieniach..", "TRYB POGLĄDOWY", "fa fa-warning");
	}
	else {
		$grupa_dane = SQL::row("SELECT `nazwa`, `moduly`, `dostep` FROM `acp_users_grupy` WHERE `id` = ".$player->grupa." LIMIT 1");
		$moduly_grupa = json_decode($grupa_dane->moduly);
		$dostep = json_decode($grupa_dane->dostep)[0];
	}
	$moduly = array_merge($moduly_grupa, $podstawa_user);
}
else {
	$podstawa = [ "blad", "login", "register", "forget_password", "logout", "ajax",
	"default", "cronjobs", "cronjobs_stats", "cronjobs_optym", "cronjobs_serwer", "api", "plugins_lic",
	"pub_sourcebans", "pub_admin_list", "pub_galeria_map", "pub_changelog", "pub_serwery", "pub_hlstats_top", "pub_roundsound", "pub_zadanie", "pub_iframe", "test"
	];
	$podstawa_clear = ['download'];
	$gosc = array_merge($podstawa, $podstawa_clear);
	$moduly = [];
}

// wartość get x
$x = (isset($_GET['x'])) ? $_GET['x'] : null;
$xx = (isset($_GET['xx'])) ? $_GET['xx'] : null;

// wyswietl bledy php 0 = OFF
daj_bledy((int)$acp_system['dev_on'], $acp_system['dev_modul'], $x);

//pusty pasek adresu
if(empty($x)){
	if($player->role >= 0) { $x = 'wpisy'; } else { $x = 'default'; }
}

$header = 'templates/master/header.php';
$footer = 'templates/master/footer.php';

if(in_Array($x,$moduly) && ($player->role == 1) ){
	$header = 'templates/user/header.php';
	$menu = 'templates/user/menu.php';
	$footer = 'templates/user/footer.php';
	$page = (file_exists("templates/admin/$x.php")) ? "templates/admin/$x.php" : "templates/user/$x.php";
}
elseif(in_Array($x,$moduly) && ($player->role == 0) ){
	$header = 'templates/user/header.php';
	$menu = 'templates/user/menu.php';
	$footer = 'templates/user/footer.php';
	$page =  'templates/user/'.$x.'.php';
}
elseif(in_Array($x,$gosc)){
	if(in_Array($x,$podstawa_clear)){
		$page =  'templates/master/'.$x.'.php';
		require_once($page);
	}
	else {
		$header = 'templates/master/przybornik/header.php';
		$menu = 'templates/master/przybornik/menu.php';
		$footer = 'templates/master/przybornik/footer.php';
	}
	$page =  'templates/master/'.$x.'.php';
}
else {
	$header = 'templates/master/przybornik/header.php';
	$menu = 'templates/master/przybornik/menu.php';
	$footer = 'templates/master/przybornik/footer.php';
	$page =  'templates/master/blad.php';
}
require_once($header);
require_once($menu);
require_once($page);
require_once($footer);

ob_end_flush();
?>
