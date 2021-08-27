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
$co = Get::string('co');
if(isset($_POST['nowy_rekord'])){
  Controller('ServerConReklamy')->store($dostep->SerwerReklamyDodaj);
  redirect("?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])){
  Controller('ServerConReklamy')->update($dostep->SerwerReklamyEdytuj);
  redirect("?x=$x&xx=$xx");
}
if($co == "usun"){
  Controller('ServerConReklamy')->destroy($dostep->SerwerReklamyUsun);
  redirect("?x=$x&xx=$xx");
}

if(Get::int('wymus_aktualizacje') == 1){
  Model('ServerKonfiguracja')->UpdateNow($dostep->SerwerWymusAktualizacje);
  redirect("?x=$x&xx=$xx");
}

$serwer_array = $servers->servers_list();
?>

<?
if(Controller('ServerConReklamy')->id){
  $reklamaEdit = Controller('ServerConReklamy')->edit();
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Edycja </br>
          <small>Reklama: <?= $reklamaEdit->tekst ?> [ID: <?= $reklamaEdit->id ?>]</small>
        </h3>
        <div class="pull-right box-tools">
        </div>
      </div>
      <div class="box-body">
        <form name='edycja_from' method='post' action='<?= "?x=$x&xx=$xx&edycja=$reklamaEdit->id"; ?>'>
          <input type='hidden' name='id' value='<?= $reklamaEdit->id ?>'>
          <div class="col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
              <?= optionHtml($serwer_array, ['name' => 'e_serwerid' , 'value' => $reklamaEdit->serwer_id]); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Gdzie</span>
              <?= optionHtml(Controller('ServerConReklamy')->gdzie, ['name' => 'e_gdzie' , 'value' => $reklamaEdit->gdzie]); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Tekst</span><input class='form-control' type='text' name='e_tekst' value='<?= $reklamaEdit->tekst ?>'/></div></p>
          </div>
          <div class="col-xs-6">
            <p><div class='form-group input-group'><span class='input-group-addon'>Reklama Czasowa</span>
              <?= optionHtml(Controller('ServerConReklamy')->YesNo, ['name' => 'e_czasowa' , 'value' => $reklamaEdit->czasowa]); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Koniec</span><input class='form-control' type='date' name='e_czasowa_end' value='<?= $reklamaEdit->czasowa_end ?>'/></div></p>
          </div>
          <div class="col-xs-6">
            <p><div class='form-group input-group'><span class='input-group-addon'>Zakres Czasu</span>
              <?= optionHtml(Controller('ServerConReklamy')->YesNo, ['name' => 'e_zakres' , 'value' => $reklamaEdit->zakres]); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Od dnia</span><input class='form-control' type='number' name='e_zakres_start' value='<?= $reklamaEdit->zakres_start ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Do dnia</span><input class='form-control' type='number' name='e_zakres_koniec' value='<?= $reklamaEdit->zakres_stop ?>'/></div></p>
          </div>
          <div class="col-xs-12">
            <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj Reklamę'/></p>
          </div>
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
				  <h3 class="box-title">Reklamy</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Tekst</th>
								<th>Gdzie</th>
								<th>Czasowa</th>
								<th>Zakres</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
  					foreach(Controller('ServerConReklamy')->index() as $reklama){
              $reklama->serwer_nazwa = ($reklama->serwer_id==0) ? 'Wszystkie' : Model('Server')->mod($reklama->serwer_id);
              $reklama->czasowa = ($reklama->czasowa == 1) ? 'Tak' : 'Nie';
              $reklama->zakres = ($reklama->zakres == 1) ? 'Tak' : 'Nie';
						?>
            <tr class="odd gradeX">
              <td><?= $reklama->id ?></td>
							<td><?= $reklama->serwer_nazwa ?></td>
							<td><?= $reklama->tekst ?></td>
							<td><?= Controller('ServerConReklamy')->gdzie[$reklama->gdzie] ?></td>
							<td><?= $reklama->czasowa ?></td>
							<td><?= $reklama->zakres ?></td>
              <td>
                <div class="btn-group">
                  <a href="<?= "?x=$x&xx=$xx&co=edycja&id=$reklama->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                  <a href="<?= "?x=$x&xx=$xx&co=usun&id=$reklama->id" ?>" onclick="return confirm('Jesteś Pewny?')" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
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
                  <span class='input-group-addon'>Miejsce wyświetlenia</span>
                  <?= optionHtml(Controller('ServerConReklamy')->gdzie, ['name' => 'n_gdzie' , 'value' => '']); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Tekst Reklamy</span>
                  <input class="form-control" name="n_tekst">
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
