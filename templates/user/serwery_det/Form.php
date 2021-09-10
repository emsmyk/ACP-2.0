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
if(Get::string('regulamin') == 'regulamin'){
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
if(Get::string('wykresy') == 'wykresy'){
  $_SESSION['ServerDet_'. Get::int('serwer_id') ]['wyk-graczy-zakres'] = $_POST["wyk-graczy-zakres"];
  $_SESSION['ServerDet_'. Get::int('serwer_id') ]['srv_det_graczy'] = $_POST["wyk-graczy-ilosc"];
  $_SESSION['ServerDet_'. Get::int('serwer_id') ]['srv_det_gosetti_pozycja'] = $_POST["wyk-gosetti-pozycja-ilosc"];
  $_SESSION['ServerDet_'. Get::int('serwer_id') ]['srv_det_gosetti_tura'] = $_POST["wyk-gosetti-punkty-ilosc"];

  redirect("?x=$x&serwer_id=$serwer_id");
}

//
// Changelog
//
if(Get::string('changelog') == 'changelog'){
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
if(Get::string('prace_zdalne') == 'skasuj_bledy'){
  Model('ServerKonfiguracja')->deleteErrorsUpload($serwer_id, $dostep->PraceCykliczneOdczytane);
  redirect("?x=$x&serwer_id=$serwer_id");
}
?>
