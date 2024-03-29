<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
</style>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
    <p><?= $Messe->show(); ?></p>
	</section >
  </div>
<?
$co = Get::string('co');

$row = Controller('RoundsoundList')->edit(Get::int('id'));
tytul_strony("RoundSound: Lista - $row->nazwa");

if(empty($row)){
  redirect("?x=roundsound&xx=lista");
}

if(isset($_POST['edit'])) {
  Controller('RoundsoundList')->update($row->id, $dostep->RsListaEdycja);
  redirect("?x=$x&xx=$xx&id=$row->id");
}
if(isset($_POST['dodaj_piosenke'])) {
  Controller('RoundsoundListSong')->addSong($_POST['piosenka'], $row->id, $dostep->RsListaDodajPiosenke);
  redirect("?x=$x&xx=$xx&id=$row->id");
}
if($co == 'usun'){
  Controller('RoundsoundList')->destroy($row->id, $dostep->RsListaUsun);
  redirect("?x=$x");
}
if($co == 'usun_piosenke_z_listy'){
  Controller('RoundsoundListSong')->deletesong(Get::int('id_piosenki'), $row->id, $dostep->RsListaUsunPiosenke);
  redirect("?x=$x&xx=$xx&id=$row->id");
}
if($co == 'ustaw_status'){
  Controller('RoundsoundList')->status($row->id, Get::string('jaki'), $dostep->RsUstawStatus);
  redirect("?x=$x&xx=$xx&id=$row->id");
}

?>
  <div class="row">
    <div class="col-lg-12">
      <div class="box box">
        <div class="box-header">
				  <h3 class="box-title">RoundSound - <?= $row->nazwa ?></h3>
				</div>
        <div class="box-body">
          <h4>Nazwa:</h4><p><?= $row->nazwa ?></p>
          <h4>Utworzył:</h4><p><?= $row->user_name ?></p>
          <h4>Data dodania:</h4><p><?= $row->data ?></p>
          <hr>
          <? if($row->id == $row->aktualnie_grany_rs): ?>
            <p> Lista Utworów jest aktualnie ustawiona jako 'Aktualnie Grana'. </p>
          <? elseif($row->id == $row->w_przygotowaniu):?>
            <p> Lista Utworów jest aktualnie ustawiona jako 'W przygotwaniu'. </p>
          <? endif; ?>
        </div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#edit"><i class="fa fa-edit"></i> Edytuj</button>
          <a class="btn btn-danger" href="<?= "?x=$x&xx=$xx&id=$row->id&co=usun" ?>" role="button"><i class="fa fa fa-close"></i> Usuń</a>

          <? if($row->id == $row->w_przygotowaniu):?>
            <a class="btn btn-default" href="<?= "?x=$x&xx=$xx&id=$row->id&co=ustaw_status&jaki=aktualna" ?>" role="button"><i class="fa fa fa-close"></i> Ustaw jako: Aktualnie Graną</a>
          <? elseif($row->id != $row->aktualnie_grany_rs && $row->id != $row->w_przygotowaniu): ?>
            <a class="btn btn-default" href="<?= "?x=$x&xx=$xx&id=$row->id&co=ustaw_status&jaki=w_przygotowaniu" ?>" role="button"><i class="fa fa fa-close"></i> Ustaw jako: W przygotwaniu</a>
          <? endif; ?>
        </div>
      </div>
      <div class="box box">
        <div class="box-header">
				  <h3 class="box-title">Lista Utworów</h3>
				</div>
        <div class="box-body">
          <ul class="todo-list">
            <?
            $row->lista_piosenek = json_decode($row->lista_piosenek);
            foreach ($row->lista_piosenek as $value):
              $row->piosenka_details[$value] = SQL::row("SELECT `nazwa`, `wykonawca`, `album`, `link_yt`, `vote`, `mp3`, `mp3_code` FROM `rs_utwory` WHERE `id` = $value LIMIT 1");
              if(!empty( $row->piosenka_details[$value] )):
                $row->piosenka_details[$value]->mp3_color = ($row->piosenka_details[$value]->mp3 == 1) ? 'success' : 'danger';
                ?>
                <li class="list-group-item">
                  <b><?= $row->piosenka_details[$value]->nazwa ?> - <?= $row->piosenka_details[$value]->wykonawca ?></b> <i><?= $row->piosenka_details[$value]->album ?></i>
                  <span class="label label-<?= $row->piosenka_details[$value]->mp3_color ?>"> <i class="fa fa-music"> </i> Mp3 File</span>
                  <div class="tools">
                    <a href="<?= "?x=$x&xx=piosenki_edit&id=$value" ?>"><i class="fa fa-edit"></i> Edytuj</a>
                    <a href="<?= "?x=$x&xx=$xx&id=$row->id&co=usun_piosenke_z_listy&id_piosenki=$value" ?>"><i class="fa fa-trash-o"></i> Usuń</a>
                  </div>
                </li>
                <?
              endif;
            endforeach;
            ?>
          </ul>
        </div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj_piosenke"><i class="fa fa-plus"></i> Dodaj Piosenkę</button>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="modal fade" id="dodaj_piosenke">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Dodaj nową piosenkę </h4>
          </div>
          <div class="modal-body">
            <form name='dodaj_piosenke' method='post'>
              <input type="hidden" name="id" value="<?= $row->id ?>">
              <p><div class='form-group input-group'><span class='input-group-addon'>Wybierz piosenkę</span>
                <select class="form-control" name="piosenka">
                  <?
                  $piosenki_dostepne = SQL::all("SELECT `id`, `nazwa`, `wykonawca` FROM `rs_utwory` WHERE `mp3` != '0' AND `akcept` != '0'");
                  foreach ($piosenki_dostepne as $value) {
                    echo '<option value="'.$value->id.'">'.$value->nazwa.' - '.$value->wykonawca.'</option>';
                  }
                  ?>
                </select>
              </div></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="dodaj_piosenke" class="btn btn-primary">Dodaj</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="edit">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edytuj</h4>
          </div>
          <div class="modal-body">
            <form name='edit' method='post'>
              <input type="hidden" name="id" value="<?= $row->id ?>">
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa</span>
                  <input class="form-control" name="nazwa" type="text" value="<?= $row->nazwa ?>">
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="edit" class="btn btn-primary">Edytuj</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</div>
<? require_once("./templates/user/stopka.php");  ?>


<div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="./www/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="./www/bower_components/datatables.net-bs/js/dataTables.responsive.js"></script>
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
<!-- page script -->
<?= Model('DataTable')->table([
  []
]); ?>
</body>
</html>
