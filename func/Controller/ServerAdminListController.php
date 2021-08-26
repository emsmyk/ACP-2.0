<?php
class ServerAdminListController
{

  function __construct()
  {
    $this->db = DB::getInstance();
  }

  function list_adminow_ustawienia_edit($serwer, $dostep){
    Permission::check($dostep);

    $from = From::check();
    $from->ilosc_adminow = (empty($from->ilosc_adminow)) ? 5 : $from->ilosc_adminow;

    $this->db->delete('acp_serwery_listaadminow', [ 'serwer' => $serwer ], 1);
    $this->db->insert('acp_serwery_listaadminow', [
      'serwer' => $serwer,
      'dane' => '{\"pokaz_legende\":'.$from->pok_ukr_legenda.', \"pokaz_weteran\":'.$from->pok_ukr_weteran.', \"pokaz_bez_uprawnien\":'.$from->pok_ukr_bezuprawnien.', \"pokaz_opiekuna\":'.$from->pok_ukr_opiekun.',\"pokaz_zastepce\":'.$from->pok_ukr_zastepca.'}',
      'ilosc_adminow' => $from->ilosc_adminow
    ]);
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
