<?php
class RoundsoundController
{
  function __construct()
  {
    $this->setting = [
      'rs_roundsound' => SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound';"),
      'rs_roundsound_c' => SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound_c';"),
    ];
  }

  function propozycja($rs_id)
  {
    $from = From::check([
      'nazwa' => 'reg',
      'start' => 'reg',
      'end' => 'reg',
      'link' => 'reg',
    ], [
      'nazwa.reg' => 'Nazwa nie może być pusta, podaj tytuł piosenki.',
      'start.reg' => 'Podaj poczatek fragmenu utworu',
      'end.reg' => 'Podaj koniec fragmentu utworu',
      'link.reg' => 'Podaj link do utworu na YT, proszę..'
    ]);

    if(!preg_match_all('~[a-z]+://\S+~', $from->link, $out)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Pole link musi zawierać link do serwisu muzycznego (YouTube)"
      ]);
    }
    if(!strpos($from->start, ':')){
      return Messe::array([
        'type' => 'warning',
        'text' => "Początek utworu powinień być w formacie (Mintut):(Sekundy) np. 1:02"
      ]);
    }
    if(!strpos($from->end, ':')){
      return Messe::array([
        'type' => 'warning',
        'text' => "Koniec utworu powinień być w formacie (Mintut):(Sekundy) np. 1:32"
      ]);
    }

    $last = SQL::insert('rs_utwory',[
      'nazwa' => $from->nazwa,
      'wykonawca' => $from->wykonawca,
      'album' => $from->album,
      'start' => $from->start,
      'end' => $from->end,
      'link_yt' => $from->link,
      'roundsound_propozycja' => $rs_id
    ]);

    Logs::log("Zaproponowano nowy utwór: $from->nazwa (ID: $last)", "?x=roundsound&xx=piosenki_edit&id=$last", '-1');

    // powiadomienie
    Powiadomienia::new(
      User::getUserHavePermission('RsPiosenkaAkcept'),
      [],
      "?x=roundsound&xx=piosenki_edit&id=$last",
      "Roundsound | Dodano propozycję nowej piosenki $from->nazwa, która oczekuje na akceptację",
      "fa fa-music"
    );

  }

  function vote($rs_id, $id)
  {
    $gosc->przegladarka = $_SERVER['HTTP_USER_AGENT'];
    $gosc->ip = $_SERVER['REMOTE_ADDR'];
    $gosc->vote_rs = SQL::one("SELECT `roundsound` FROM `rs_vote` WHERE `ip` LIKE '%$gosc->ip%' AND `przegladarka` LIKE '%$gosc->przegladarka%' ORDER BY `data` DESC LIMIT 1");
    $gosc->vote_time = SQL::one("SELECT `data` FROM `rs_vote` WHERE `ip` LIKE '%$gosc->ip%' AND `przegladarka` LIKE '%$gosc->przegladarka%' ORDER BY `data` DESC LIMIT 1");
    $gosc->utwor = SQL::one("SELECT `utwor` FROM `rs_vote` WHERE `ip` LIKE '%$gosc->ip%' AND `przegladarka` LIKE '%$gosc->przegladarka%' ORDER BY `data` DESC LIMIT 1");
    $rs->rs_vote = SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_vote' LIMIT 1"); /* 1 - jednba piosenka 0 - wiele piosenek*/
    $rs->rs_time = SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_vote_time' LIMIT 1");

    // jeżeli to google bot to nie dodawanie glosu
    if(isset($gosc->przegladarka) && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $gosc->przegladarka)){
        return;
    }

    if($rs->rs_vote == '1'):
      if(strtotime($gosc->vote_time)< (time() - $rs->rs_time*60) || empty($gosc->vote_time)){
        /* START - Od tego momentu kopia wykonania oddania glosu*/
        SQL::query("UPDATE `rs_utwory`  SET `vote` = `vote` + 1 WHERE `id` = '$id' LIMIT 1");
        SQL::query("INSERT INTO `rs_vote` (`roundsound`, `utwor`, `ip`, `przegladarka`) VALUES ('$rs_id', '$id', '$gosc->ip', '$gosc->przegladarka')");
        return Messe::array([
          'type' => 'success',
          'text' => "Oddałeś poprawnie głoś. Zapis poprawny."
        ]);        /* KONIEC*/
      }
      return Messe::array([
        'type' => 'warning',
        'text' => "Już głosowałeś, spróbuj ponownie kolejnego dnia"
      ]);

    elseif($rs->rs_vote == '0'):
      $gosc->utwor = SQL::one("SELECT `utwor` FROM `rs_vote` WHERE `ip` LIKE '%$gosc->ip%' AND `przegladarka` LIKE '%$gosc->przegladarka%' AND `utwor` = '$id' ORDER BY `data` DESC LIMIT 1");
      if($id == $gosc->utwor && strtotime($gosc->vote_time)< (time() - $rs->rs_time*60)){
        /* START - Od tego momentu kopia wykonania oddania glosu*/
        query("UPDATE `rs_utwory`  SET `vote` = `vote` + 1 WHERE `id` = '$id' LIMIT 1");
        query("INSERT INTO `rs_vote` (`roundsound`, `utwor`, `ip`, `przegladarka`) VALUES ('$rs_id', '$id', '$gosc->ip', '$gosc->przegladarka')");
        return Messe::array([
          'type' => 'success',
          'text' => "Oddałeś poprawnie głoś. Zapis poprawny."
        ]);        /* KONIEC*/
        /* KONIEC*/
      }
      elseif($id != $gosc->utwor){
        /* START - Od tego momentu kopia wykonania oddania glosu*/
        query("UPDATE `rs_utwory`  SET `vote` = `vote` + 1 WHERE `id` = '$id' LIMIT 1");
        query("INSERT INTO `rs_vote` (`roundsound`, `utwor`, `ip`, `przegladarka`) VALUES ('$rs_id', '$id', '$gosc->ip', '$gosc->przegladarka')");
        return Messe::array([
          'type' => 'success',
          'text' => "Oddałeś poprawnie głoś. Zapis poprawny."
        ]);        /* KONIEC*/
        /* KONIEC*/
      }
      return Messe::array([
        'type' => 'warning',
        'text' => "Już głosowałeś, spróbuj ponownie kolejnego dnia"
      ]);

    endif;
  }

  function DanePubliczne($id)
  {
    $dane = new stdClass();

    if(is_null($id) || empty($id)){
      $dane->rs = SQL::row("SELECT * FROM `rs_roundsound` WHERE `id` = ".$this->setting['rs_roundsound']." LIMIT 1");
    }
    else {
      $dane->rs = SQL::row("SELECT * FROM `rs_roundsound` WHERE `id` = $id LIMIT 1");
    }

    $dane->lista_piosenek = json_decode($dane->rs->lista_piosenek);

    return $dane;
  }

  function DanePubliczneAktualny()
  {
    return SQL::one("SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = ".$this->setting['rs_roundsound']." LIMIT 1");
  }

  function DanePubliczneAktualnyID()
  {
    return $this->setting['rs_roundsound'];
  }

  function DanePubliczneKolejny()
  {
    return SQL::one("SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = ".$this->setting['rs_roundsound_c']." LIMIT 1");
  }

  public function DanePubliczneKolejnyID()
  {
    return $this->setting['rs_roundsound'];
  }
}

?>
