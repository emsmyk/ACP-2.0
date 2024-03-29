<div class="content-wrapper">
 <section class="content">
	<div class="row">
		<div class="col-lg-12">
      <p><?= $Messe->show(); ?></p>
		</div>
	</div>
<?
tytul_strony('Powiadomienia');

$co = Get::string('co');
$id = Get::int('id');

if(!empty($co) && !empty( Get::int('id') )) {
	if($co == "usun") {
		SQL::query("DELETE FROM `acp_users_notification` WHERE `id` = '".$id."' AND `u_id`= '".$player->user."';");
    Messe::array([
      'type' => 'success',
      'text' => "Powiadomienie zostało usunięte."
    ]);
	}
	else if($co == "read") {
		SQL::query("UPDATE `acp_users_notification` SET `read` = '0' WHERE `id` = '".$id."' AND `u_id`= '".$player->user."';");
    Messe::array([
      'type' => 'success',
      'text' => "Powiadomienie zostało oznaczone jako odczytane."
    ]);
	}
	redirect('?x=powiadomienia');
}
if(!empty($co)) {
	if($co == "odczytane_all") {
		SQL::query("UPDATE `acp_users_notification` SET `read` = '0' WHERE `u_id`= '".$player->user."';");
    Messe::array([
      'type' => 'success',
      'text' => "Wszystkie powioadmienie zostały oznacznone jako odczytane"
    ]);
	}
	else if($co == "usun_all") {
		SQL::query("DELETE FROM `acp_users_notification` WHERE `u_id`= '".$player->user."';");
    Messe::array([
      'type' => 'success',
      'text' => "Wszystkie powiadomienia zostały skasowane"
    ]);
	}
	redirect('?x=powiadomienia');
}
?>
  <div class="row">
	<div class="col-lg-12">
	<div class="box">
	 <div class="box-header with-border"><h3 class="box-title">Powiadomienia</h3></div>
	 <div class="box-body">
	  <table id="example" class="table table-bordered table-striped" width="100%">
		<thead>
			<tr>
				<th width="1%"></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?
		$powiadomienia = SQL::all("SELECT * FROM `acp_users_notification` WHERE `u_id` = '".$player->user."' ORDER BY `data` DESC;");
		foreach($powiadomienia as $powiadomienie){
      $powiadomienie->icon_kolor = ($powiadomienie->read==1) ? 'text-aqua' : '';
		?>
			<tr class="odd gradeX">
				<td><i class="<?= $powiadomienie->icon.' '.$powiadomienie->icon_kolor ?>"></i></td>
				<td>
  				<a href="<?= $powiadomienie->link ?>&powiadomienie_id=<?= $powiadomienie->id ?>"><?= $powiadomienie->text; ?></a>
        </td>
				<td><?= Date::relative($powiadomienie->data); ?></td>
				<td>
					<div class="btn-group">
					  <button type="button" class="btn btn-default" onclick="window.location.href='<?= $powiadomienie->link ?>&powiadomienie_id=<?= $powiadomienie->id ?>'">Otwórz</button>
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
						<li><a target="_blank" href="<?= $powiadomienie->link ?>&powiadomienie_id=<?= $powiadomienie->id ?>">Nowa Karta</a></li>
						<li class="divider"></li>
						<li><a href="?x=powiadomienia&co=read&id=<?= $powiadomienie->id ?>">Odczytane</a></li>
						<li><a href="?x=powiadomienia&co=usun&id=<?= $powiadomienie->id ?>">Usuń</a></li>
					  </ul>
					</div>
				</td>
			</tr>
		<? } ?>
		</tbody>
	  </table>
	 </div>
	 <div class="box-footer">
	  <div class="pull-right">
	   <button type="submit" onclick="return confirm('Oznaczyć wszystkie powiadomienia jako odczytane?')"  class="btn btn-default" onclick="window.location.href='?x=powiadomienia&co=odczytane_all'"><i class="fa fa fa-bell"></i> Odczytaj Wszystkie</button>
	   <button type="submit" onclick="return confirm('Czy jesteś pewny i chcesz usunąć wszystkie powiadomienia?')" class="btn btn-danger" onclick="window.location.href='?x=powiadomienia&co=usun_all'"><i class="fa fa-times"></i> Usuń Wszystkie</button>
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
