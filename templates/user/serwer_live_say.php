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
$id = (isset($_GET['serwer'])) ? (int)$_GET['serwer'] : null;
$serwer = SQL::row("SELECT `serwer_id`, `serwer_on`, `prefix_hls`, `mod`, `nazwa`, `ip`, `port` FROM `acp_serwery` WHERE `serwer_id` = $id LIMIT 1");
if($id == null || $id == 0){
  Messe::array([
    'type' => 'warning',
    'text' => "Brak wybranego serwera.."
  ]);
  redirect("?x=wpisy");
}
elseif(empty($serwer->ip)){
  Messe::array([
    'type' => 'warning',
    'text' => "Wybrany serwer nie istnieje"
  ]);
  redirect("?x=wpisy");
}

$serwer->say = Model('Hlstats')->say($serwer->prefix_hls);
if(empty($serwer->say->say)){
  $serwer->say->say->{1}->id = '-------';
  $serwer->say->say->{1}->name = 'none';
  $serwer->say->say->{1}->message = 'brak wiadomości..';
  $serwer->say->say->{1}->map = '-';
}
?>

	<div class="row">
		<div class="col-lg-12">
			<div class="box box">
				<div class="box-header">
          <i class="fa fa-text-height fa-fw"></i>
				  <h3 class="box-title">Czat Say</h3> <br>
          <small><?= $serwer->mod ?> | <?= $serwer->nazwa ?></small>
          <div class="box-tools pull-right">
            <a href="?x=serwery_det&serwer_id=<?= $serwer->serwer_id ?>" class="btn btn-box-tool"><i class="fa fa-reply"></i></a>
          </div>
				</div>
				<div class="box-body table-responsive no-padding chat">
          <table class="table table-dark">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Gracz: Wiadomość</th>
                <th scope="col">Data</th>
                <th scope="col">Mapa</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <?
            foreach ($serwer->say->say as $value):
              $value->steam = "STEAM_0:".$value->steam;
              $value->steam = $Steam->toCommunityID($value->steam);
            ?>
            <tbody>
              <tr>
                <th scope="row"><?= $value->id ?></th>
                <td><b><?= $value->name ?></b>: <?= $value->message ?></td>
                <td><?= Date::relative($value->eventTime) ?></td>
                <td><?= $value->map ?></td>
                <td>
                  <div class="btn-group">
                    <a target="_blank" href="https://hlstats.sloneczny-dust.pl/hlstats.php?mode=playerinfo&player=<?= $value->playerId ?>" class="btn btn-sm btn-default"><i class="fa fa-bar-chart"></i> HLstats</a>
                    <a target="_blank" href="https://steamcommunity.com/profiles/<?= $value->steam ?>" class="btn btn-sm btn-default"><i class="fa fa-steam"></i> Profil Steam</a>
                  </div>
                </td>
              </tr>
            </tbody>
            <? endforeach; ?>
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

<script>
  $(document).ready(function () {
    $('.chat').slimScroll({ height: '800px'});
  });
</script>
</body>
</html>
