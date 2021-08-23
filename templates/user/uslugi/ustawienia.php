<?
tytul_strony("Usługi: Ustawienia");
?>
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
if(isset($_POST)){
  $usluga = SQL::row("SELECT * FROM `acp_uslugi_rodzaje` WHERE `id` = ".$_POST['id']." LIMIT 1");
  if(isset($_POST['edytuj'])){
    Controller('Ustawienia')->updateConf([
      ['time_uslugi' => 'time_uslugi', 'value' => $_POST['time_uslugi']],
      ['time_uslugi' => 'cron_optym_stare_uslugi_limit', 'value' => $_POST['cron_optym_stare_uslugi_limit']],
      ['time_uslugi' => 'cron_optym_stare_uslugi_hour', 'value' => $_POST['cron_optym_stare_uslugi_hour']],
      ['time_uslugi' => 'cron_optym_stare_uslugi_day', 'value' => $_POST['cron_optym_stare_uslugi_day']],
    ]);
    header("Location: ?x=$x&xx=$xx");
  }
  if(isset($_POST['Dodaj'])){
    Controller('Uslugi')->dodaj_usluge($dostep->UslugiNowaUsluga);
    header("Location: ?x=$x&xx=$xx");
  }
  if(isset($_POST['save'])){
    Controller('Uslugi')->zapisz_zmiany($dostep->UslugiNowaUsluga);
    header("Location: ?x=$x&xx=$xx");
  }
  if(isset($_POST['usun'])){
    Controller('Uslugi')->usun_usluge($dostep->UslugiNowaUsluga);
    header("Location: ?x=$x&xx=$xx");
  }
  if(isset($_POST['edytuj_publiczne'])){
    Controller('Uslugi')->edytuj_dane_publiczne($dostep->UslugiNowaUsluga);
    header("Location: ?x=$x&xx=$xx");
  }
}
if(!empty($_GET['usluga']) && !empty($_GET['on_off'])){
  Model('Roundsound')->ustawienia_OnOff($_GET['serwer'],  $_GET['usluga'], $_GET['on_off'], $dostep->UslugiNowaUsluga);
  header("Location: ?x=$x&xx=$xx");
}
?>
	<div class="row">
		<div class="col-xs-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Podstawowe<br><small>Ustawienia podstawowe modulu roundsound</small></h3>
        </div>
        <div class="box-body">
          <form method='post'>
            <p><div class='form-group input-group'><span class='input-group-addon'>Aktualizacja</span>
              <?= optionHtml($cronOption, ['name' => 'time_uslugi', 'value' => $acp_system['time_uslugi']]); ?></div></p>
           <p><div class='form-group input-group'><span class='input-group-addon'>Ostatnia Aktualizacja</span><input class='form-control' type='text' value='<?= $acp_system['cron_uslugi']; ?>' disabled /></div></p>
           <p><div class='form-group input-group'><span class='input-group-addon'>cron_optym_stare_uslugi_limit</span><input class='form-control' type='text' name='cron_optym_stare_uslugi_limit' value='<?= $acp_system['cron_optym_stare_uslugi_limit']; ?>' /></div></p>
           <p><div class='form-group input-group'><span class='input-group-addon'>cron_optym_stare_uslugi_hour</span><input class='form-control' type='text' name='cron_optym_stare_uslugi_hour' value='<?= $acp_system['cron_optym_stare_uslugi_hour']; ?>' /></div></p>
           <p><div class='form-group input-group'><span class='input-group-addon'>cron_optym_stare_uslugi_day</span><input class='form-control' type='text' name='cron_optym_stare_uslugi_day' value='<?= $acp_system['cron_optym_stare_uslugi_day']; ?>' /></div></p>
           <p><input name='edytuj' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
          </from>
        </div>
      </div>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Rodzaje Usług<br><small>Dostępne Usługi oraz dostępne możliwości blokad oraz dostępów</small></h3>
        </div>
        <? if(isset($_POST['serwery'])): ?>
          <div class="box-body">
            <ul class="todo-list">
              <?
              $usluga->serwery = json_decode($usluga->serwery);
              $serweryQ = SQL::all("SELECT `serwer_id`, `istotnosc`, `mod`, `nazwa`, `cronjobs` FROM `acp_serwery` ORDER BY `acp_serwery`.`istotnosc` ASC");
              foreach ($serweryQ as $row):
                  $row->kolor = (in_array($row->serwer_id, $usluga->serwery)) ? 'success' : 'danger';
                  $row->text1 = (in_array($row->serwer_id, $usluga->serwery)) ? '<i class="fa fa-check"></i> ON' : 'OFF';
                  $row->text2 = (in_array($row->serwer_id, $usluga->serwery)) ? 'Wyłącz' : 'Włącz';
                  $row->OnOff = (in_array($row->serwer_id, $usluga->serwery)) ? 'off' : 'on';
  								if($row->cronjobs == 1):
              ?>
                <li class="list-group-item">
                  <b> <?= $row->istotnosc ?></b> <?= $row->nazwa ?> [<?= $row->mod ?>]
                  <small class="label label-<?= $row->kolor ?>"><?= $row->text1 ?></small>
                  <div class="tools">
                    <a href="<?= "?x=$x&xx=$xx&usluga=$usluga->id&serwer=$row->serwer_id&on_off=$row->OnOff" ?>"><?= $row->text2 ?></a>
                  </div>
                </li>
              <?  endif;
              endforeach; ?>
            </ul>
          </div>
        <? endif; ?>
        <? if(isset($_POST['dane_publiczne'])): ?>
          <div class="box-body">
            <form method='post'>
              <input type="hidden" name="id" value="<?= $usluga->id ?>" />
              <input type="hidden" name="nazwa" value="<?= $usluga->nazwa ?>" />
               <p><div class='form-group input-group'><span class='input-group-addon'>Dostęp Publiczny</span><input class='form-control' type='text' name='publiczna' value='<?= $usluga->publiczna; ?>' /></div></p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Obrazek IMG</span><input class='form-control' type='text' name='img' value='<?= $usluga->img ?>' /></div></p>
               <p><div class='form-group input-group'><span class='input-group-addon'>Opis</span><input class='form-control' type='text' name='opis' value='<?= $usluga->opis ?>' /></div></p>
               <p><input name='edytuj_publiczne' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
            </form>
          </div>
        <? endif; ?>
        <div class="box-body">
          <table class="table table-hover">
            <tbody>
              <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Flagi</th>
                <th></th>
              </tr>
              <?
              $lista_uslug = SQL::all("SELECT * FROM `acp_uslugi_rodzaje`");
              foreach ($lista_uslug as $key => $value) {
              ?>
              <tr>
                <form method='post'>
                  <input type="hidden" name="id" value="<?= $value->id ?>" />
                  <td><?= $value->id ?></td>
                  <td><input class='form-control' type='text' name='nazwa' value='<?= $value->nazwa ?>' /></td>
                  <td><input class='form-control' type='text' name='flagi' value='<?= $value->flags ?>' /></td>
                  <td>
                    <div class="btn-group">
                      <input name='serwery' type="submit" class="btn btn-default" value="Serwery">
                      <!-- <input name='dane_publiczne' type="submit" class="btn btn-default" value="Dane Publiczne"> -->
                      <input name='save' type="submit" class="btn btn-success" value="Zapisz">
                      <input name='usun' type="submit" class="btn btn-danger" value="Usuń">
                    </div>
                  </td>
                </form>
              </tr>
              <? } ?>
              <tr>
                <form method='post'>
                  <td>-</td>
                  <td><input class='form-control' type='text' name='n_nazwa' /></td>
                  <td><input class='form-control' type='text' name='n_flagi' /></td>
                  <td>
                    <div class="btn-group">
                      <input name='Dodaj' type="submit" class="btn btn-default" value="Dodaj">
                    </div>
                  </td>
                </form>
              </tr>
            </tbody>
          </table>
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
<!-- SZABLONY -->
<script src="./www/dist/js/demo.js"></script>
<!-- page script -->
</body>
</html>
