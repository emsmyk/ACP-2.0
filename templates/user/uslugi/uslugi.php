<?
tytul_strony("Usługi: Moje Usługi");
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
  $co = Get::string('co');
  if(isset($co)){
    $id = Get::int('id');
    $usluga_row = SQL::row("SELECT * FROM `acp_uslugi` WHERE `id` = $id LIMIT 1");
    $serwer_array = array();
    foreach($servers->servers as $serwer_array_dane){
      $serwer_array[$serwer_array_dane->serwer_id]="$serwer_array_dane->nazwa";
    }
    $rodzaj_array = array();
    $rodzaj_array_q = SQL::all("SELECT `id`, `nazwa` FROM `acp_uslugi_rodzaje`");
    foreach($rodzaj_array_q as $rodzaj_array_dane){
      $rodzaj_array[$rodzaj_array_dane->id]="$rodzaj_array_dane->nazwa";
    }

    switch ($co) {
      case 'edycja':
        if(isset($_POST['edycja_from'])){
          Controller('Uslugi')->uslugi_edytuj($dostep->UslugiListaEdytuj);
          redirect("?x=$x&xx=$xx&co=$co&id=$id");
        }
        break;
      case 'usun':
        Controller('Uslugi')->uslugi_usun($id, $dostep->UslugiListaUsun);
        redirect("?x=$x&xx=$xx");
        break;
    }
  }

  ?>
  <? if(!empty($id)): ?>
  <div class="row">
    <div class="col-xs-12">
      <div class="box box">
        <div class="box-header">
          <h3 class="box-title">Edytuj Usługę ID: <?= $id ?></h3>
          <div class="pull-right box-tools">
          </div>
        </div>
        <div class="box-body">
          <form method="post">
            <div class="col-xs-12">
              <input type='hidden' name='id' value='<?= $usluga_row->id ?>'>
              <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
                <?= optionHtml($serwer_array, ['name' => 'serwer' , 'value' => $usluga_row->serwer, 'disable' => 1 ]); ?>
              </div></p>
              <p><div class='form-group input-group'><span class='input-group-addon'>Rodzaj</span>
                <?= optionHtml($rodzaj_array, ['name' => 'rodzaj' , 'value' => $usluga_row->rodzaj, 'disable' => 1 ]); ?>
              </div></p>

              <p><div class='form-group input-group'><span class='input-group-addon'>STEAM</span><input class='form-control' type='text' name='steam' value='<?= $usluga_row->steam_id ?>'/></div></p>
              <p><div class='form-group input-group'><span class='input-group-addon'>Koniec</span><input class='form-control' type='text' name='koniec' value='<?= $usluga_row->koniec ?>'/></div></p>
            </div>
            <div class="col-xs-12">
              <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
            </div>
          </from>
        </div>
      </div>
    </div>
  </div>
  <? endif; ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Lista Usług</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example">
            <thead>
							<tr>
                <th>ID</th>
  							<th>Serwer</th>
  							<th>Steam</th>
  							<th>Koniec</th>
  							<th>Rodzaj</th>
  							<th>Data Dodania</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
            <?
            $player->steam_id = $Steam->toSteamID($player->steam);
            $lista_q = SQL::all("SELECT *,
              (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer` LIMIT 1) AS `serwer_mod`,
              (SELECT `nazwa` FROM `acp_uslugi_rodzaje` WHERE `id` = `rodzaj` LIMIT 1) AS `rodzaj_nazwa`
            FROM `acp_uslugi`; ");
            foreach ($lista_q as $lista) {
            ?>
              <tr class="odd gradeX">
                <td><?= $lista->id ?></td>
                <td><?= $lista->serwer_mod ?></td>
                <td><?= $lista->steam ?><br><small><?= $lista->steam_id ?></small></td>
                <td><?= $lista->koniec ?></td>
                <td><?= $lista->rodzaj_nazwa ?></td>
                <td><?= $lista->data ?></td>
                <td>
                  <div class="btn-group">
                    <a href="<?= "?x=$x&xx=$xx&co=edycja&id=$lista->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                    <a href="<?= "?x=$x&xx=$xx&co=usun&id=$lista->id" ?>" onclick="return confirm('Jesteś Pewny?')" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
                  </div>
                </td>
              </tr>
            <? } ?>
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
<!-- page script -->
<?= Model('DataTable')->table([
  []
]); ?>
</body>
</html>
