<?php
class RoundsoundSongController
{
  function __construct()
  {
  }

  function addSong($SongId, $RsId, $dostep)
  {
    Permission::check($dostep);

    $from->rs = Controller('RoundsoundList')->edit($RsId);
    $from->dane_piosenka =  Controller('RoundsoundSong')->edit($SongId);
    $from->lista = json_decode($from->rs->lista_piosenek);

    if(in_array($SongId, $from->lista)){
      return Messe::array([
        'type' => 'warning',
        'text' => "Ta piosenka jest dodana już do listy utworów"
      ]);
    }

    $from->lista[] = (int)$SongId;

    SQL::update(
      'rs_roundsound',
      [
        'lista_piosenek' => json_encode($from->lista),
      ],
      $RsId
    );
    SQL::update(
      'rs_utwory',
      [
        'roundsound_propozycja_dodane' => '1',
      ],
      $SongId
    );

    Logs::log("Dodano utwór ". $from->piosenka->nazwa." (ID: $SongId)  do listy utworów ".$from->rs->nazwa." (ID: $RsId)", "?x=roundsound&xx=lista_edit&id=$RsId");
  }

  function deleteSong($SongId, $RsId, $dostep)
  {
    Permission::check($dostep);

    $from->rs = Controller('RoundsoundList')->edit($RsId);
    $from->piosenka =  Controller('RoundsoundSong')->edit($SongId);

    $from->lista = json_decode($from->rs->lista_piosenek);
    $from->lista = array_diff($from->lista, [ (int)$SongId ]);
    $from->lista = json_encode(array_values($from->lista));

    SQL::update(
      'rs_roundsound',
      [
        'lista_piosenek' => $from->lista,
      ],
      $RsId
    );

    Logs::log("Skasowano utwór ". $from->piosenka->nazwa." (ID: $SongId)  z listy utworów ".$from->rs->nazwa." (ID: $RsId)", "?x=roundsound&xx=lista_edit&id=$RsId");
  }
}
 ?>
