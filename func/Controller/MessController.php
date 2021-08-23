<?php
class MessController
{
  function __construct()
  {
    $this->db = DB::getInstance();

    $this->user = User::get();
  }

  function read($id)
  {
    $this->db->query("update acp_messages set m_status = 1 where m_id = $id and m_to = $this->user limit 1");
  }

  function send()
  {
    $from = From::check([
      'to' => 'reg',
      'tytul' => 'reg',
      'text' => 'reg'
    ],[
      'to.reg' => 'Nazwa użytkownika nie może być pusta',
      'tytul.reg' => 'Tytuł wiadomości nie może byc pusty',
      'text.reg' => 'Tekst wiadomości nie może być pusty',
    ]);

    $from->to_user = $this->db->get_row("select user from acp_users where login ='".$from->to."' limit 1")[0];
    if(empty($from->to_user)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Nie ma takiego użytkownika. ( Pamiętaj że login steam nie jest takie sam jak login użytkownika )"
      ]);
    }

    $this->db->insert('acp_messages', [
      'm_from' => $this->user,
      'm_to' => $from->to_user,
      'm_type' => '2',
      'm_tytul' => $from->tytul,
      'm_text' => $from->text,
    ]);
  }

  function odrzuc_wiadomosc($msg_id)
  {
    $this->db->query("delete from acp_messages where m_id = $msg_id and m_czyja = $this->user and m_type = 3 limit 1");
    return Messe::array([
      'type' => 'success',
      'text' => "Wersja Robocza została odrzucona (usunięta)."
    ]);
  }

  function zapisz_wiadomosc()
  {
    $from = From::check([
      'to' => 'reg',
      'tytul' => 'reg',
      'text' => 'reg'
    ],[
      'to.reg' => 'Nazwa użytkownika nie może być pusta',
      'tytul.reg' => 'Tytuł wiadomości nie może byc pusty',
      'text.reg' => 'Tekst wiadomości nie może być pusty',
    ]);

    $from->to_user = $this->db->get_row("select user from acp_users where login ='".$from->to."' limit 1")[0];
    if(is_null($from->to_user)){
      $from->to_user = 0;
    }

    $this->db->insert('acp_messages', [
      'm_from' => $this->user,
      'm_to' => $from->to_user,
      'm_type' => '3',
      'm_czyja' => $this->user,
      'm_tytul' => $from->tytul,
      'm_text' => $from->text,
    ]);

    return Messe::array([
      'type' => 'success',
      'text' => "Wiadomość została zapisana jako wersja robocza."
    ]);
	}

  function zapisz_wiadomosc_update($id)
  {
    $from = From::check([
      'to' => 'reg',
      'tytul' => 'reg',
      'text' => 'reg'
    ],[
      'to.reg' => 'Nazwa użytkownika nie może być pusta',
      'tytul.reg' => 'Tytuł wiadomości nie może byc pusty',
      'text.reg' => 'Tekst wiadomości nie może być pusty',
    ]);		$to_user = $this->db->get_row("select user from acp_users where login ='".$to."' limit 1")[0];

    $from->to_user = $this->db->get_row("select user from acp_users where login ='".$from->to."' limit 1")[0];
    if(is_null($from->to_user)){
      $from->to_user = 0;
    }

    $this->db->update('acp_messages', [
      'm_to' => $from->to_user,
      'm_tytul' => $from->tytul,
      'm_text' => $from->text,
    ], [
      'm_id' => $id
    ]);

    return Messe::array([
      'type' => 'success',
      'text' => "Wersja Robocza została zaaktualizowana"
    ]);
	}

  function destroy($id, $type)
  {
		switch($type){
			case 1:
				$this->db->query("delete from acp_messages where m_id = $id and m_to = $this->user and m_type = 1 limit 1");
			break;
			case 2:
				$this->db->query("delete from acp_messages where m_id = $id and m_from = $this->user and m_type = 2 limit 1");
			break;
		}

    return Messe::array([
      'type' => 'success',
      'text' => "Wiadomość została skasowana"
    ]);
  }

  function kosz($id, $type)
  {
    switch($type){
      case 1:
        $this->db->query("UPDATE `acp_messages` SET `m_type` = '0', `m_czyja` = '$this->user' WHERE `m_id` = $id and m_to = $this->user and m_type = 1 limit 1");
      break;
      case 2:
        $this->db->query("UPDATE `acp_messages` SET `m_type` = '0', `m_czyja` = '$this->user' WHERE `m_id` = $id and m_from = $this->user and m_type = 2 limit 1");
      break;
    }

    return Messe::array([
      'type' => 'success',
      'text' => "Wiadomość została przeniesiona do kosza"
    ]);
  }

  function destroyOld($limit, $day)
  {
    $this->db->query("DELETE FROM `acp_messages` WHERE `m_date` < NOW() - INTERVAL $day DAY AND `m_type` = 0 LIMIT $limit");
  }
}
 ?>
