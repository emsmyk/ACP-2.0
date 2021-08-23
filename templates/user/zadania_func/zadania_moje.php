<?
tytul_strony("Zadania: Moje Zadania");

$TaskController = Controller('Task');
$TaskModel = Model('Task');
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
if(isset($_POST['nowe'])) {
  $TaskListController->store($dostep->ZadaniaDodaj);
  header("Location: ?x=$x&xx=$xx");
}
?>
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?= $TaskController->indexMy()['user']['zgloszonych'] ?></h3>

          <p>Zgłoszonych</p>
        </div>
        <div class="icon">
          <i class="ion ion-plus"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?= $TaskController->indexMy()['user']['zaakceptowanych'] ?> / <?=$TaskController->indexMy()['user']['odrzuconych'] ?></h3>

          <p>Akceptowanych / Odrzuconych</p>
        </div>
        <div class="icon">
          <i class="fa fa-thumbs-o-up"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3><?= $TaskController->indexMy()['user']['zrealizowanych'] ?></h3>

          <p>Zrealizowanych</p>
        </div>
        <div class="icon">
          <i class="fa fa-university"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
          <div class="inner">
            <h3><?= $TaskController->indexMy()['user']['procent'] ?> <sup style="font-size: 20px">%</sup></h3>

            <p>Procent Zrealizowanych</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
        </div>
      </div>
  </div>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Zgłoszone Zadania w trakcie realizacji</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example">
            <thead>
							<tr>
                <th>ID</th>
  							<th>Platforma</th>
  							<th>Typ</th>
  							<th>Temat</th>
  							<th>Status</th>
  							<th>Serwer</th>
  							<th>Data</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
            <?
            foreach ($TaskController->indexMy()['zleconychAll'] as $zadania) {
              if($zadania->platforma_web == 1):
                $zadania->nazwa_serwera = '<i>Nie dotyczy</i>';
              elseif(is_null($zadania->serwer_id)):
                $zadania->nazwa_serwera = '<i>Serwer nie istenieje</i>';
              elseif($zadania->serwer_id == 0):
                $zadania->nazwa_serwera = 'Wszystkie';
              endif;
              $zadania->prc_wyk_small = ($zadania->status == 2) ? '<button type="button" class="btn bg-'.$zadania->kolor_wykonania.' btn-xs">'.$zadania->procent_wykonania.' %</button>' :  '' ;
            ?>
              <tr class="odd gradeX">
                <td><?= $zadania->id ?></td>
                <td><?= $zadania->platforma ?></td>
                <td><?= $zadania->typ ?></td>
                <td><a href="<?= "?x=$x&xx=zadanie&id=$zadania->id" ?>"><?= $zadania->temat ?></a> <?= $zadania->prc_wyk_small ?></td>
                <td><button type='button' class='btn btn-<?= $zadania->kolor ?> btn-xs'><?= $zadania->status_nazwa ?></button></td>
                <td><?= $zadania->nazwa_serwera ?></td>
                <td><?= $zadania->data ?></td>
                <td>
                  <a href="<?= "?x=$x&xx=zadanie&id=$zadania->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-ellipsis-h"></i></button></a>
                </td>
              </tr>
            <? } ?>
            </tbody>
          </table>
				</div>
      </div>
		</div>
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Realizowane Zadania</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="example3">
            <thead>
							<tr>
                <th>ID</th>
  							<th>Platforma</th>
  							<th>Typ</th>
  							<th>Temat</th>
  							<th>Status</th>
  							<th>Serwer</th>
  							<th>Data</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
            <?
            foreach ($TaskController->indexMy()['zrealizowanychAll'] as $zadania) {
              if($zadania->platforma_web == 1):
                $zadania->nazwa_serwera = '<i>Nie dotyczy</i>';
              elseif(is_null($zadania->serwer_id)):
                $zadania->nazwa_serwera = '<i>Serwer nie istenieje</i>';
              elseif($zadania->serwer_id == 0):
                $zadania->nazwa_serwera = 'Wszystkie';
              endif;

              $zadania->prc_wyk_small = ($zadania->status == 2) ? '<button type="button" class="btn bg-'.$zadania->kolor_wykonania.' btn-xs">'.$zadania->procent_wykonania.' %</button>' :  '' ;
            ?>
              <tr class="odd gradeX">
                <td><?= $zadania->id ?></td>
                <td><?= $zadania->platforma ?></td>
                <td><?= $zadania->typ ?></td>
                <td><a href="<?= "?x=$x&xx=zadanie&id=$zadania->id" ?>"><?= $zadania->temat ?></a> <?= $zadania->prc_wyk_small ?></td>
                <td><button type='button' class='btn btn-<?= $zadania->kolor ?> btn-xs'><?= $zadania->status_nazwa ?></button></td>
                <td><?= $zadania->nazwa_serwera ?></td>
                <td><?= $zadania->data ?></td>
                <td>
                  <a href="<?= "?x=$x&xx=zadanie&id=$zadania->id" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-ellipsis-h"></i></button></a>
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
  ['name' => '#example', 'sort_type' => 'desc'],
  ['name' => '#example3', 'sort_type' => 'desc'],
]); ?>
</body>
</html>
