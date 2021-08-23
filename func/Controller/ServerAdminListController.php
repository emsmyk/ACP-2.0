<?php
class ServerAdminListController
{

  function __construct()
  {

  }

  function list_adminow_ustawienia_edit($serwer, $dostep){
    Permission::check($dostep);

    $from = From::check();
    $from->ilosc_adminow = (empty($from->ilosc_adminow)) ? 5 : $from->ilosc_adminow;

    $czy_istnieje = SQL::one("SELECT `id` FROM `acp_serwery_listaadminow` WHERE `serwer` = $serwer LIMIT 1");
    if(empty($czy_istnieje)):
      SQL::insert('acp_serwery_listaadminow', [
        'serwer' => $serwer,
        'dane' => '{\"pokaz_legende\":'.$from->pok_ukr_legenda.', \"pokaz_weteran\":'.$from->pok_ukr_weteran.', \"pokaz_bez_uprawnien\":'.$from->pok_ukr_bezuprawnien.', \"pokaz_opiekuna\":'.$from->pok_ukr_opiekun.',\"pokaz_zastepce\":'.$from->pok_ukr_zastepca.'}',
        'ilosc_adminow' => $from->ilosc_adminow
      ]);
    else:
      SQL::update('acp_serwery_listaadminow'[
        'dane' => '{\"pokaz_legende\":'.$from->pok_ukr_legenda.', \"pokaz_weteran\":'.$from->pok_ukr_weteran.', \"pokaz_bez_uprawnien\":'.$from->pok_ukr_bezuprawnien.', \"pokaz_opiekuna\":'.$from->pok_ukr_opiekun.',\"pokaz_zastepce\":'.$from->pok_ukr_zastepca.'}',
        'ilosc_adminow' => $from->ilosc_adminow
      ],
      $serwer,
      'serwer'
      );
    endif;
  }

  function list_adminow_ust(int $id){
		if($id == 0):
			return '<option value="0">Ukryty</option><option value="1">Widoczny</option>';
		elseif($id == 1):
			return '<option value="1">Widoczny</option><option value="0">Ukryty</option>';
		endif;
	}
}
 ?>
