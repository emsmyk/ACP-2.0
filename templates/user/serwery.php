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
$serverId = Get::int('detale');
if($serverId){
  $serwer = SQL::row("SELECT *,
    (SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_jr` LIMIT 1) AS jr,
    (SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_opiekun` LIMIT 1) AS opiekun,
    (SELECT `login` FROM `acp_users` WHERE `user` = `ser_a_copiekun` LIMIT 1) AS chefadmin
     FROM `acp_serwery` WHERE `serwer_id` = $serverId;");

  if($serwer->jr == '') $serwer->jr = 'brak';
  if($serwer->opiekun == '') $serwer->opiekun = 'brak';
  if($serwer->chefadmin == '') $serwer->chefadmin = 'brak';

  $serwer->mapa = Model('Server')->map_img($serwer->mapa);
?>
 <div class="row">
   <div class="col-xs-12">
     <div class="box box">
      <div class="box-header">
       <h3 class="box-title">Detale Serwera <small><?= $serwer->nazwa ?></small></h3>
       <div class="pull-right box-tools">
       </div>
      </div>
      <div class="box-body">
      <div class="col-sm-12">
        <img src="<?= $serwer->mapa ?>" class="col-lg-2" class="img-responsive" alt="Brak obrazka..">
        <h4>Nazwa Serwera </h4><p><?= $serwer->nazwa; ?></p>
        <h4>Graczy </h4><p><?= $serwer->graczy." / ".$serwer->max_graczy." [".$serwer->boty."]"; ?></p>
        <h4>Mapa </h4><p><?= $serwer->mapa; ?></p>
      </div>
      <hr>
      <div class="col-sm-6">
        <h4>Odpowiedzialni za serwer </h4>
        <p><b>Junior Admin:</b> <?= $serwer->jr; ?></p>
        <p><b>Opiekun:</b> <?= $serwer->opiekun; ?></p>
        <p><b>Chef Admin:</b> <?= $serwer->chefadmin; ?></p>
      </div>
     </div>
     </div>
   </div>


   <div class="col-xs-12">
     <div class="box box">
       <div class="box-header">
         <h3 class="box-title">Aktualnie na serwerze</h3>
         <div class="pull-right box-tools">
         </div>
       </div>
       <div class="box-body">
         <table data-order='[[ 2, "desc" ]]' data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
           <thead>
             <tr>
               <th>Nick</th>
               <th>Zabójstw</th>
               <th>Czas Gry</th>
             </tr>
           </thead>
           <tbody>
           <?
           $lista_graczy = SQL::one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$serverId."' LIMIT 1;");
           $lista_graczy = json_decode($lista_graczy, true);
           if (!empty($lista_graczy)) {
             foreach ($lista_graczy as $id => $player) {
           ?>
             <tr class="odd gradeX">
               <td><?= $player['Name']; ?></td>
               <td><?= $player['Frags']; ?></td>
               <td><?= $player['TimeF']; ?></td>
             </tr>
           <? } } ?>
           </tbody>
         </table>
       </div>
     </div>
 </div>
<?
}
else {
?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Lista Serwerów</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th width="1%"></th>
								<th>Gra</th>
								<th>Nazwa</th>
								<th>IP</th>
								<th>Mod</th>
								<th>Gracze / Sloty [ Boty ]</th>
								<th>Mapa</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
						$serwery = SQL::all("SELECT * FROM `acp_serwery` WHERE `test_serwer` = '0' ORDER BY `istotnosc` +0 ASC;");
						foreach($serwery as $serwer){
              $serwer->status = ($serwer->status == 1) ? '<i class="fa fa-circle text-red"></i>' : '<i class="fa fa-circle text-green"></i>';
              $serwer->game_img = strtolower($serwer->game);
 						?>
							<tr class="odd gradeX">
								<td><?= $serwer->istotnosc; ?></td>
								<td><?= $serwer->status; ?></td>
								<td><img src="./www/games/<?=$serwer->game_img; ?>.png" /></td>
								<td><?= $serwer->nazwa; ?></td>
								<td><a href="steam://connect/<?= $serwer->ip.":".$serwer->port ?>/"><?= $serwer->ip.":".$serwer->port ?></a></td>
								<td><?= $serwer->mod; ?></td>
								<td><?= $serwer->graczy." / ". $serwer->max_graczy ." [". $serwer->boty."]"; ?></td>
								<td><?= $serwer->mapa; ?></td>
                <td>
                  <div class="btn-group">
                    <a href="<?= "?x=$x&detale=$serwer->serwer_id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Detale</a>
                    <a href="<?= "https://gosetti.pl/serwery/$serwer->ip:$serwer->port" ?>" class="btn btn-info" role="button" aria-pressed="true"><i class="fa fa-star"></i> Głosuj</a>
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
<? } ?>
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
