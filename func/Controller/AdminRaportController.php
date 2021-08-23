<?php
class AdminRaportController
{

  function __construct()
  {
    $this->db = DB::getInstance();

    $this->nagroda = [
      'nagroda_usluga' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_Nagroda' LIMIT 1")[0],
      'usluga_id' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_Nagroda_flagi' LIMIT 1")[0],
      'czas' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_Nagroda_czas' LIMIT 1")[0],

      'nagroda_ranga' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_tag' LIMIT 1")[0],
      'tag_tabela' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_tag_tabela' LIMIT 1")[0],
      'tag_say' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_tag_say' LIMIT 1")[0],
      'color_tag' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_color_tag' LIMIT 1")[0],
      'color_nick' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_color_nick' LIMIT 1")[0],
      'color_tekst' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_color_tekst' LIMIT 1")[0],
      'ranga_czas' => $this->db->get_row("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'AdmRaport_AdmM_ranga_czas' LIMIT 1")[0],
    ];

    $this->data = array(
      'ubiegly_miesiac' => date('m', strtotime("-1 month")),
      'ubiegly_rok' => date('Y', strtotime("-1 month"))
    );
  }

  function raport($serwer_id, $dostep)
  {
    Permission::check($dostep);

    $serwer->dane = Model('Server')->basic($serwer_id);
    $serwer->nazwa = $serwer->dane->nazwa;
    $serwer->mod = $serwer->dane->mod;

    $serwer->opiekun = $this->db->get_row("SELECT `ser_a_opiekun` FROM `acp_serwery` WHERE `serwer_id` = '$serwer_id' LIMIT 1")[0];
    $serwer->chefadmin = $this->db->get_row("SELECT `ser_a_copiekun` FROM `acp_serwery` WHERE `serwer_id` = '$serwer_id' LIMIT 1")[0];

    foreach ($_POST['id'] as $key => $value) {
      $this->db->insert('raport_opiekun',[
          'serwer' => $serwer_id,
          'opiekun' => $serwer->opieku,
          'chefadmin' => $serwer->chefadmin,
          'steamid' => $_POST['steamid']["$key"],
          'admin_nick' => $_POST['nick_sb']["$key"],
          'admin_steam' => $_POST['nick_steam']["$key"],
          'grupa' => $_POST['opinia']["$key"],
          'forum_posty' => $_POST['forum_posty']["$key"],
          'forum_warny' => $_POST['forum_warny']["$key"],
          'serwer_czaspolaczenia' => $_POST['czas_gry']["$key"],
          'skladka' => $_POST['skladka']["$key"],
          'skladka_kwota' => $_POST['skladka_kwota']["$key"],
          'skladka_metoda' => $_POST['skladka_metoda']["$key"],
          'opinia' => $_POST['opinia']["$key"],
          'data_raportu' => date("Y-m-d H:i:s"),
          'miesiac' => $this->data['ubiegly_miesiac'],
          'rok' => $this->data['ubiegly_rok'],
        ]
      );
    }

    $raport->admin_miesiaca = $_POST['admin_miesiaca'];

    $this->db->insert('raport_serwer', [
        'serwer_id' => $serwer_id,
        'mod' => $serwer->mod,
        'nazwa' => $serwer->nazwa,
        'hls_graczy' => $_POST['hls_graczy'],
        'finanse_koszt' => $_POST['finanse_koszt'],
        'sklep_uslugi' => $_POST['sklep_uslugi'],
        'sklep_uslugi_koszt' => $_POST['sklep_uslugi_koszt'],
        'admini_liczba' => $_POST['admin_liczba'],
        'admin_miesiaca' => $raport->admin_miesiaca,
        'gt_rank' => $_POST['gt_rank'],
        'gt_low' => $_POST['gt_low'],
        'gt_hight' => $_POST['gt_hight'],
        'sb_ban' => $_POST['sb_ban'],
        'sb_mute' => $_POST['sb_mute'],
        'sb_gag' => $_POST['sb_gag'],
        'sb_unban' => $_POST['sb_unban'],
        'sb_unmute' => $_POST['sb_unmute'],
        'sb_ungag' => $_POST['sb_ungag'],
        'miesiac' => $this->data['ubiegly_miesiac'],
        'rok' => $this->data['ubiegly_rok'],
      ]
    );

    Logs::log("Raport Opiekuna za ".$this->data['ubiegly_miesiac']."/".$this->data['ubiegly_rok']." z serwera ".$serwer->dane->nazwa." [".$serwer->dane->mod."] (ID: $serwer_id) złożony.", "?x=raporty&xx=raport_miesieczny");

    if($this->nagroda['nagroda_usluga'] == 1){
      if(empty($raport->admin_miesiaca)) {
        return;
      }

      $this-nagroda_usluga($serwer_id, $raport->admin_miesiaca);

    }

    if($this->nagroda['nagroda_ranga'] == 1){
      if(empty($raport->admin_miesiaca)) {
        return;
      }

      $this-nagroda_ranga($serwer_id, $raport->admin_miesiaca);

    }

  }

  function addDay($day)
  {
    $now = new DateTime();
    return $now->modify("+$day day")->format('Y-m-d H:i:s');
  }

  function nagroda_ranga($serwer_id, $steam)
  {
    $this->db->insert('acp_serwery_hextags', [
        'serwer_id' => $serwer_id,
        'hextags' => $steam,
        'ScoreTag' => $this->nagroda['tag_tabela'],
        'TagColor' => $this->nagroda['color_tag'],
        'ChatTag' => $this->nagroda['tag_say'],
        'ChatColor' => $this->nagroda['color_tag'],
        'NameColorNameColor' => $this->nagroda['color_nick'],
        'Force' => 0,
        'istotnosc' => 20,
        'czasowa' => '1',
        'czasowa_end' => $this->addDay($this->nagroda['czas']),
        'komentarz' => 'Najleszy admin',
      ]
    );
  }

  function nagroda_usluga($serwer_id, $steam)
  {
    $this->db->insert('acp_uslugi',[
        'user' => 0,
        'serwer' => $serwer_id,
        'steam' => $Steam->toCommunityID($steam),
        'steam_id' => $steam,
        'koniec' => $this->addDay($this->nagroda['czas']),
        'rodzaj' => $this->nagroda['usluga_id'],
      ]
    );
  }
}
 ?>
