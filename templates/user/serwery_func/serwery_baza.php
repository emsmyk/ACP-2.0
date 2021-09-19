<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
</style>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
    <p><?= $Messe->show(); ?></p>
	</section>
  </div>

<?
$co =  Get::string('co');
if(isset($_POST['nowy_rekord'])){
  Controller('ServerConDB')->store($dostep->SerwerBazaDodaj);
  redirect("?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])){
  Controller('ServerConDB')->update($dostep->SerwerBazaEdytuj);
  redirect("?x=$x&xx=$xx");
}
if($co == "usun"){
  Controller('ServerConDB')->destroy($dostep->SerwerBazaUsun);
  redirect("?x=$x&xx=$xx");
}

if(Get::int('wymus_aktualizacje') == 1){
  Model('ServerKonfiguracja')->UpdateNow($dostep->SerwerWymusAktualizacje);
  redirect("?x=$x&xx=$xx");
}

$serwer_array = $servers->servers_list();
$wl_wyl_array = Controller('ServerConDB')->OnOff;
?>
<?
if(Controller('ServerConDB')->id){
  $databaseEdit = Controller('ServerConDB')->edit();
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Edycja </br>
          <small>DB: <?= $databaseEdit->nazwa ?> [ID: <?= $databaseEdit->id ?>]</small>
        </h3>
        <div class="pull-right box-tools">
        </div>
      </div>
      <div class="box-body">
        <form name='edycja_from' method='post' action='<?= "?x=$x&xx=$xx&edycja=$databaseEdit->id"; ?>'>
          <input type='hidden' name='id' value='<?= $databaseEdit->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
            <?= optionHtml($serwer_array, ['name' => 'e_serwerid' , 'value' => $databaseEdit->serwer_id, 'disable' => 1 ]); ?>
          </div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Nazwa</span><input class='form-control' type='text' name='e_nazwa' value='<?= $databaseEdit->nazwa ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Driver</span><input class='form-control' type='text' name='e_driver' value='<?= $databaseEdit->d_driver ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Host</span><input class='form-control' type='text' name='e_host' value='<?= $databaseEdit->d_host ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Baza</span><input class='form-control' type='text' name='e_baza' value='<?= $databaseEdit->d_baze ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>User</span><input class='form-control' type='text' name='e_user' value='<?= $databaseEdit->d_user ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Hasło</span><input class='form-control' type='text' name='e_haslo' value='<?= $databaseEdit->d_pass ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Port</span><input class='form-control' type='text' name='e_port' value='<?= $databaseEdit->d_port ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>TimeOut</span><input class='form-control' type='text' name='e_timeout' value='<?= $databaseEdit->d_timeout ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Port/TimeOut</span>
            <?= optionHtml($wl_wyl_array, ['name' => 'e_time_out_on' , 'value' => $databaseEdit->d_time_port_on, 'disable' => 1 ]); ?>
          </div></p>
					<p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj Bazę Danych'/></p>
        </form>
      </div>
    </div>
  </div>
</div>
<?
}
?>
	<div class="row">
		<div class="col-lg-8">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Bazy Danych</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Nazwa</th>
								<th>Driver</th>
								<th>Host</th>
								<th>Baza</th>
								<th>User</th>
								<th>Port / TimeOut</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
  					foreach(Controller('ServerConDB')->index() as $db){
              $db->serwer_nazwa = ($db->serwer_id==0) ? 'Wszystkie' : Model('Server')->mod($db->serwer_id);
						?>
            <tr class="odd gradeX">
              <td><?= $db->id ?></td>
								<td><?= $db->serwer_nazwa ?></td>
								<td><?= $db->nazwa ?></td>
								<td><?= $db->d_driver ?></td>
								<td><?= $db->d_host ?></td>
								<td><?= $db->d_baze ?></td>
								<td><?= $db->d_user ?></td>
								<td><? if($db->d_time_port_on == 1) {?><?= $db->d_port; ?> / <?= $db->d_timeout; ?> <? } else { echo "Brak"; }?></td>
                <td>
                  <div class="btn-group">
                    <a href="<?= "?x=$x&xx=$xx&co=edycja&id=$db->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                    <a href="<?= "?x=$x&xx=$xx&co=usun&id=$db->id" ?>" onclick="return confirm('Jesteś Pewny?')" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
                  </div>
                </td>
						</tr>
						<? } ?>
						</tbody>
					</table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj-serwer"><i class="fa fa-plus"></i> Dodaj</button>
        </div>
			</div>
		</div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-maroon">
        <div class="inner">
          <h2>Ostatnia Aktualizcja</h2>
          <p><?= Model('ServerKonfiguracja')->UpdateLast(); ?> </p>
        </div>
        <div class="icon">
          <i class="fa fa-clock-o"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-olive">
        <div class="inner">
          <h2>Kolejna aktualizacja</h2>
          <p><?= Model('ServerKonfiguracja')->UpdateNext(); ?> </p>
        </div>
        <div class="icon">
          <i class="fa fa-bolt"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-xs-12">
      <div class="small-box bg-blue">
        <div class="inner">
          <?= Model('ServerKonfiguracja')->serwery_aktualizowane(); ?>
        </div>
        <div class="icon">
          <i class="fa fa-server"></i>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
    <!-- okno wyskakujace -->
    <div class="modal fade" id="dodaj-serwer">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj</h4>
          </div>
          <div class="modal-body">
            <form name='now_rekord' method='post' action='<?= "?x=$x&xx=$xx"; ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <?= optionHtml($serwer_array, ['name' => 'n_serwer' , 'value' => '']); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa</span>
                  <input class="form-control" name="n_nazwa">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Driver</span>
                  <input class="form-control" name="n_driver">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Host</span>
                  <input class="form-control" name="n_host">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Baza</span>
                  <input class="form-control" name="n_baza">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>User</span>
                  <input class="form-control" name="n_user">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Hasło</span>
                  <input class="form-control" name="n_haslo">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Port</span>
                  <input class="form-control" name="n_port">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>TimeOut</span>
                  <input class="form-control" name="n_timeout">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Port/TimeOut</span>
                  <?= optionHtml(['1' => 'ON', '0' => 'OFF'], ['name' => 'n_time_out_on' , 'value' => '']); ?>
                </div>
              </p>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="nowy_rekord" class="btn btn-primary">Zapisz</button>

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
