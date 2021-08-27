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
$co = Controller('RootGrups')->co;
$id = Controller('RootGrups')->id;

if(isset($_POST['nowa_grupa'])) {
  Controller('RootGrups')->store();
  redirect("?x=$x");
}

if($co == 'usun_grupa' && $id >= 0) {
  Controller('RootGrups')->destroy($id);
  redirect("?x=$x");
}
?>
<?
if($co == 'edytuj_grupa' && $id >= 0) {
  $edytuj_grupa_q = Controller('RootGrups')->edit();
  $grupa_dostep = json_decode($edytuj_grupa_q->dostep)[0];
  $grupa_moduly = json_decode($edytuj_grupa_q->moduly);

  if(isset($_POST['edycja_grupy'])) {
    $e_id = (int) $_POST['id'];
    $e_nazwa = $_POST['e_nazwa'];
    $e_kolor = $_POST['e_kolor'];
    $tablica_post = Controller('Ustawienia')->zmien_moduly($_POST['checkboxvar']);
    $tablica_post_dostep =  Controller('Ustawienia')->zmien_dostep($_POST['e_dostep']);

    query("UPDATE `acp_users_grupy` SET `dostep` = '[".$tablica_post_dostep."]', `moduly` = '$tablica_post', `nazwa` = '$e_nazwa', `kolor` = '$e_kolor' WHERE `id` = $e_id;");

    Logs::log("Grupa $e_nazwa (ID: $e_id) została zedytowana");
  	redirect("?x=$x&co=$co&id=$id");
  }
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header with-border">
        <h3 class="box-title">Edycja Grupy | <?= $edytuj_grupa_q->nazwa ?></h3>
      </div>
      <div class="box-body">
        <form name='edycja_grupy' method='post' action='?x=acp_grupy&co=edytuj_grupa&id=<?= $edytuj_grupa_q->id; ?>'>
        <input type='hidden' name='id' value='<?= $edytuj_grupa_q->id; ?>'>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Nazwa</span><input class='form-control' type='text' name='e_nazwa' value='<?= $edytuj_grupa_q->nazwa; ?>'/></div>
          </p>
          <p>
            <div class='form-group input-group'>
              <span class='input-group-addon'>Kolor</span><input class='form-control' type='text' name='e_kolor' value='<?= $edytuj_grupa_q->kolor; ?>'/></div>
          </p>
          <p><input name='edycja_grupy' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
          </from>
        </div>
    </div>
  </div>

  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header with-border">
        <h3 class="box-title">Dostęp | Moduły & Uprawnienia</h3>
      </div>
      <div class="box-body">
      <form name='edycja_grupy' method='post' action='?x=acp_grupy&co=edytuj_grupa&id=<?= $edytuj_grupa_q->id; ?>'>
      <input type='hidden' name='id' value='<?= $edytuj_grupa_q->id; ?>'>

      <?
      $moduly_q = SQL::all("SELECT * FROM `acp_moduly`;");
      foreach ($moduly_q as $modul){
        if(in_Array($modul->nazwa, $grupa_moduly)) {
          $checked = 'checked'; $checked_text = 'Odznacz aby zabrać dostęp'; $checked_collapse = ' <small>(Posiada dostęp)</small>';
        }
        else {
          $checked = '';  $checked_text = 'Zaznacz Aby nadać dostęp'; $checked_collapse = '';
        }
        if($modul->opis == '') { $modul->opis = 'brak opisu modulu..'; }
      ?>
        <div class="box-group" id="accordion">
          <div class="panel box">
            <div class="box-header with-border">
              <p class="box-title ">
                <a class="text-black" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $modul->id ?>">
                  <i class="<?= $modul->ikona ?>"></i> <span><?= $modul->nazwa_wys ?></span> <?= $checked_collapse ?>
                </a>
              </p>
            </div>
            <div id="collapse<?= $modul->id ?>" class="panel-collapse collapse">
              <div class="box-body">
                <div class="list-group">
                  <li class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                     <?=$modul->opis ?>
                    </div>
                    <p class="mb-1">  <input type="checkbox" value="<?=$modul->nazwa ?>" name="checkboxvar[]" <?=$checked ?> /> <?=$checked_text ?></p>
                  </li>
                </div>
                <hr>
                <div class="list-group">
                  <?
                  $modul_akcja_q = SQL::all("SELECT * FROM `acp_moduly_akcje` WHERE `modul_id` = $modul->id ; ");
                  foreach ($modul_akcja_q as $modul_akcja){
                  ?>
                  <li class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                      <div class='form-group input-group'><span class='input-group-addon'><?= $modul_akcja->akcja_wys ?></span>
                        <select class='form-control' name='e_dostep[]'>
                          <?
                          if(in_array(1, (array) $grupa_dostep->{$modul_akcja->akcja})) {
                            echo "<option value='$modul_akcja->akcja-1'>Tak</option>";
                            echo "<option value='$modul_akcja->akcja-0'>Nie</option>";
                          }
                          else{
                            echo "<option value='$modul_akcja->akcja-0'>Nie</option>";
                            echo "<option value='$modul_akcja->akcja-1'>Tak</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <p class="mb-1">Opis:</b> <?= $modul_akcja->opis ?></p>
                  </li>
                  <? } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      <? } ?>


      <p><input name='edycja_grupy' class='btn btn-primary btn-sm btn-block' type='submit' value='Zapisz zmiany'/></p>
      </from>
      </div>
    </div>
  </div>
</div>
<?}
else {
?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box box">
          <div class="box-header">
            <h3 class="box-title">Grupy</h3>
          </div>
          <div class="box-body">
            <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nazwa</th>
                  <th>Użytkowników</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?
    						foreach(Controller('RootGrups')->index() as $grupy){
    						?>
                <tr class="odd gradeX">
                  <td><?= $grupy->id; ?></td>
                  <td><?= $grupy->nazwa; ?></td>
                  <td><?= $grupy->liczba_userow; ?></td>
                  <td>
                    <a href="<?= "?x=$x&co=edytuj_grupa&id=$grupy->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                    <a href="<?= "?x=$x&co=usun_grupa&id=$grupy->id" ?>"><button type="button" class="btn btn-danger"><i class="fa fa-times"></i></button></a>
                  </td>
                </tr>
                <? } ?>
              </tbody>
            </table>
          </div>
          <div class="box-footer clearfix no-border">
            <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj-grupe"><i class="fa fa-plus"></i> Nowa Grupa</button>
          </div>
        </div>
      </div>
    </div>
    <? } ?>

    <div class="row">
      <div class="modal fade" id="dodaj-grupe">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Dodaj Grupę</h4>
            </div>
            <form name='nowa_grupa' method='post' action='?x=<?= $x ?>'>
              <div class="modal-body">
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Nazwa</span>
                    <input class="form-control" name="new_nazwa">
                  </div>
                </p>
                <p>
                  <div class='form-group input-group'>
                    <span class='input-group-addon'>Kolor</span>
                    <input class="form-control" name="new_kolor">
                  </div>
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
                <button type="input" name="nowa_grupa" class="btn btn-primary">Zapisz</button>
              </div>
            </form>
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
