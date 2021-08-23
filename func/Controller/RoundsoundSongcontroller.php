<?php
class RoundsoundSongController
{

  function __construct()
  {
    $this->user = User::get();
  }

  function edit($id)
  {
    $data = SQL::row("SELECT *, (SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = `roundsound_propozycja` ) AS `roundsound_nazwa`, (SELECT `login` FROM `acp_users` WHERE `user` = `akcept` LIMIT 1) AS `login_akceptujacego` FROM `rs_utwory` WHERE `id` = $id LIMIT 1");
    return $data;
  }

  function store($dostep)
  {
    Permission::check($dostep);

    $from = From::check(
      [
        'nazwa' => 'reg',
        'start' => 'reg',
        'end' => 'reg',
        'link' => 'reg'
      ],
      [
        'nazwa.reg' => 'Pusta nazwa. Aby dodać nową musisz wpisać coś..',
        'start.reg' => 'Podaj sekundę (Format: (MM:SS)) od której rozpoczyna się proponowany fragment',
        'end.reg' => 'Podaj sekundę (Format: (MM:SS)) na której kończy się proponowany fragment',
        'link.reg' => 'Podaj link do utworu na YT, proszę..'
      ]
    );

    $last = SQL::insert(
      'rs_utwory',
      [
        'nazwa' => $from->nazwa,
        'wykonawca' => $from->wykonawca,
        'album' => $from->album,
        'start' => $from->start,
        'end' => $from->end,
        'link_yt' => $from->link,
      ]
    );

    Logs::log("Dodano nowy utwór Nazwa: $from->nazwa (ID: $last)", "?x=roundsound&xx=piosenki_edit&id=$last");

    Powiadomienia::new(
      User::getUserHavePermission('RsPiosenkaAkcept'),
      [],
      "?x=roundsound&xx=piosenki_edit&id=$last",
      "Roundsound | Dodano nową piosenkę $from->nazwa, która oczekuje na akceptację",
      "fa fa-music"
    );
  }

  function destroy($id, $dostep)
  {
    Permission::check($dostep);

    $from->piosenka = $this->edit($id);

    SQL::query("DELETE FROM `rs_utwory` WHERE `id` = $id LIMIT 1");
    unlink("www/mp3/".$from->piosenka->mp3_code.".mp3");
    Logs::log("Usunięto piosenke ".$from->piosenka->nazwa." (ID: $id)", "?x=roundsound&xx=piosenki");
  }

  function update($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check(
      [
        'nazwa' => 'reg',
        'start' => 'reg',
        'end' => 'reg',
        'link' => 'reg'
      ],
      [
        'nazwa.reg' => 'Pusta nazwa. Aby dodać nową musisz wpisać coś..',
        'start.reg' => 'Podaj sekundę (Format: (MM:SS)) od której rozpoczyna się proponowany fragment',
        'end.reg' => 'Podaj sekundę (Format: (MM:SS)) na której kończy się proponowany fragment',
        'link.reg' => 'Podaj link do utworu na YT, proszę..'
      ]
    );

    SQL::update(
      'rs_utwory',
      [
        'nazwa' => $from->nazwa,
        'wykonawca' => $from->wykonawca,
        'album' => $from->album,
        'start' => $from->start,
        'end' => $from->end,
        'link_yt' => $from->link,
      ],
      $id
    );

    Logs::log("Zaktualizowano utwór Nazwa: $from->nazwa (ID: $from->id)", "?x=roundsound&xx=piosenki_edit&id=$from->id");
  }

  function uploadMp3($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check();
    $from->file = $_FILES['nazwa_pliku'];
    $from->piosenka = SQL::row("SELECT * FROM `rs_utwory` WHERE `id` = $id LIMIT 1");
    $from->mp3_code = generujLosowyCiag(10, false);

    // sprwadzenie czy losowy ciag jest unikalny
    if(!empty(SQL::one("SELECT * FROM `rs_utwory` WHERE `mp3_code` LIKE '$from->mp3_code'"))){
      $from->mp3_code = generujLosowyCiag(10, false);
    }

    if($from->file['size'] > 2097152){
      return Messe::array([
        'type' => 'warning',
        'text' => "Plik jest za duży, makysmalna wielkość pliku to 2 MB"
      ]);
    }
    if($from->file['type'] != 'audio/mpeg'){
      return Messe::array([
        'type' => 'warning',
        'text' => "Poprawny format pliku to MP3"
      ]);
    }

    move_uploaded_file($from->file['tmp_name'], "www/mp3/".$from->mp3_code.".mp3");

    SQL::update(
      'rs_utwory',
      [
        'mp3' => '1',
        'mp3_code' => $from->mp3_code
      ],
      $id
    );

    Logs::log("Wgrano ".$from->file['name']." ", "?x=roundsound&xx=piosenki_edit&id=$id");
  }

  function akcept($id, $dostep)
  {
    Permission::check($dostep);

    $from->piosenka = $this->edit($id);

    SQL::update(
      'rs_utwory',
      [
        'akcept' => $this->user,
        'data_akcept' => date("Y-m-d H:i:s")
      ],
      $id
    );

    Logs::log("Zakceptowano piosenkę ".$from->piosenka->nazwa." (ID: $from->id)", "?x=roundsound&xx=piosenki_edit&id=$from->id");
  }
}
 ?>
