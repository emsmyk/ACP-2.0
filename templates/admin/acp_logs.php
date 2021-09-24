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
$ss_acp_logi = (empty($_SESSION['ss_acp_logi'])) ? 0 : $_SESSION['ss_acp_logi'];
if(Get::string('co') == 'zdalny'){
  Controller('Logi')->zmien_ss_logi_zdalne($ss_acp_logi);
  redirect("?x=$x");
}
?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
        <div class="box-header with-border">
          <h3 class="box-title">ACP Logi</h3>

          <div class="box-tools pull-right">
            <div class="btn-group">
              <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-wrench"></i></button>
              <ul class="dropdown-menu" role="menu">
                <?
                if($ss_acp_logi == 1) {
                  echo "<li><a href='?x=$x&co=zdalny'>Pokaż - Prace zdalne</a></li>";
                }
                else {
                  echo "<li><a href='?x=$x&co=zdalny'>Ukryj - Prace zdalne</a></li>";
                }
                ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="box-body">
					<table data-page-length='50' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
								<th>Data</th>
								<th>Użytkownik</th>
								<th>Log</th>
								<th>Moduł</th>
							</tr>
						</thead>
						<tbody>
						<?
						foreach(Controller('Logi')->index(
              ['hide' => $ss_acp_logi, 'sort' => 0, 'sort_type' => '', 'sort_column' => '', 'limit' => 0, 'limit_count' => '' ]
            ) as $log){
						?>
							<tr class="odd gradeX">
								<td><?= $log->data; ?></td>
								<td><?= $log->nick; ?></td>
								<td><?= $log->tekst; ?></td>
								<td><?= $log->page; ?></td>
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
<!-- PACE -->
<script src="./www/bower_components/PACE/pace.min.js"></script>
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
  ['sort_type' => 'desc']
]); ?>
</body>
</html>
