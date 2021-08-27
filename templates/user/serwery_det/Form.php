<?
if(isset($_POST['admin_list_add_admin_from'])){
  Model('Sourcebans')->admin_store($serwer_id, $dostep->serwery_det_SB_adm_dodaj);
  redirect("?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_edytuj'])){
  Model('Sourcebans')->admin_update($serwer_id, $dostep->serwery_det_SB_adm_edytuj);
  redirect("?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_usun'])){
  Model('Sourcebans')->admin_destry($serwer_id, $dostep->serwery_det_SB_adm_usun);
  redirect("?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_degradacja'])){
  Model('Sourcebans')->admin_degradacja($serwer_id, $dostep->serwery_det_SB_adm_degra_rezy);
  redirect("?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_rezygnacja'])){
  Model('Sourcebans')->admin_rezygnacja($serwer_id, $dostep->serwery_det_SB_adm_degra_rezy);
  redirect("?x=$x&serwer_id=$serwer_id");
}


if(isset($_POST['ust_podstawowe_edit'])){
  Controller('ServerDet')->editUstPodstawowe($_POST['ust_podstawowe_edit'], $serwer_id, $player->user, $dostep->ustawienia_podstawowe);
  redirect("?x=$x&serwer_id=$serwer_id");
}
$get_regulamin = (isset($_GET['regulamin'])) ? $_GET['regulamin'] : '';
if($get_regulamin == 'regulamin'){
  Controller('ServerRegulamin')->edit($serwer_id);
  redirect("?x=$x&serwer_id=$serwer_id");
}

if(isset($_POST['admin_list_ustawienia_edit'])){
  Controller('ServerAdminList')->edit($serwer_id, $dostep->ustawienia_podstawowe);
  redirect("?x=$x&serwer_id=$serwer_id");
}
if(isset($_POST['admin_list_raport'])){
  Controller('AdminRaport')->raport($serwer_id, $dostep->serwery_det_RaportOpiekuna);
  redirect("?x=$x&serwer_id=$serwer_id");
}

if(isset($_POST['wgraj_mape'])){
  Controller('ServerUploadMap')->upload($dostep->serwery_det_WgrajMape);
  redirect("?x=$x&serwer_id=$serwer_id");
}

//
// Wykresy
//
$get_wkresy = (isset($_GET['wykresy'])) ? $_GET['wykresy'] : '';
if(empty($_SESSION["wyk-graczy-zakres-$serwer_id"])) { $_SESSION["wyk-graczy-zakres-$serwer_id"] = 'hour'; }
if(empty($_SESSION["srv_det_graczy_$serwer_id"])) { $_SESSION["srv_det_graczy_$serwer_id"] = 30; }
if(empty($_SESSION["srv_det_gosetti_pozycja_$serwer_id"])) { $_SESSION["srv_det_gosetti_pozycja_$serwer_id"] = 10; }
if(empty($_SESSION["srv_det_gosetti_tura_$serwer_id"])) { $_SESSION["srv_det_gosetti_tura_$serwer_id"] = 10; }
if(empty($_SESSION["srv_det_gosetti_tura_$serwer_id"])) { $_SESSION["srv_det_gosetti_tura_$serwer_id"] = 10; }
if($get_wkresy == 'wykresy'){
  $_SESSION["wyk-graczy-zakres-$serwer_id"] = $_POST["wyk-graczy-zakres"];
  $_SESSION["srv_det_graczy_$serwer_id"] = $_POST["wyk-graczy-ilosc"];
  $_SESSION["srv_det_gosetti_pozycja_$serwer_id"] = $_POST["wyk-gosetti-pozycja-ilosc"];
  $_SESSION["srv_det_gosetti_tura_$serwer_id"] = $_POST["wyk-gosetti-punkty-ilosc"];

  redirect("?x=$x&serwer_id=$serwer_id");
}

//
// Changelog
//
$get_changelog = (isset($_GET['changelog'])) ? $_GET['changelog'] : '';
if($get_changelog == 'changelog'){
  if(isset($_POST['changelog_add'])) {
    $changelog->store('dodaj_admina');
  }
  if(isset($_POST['changelog_awans_deg_rez'])) {
    $changelog->store('awans_deg_rez');
  }
  if(isset($_POST['changelog_wlasny'])) {
    $changelog->store('wlasny');
  }
  redirect("?x=$x&serwer_id=$serwer_id");
}

//
// Prace Zdalne
//
if(!empty($_GET['prace_zdalne'])){
  if($_GET['prace_zdalne'] == 'skasuj_bledy'){
    Model('ServerKonfiguracja')->deleteErrorsUpload($serwer_id, $dostep->PraceCykliczneOdczytane);
    redirect("?x=$x&serwer_id=$serwer_id");
  }
}
?>
