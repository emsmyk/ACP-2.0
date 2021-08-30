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
$id = Get::int('id');
$edycja_id = Get::int('edycja');
$co = Get::string('co');

if(!empty($co) && !empty($id) &&  $co == "usun"){
  Controller('ServerConTagi')->destroy($id, $dostep->SerwerTagiUsun);
	redirect("?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])) {
  Controller('ServerConTagi')->update($_POST['id'], $dostep->SerwerTagiEdytuj);
	redirect("?x=$x&xx=$xx&edycja=$edycja_id");
}
if(isset($_POST['nowy_rekord'])) {
  Controller('ServerConTagi')->store($dostep->SerwerTagiDodaj);
  redirect("?x=$x&xx=$xx");
}

if(Get::int('wymus_aktualizacje') == 1){
  Model('ServerKonfiguracja')->UpdateNow($dostep->SerwerWymusAktualizacje);
  redirect("?x=$x&xx=$xx");
}

$serwer_array = $servers->servers_list();

if(!empty($edycja_id)){
  $tag = Controller('ServerConTagi')->edit($edycja_id);
    $tag->staly = ($tag->staly == 1) ? 'checked' : '';
?>
  <div class="row">
    <div class="col-xs-12">
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title">Edycja</h3>
          <div class="pull-right box-tools">
          </div>
        </div>
        <div class="box-body">
          <form method='post'>
            <input type='hidden' name='id' value='<?= $tag->id ?>'>
            <div class="col-xs-12">
              <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
                <?= optionHtml($serwer_array, ['name' => 'serwer' , 'value' => $tag->serwer]); ?>
              </div></p>
              <p><div class='form-group input-group'><span class='input-group-addon'>Tag</span><input class='form-control' type='text' name='tag' value='<?= $tag->tekst ?>'/></div></p>
              <p>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="staly" <?= $tag->staly ?>> Tag stały (Jest za każdym razem)
                  </label>
                </div>
              </p>
            </div>
            <div class="col-xs-12">
              <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
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
				  <h3 class="box-title">Lista Tagów</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Tag</th>
								<th>Stały</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
  					foreach(Controller('ServerConTagi')->index() as $tag){
						?>
            <tr class="odd gradeX">
              <td><?= $tag->id ?></td>
							<td><?= $tag->serwer_nazwa ?></td>
							<td><?= $tag->tekst ?></td>
							<td><?= $tag->staly ?></td>
              <td>
                <div class="btn-group">
                  <a href="<?= "?x=$x&xx=$xx&edycja=$tag->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                  <a href="<?= "?x=$x&xx=$xx&co=usun&id=$tag->id" ?>" onclick="return confirm('Jesteś Pewny?')" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
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
    <div class="modal fade" id="dodaj-serwer">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj</h4>
          </div>
          <div class="modal-body">
            <form method='post'>
              <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
                <?= optionHtml($serwer_array, ['name' => 'serwer' , 'value' => '']); ?>
              </div></p>
              <p><div class='form-group input-group'><span class='input-group-addon'>Tag</span><input class='form-control' type='text' name='tag'/></div></p>
              <p>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="staly"> Tag stały (Jest za każdym razem)
                  </label>
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
