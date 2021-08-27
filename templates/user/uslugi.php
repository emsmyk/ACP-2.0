<?
$page = 'default';
switch ($xx) {
  case 'moje_uslugi':
    $page = 'templates/user/uslugi/moje_uslugi.php';
    break;;
  case 'ustawienia':
    if(Permission::check($dostep->UslugiUstawienia, false) != 1){
      redirect("?x=default");
      break;
    }
    $page = 'templates/user/uslugi/ustawienia.php';
    break;
  case 'dodaj_usluge':
    if(Permission::check($dostep->UslugiDodaj, false) != 1){
      redirect("?x=default");
      break;
    }
    $page = 'templates/user/uslugi/dodaj_usluge.php';
    break;
  case 'uslugi':
    $page = 'templates/user/uslugi/uslugi.php';
    break;
}

if($page != 'default') {
  require_once($page);
}
else {
  redirect("?x=default");
}
?>
