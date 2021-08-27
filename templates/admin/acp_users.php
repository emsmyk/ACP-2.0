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
$id = Controller('RootUsers')->id;
$co = Controller('RootUsers')->co;

if(isset($_POST['edycja'])) {
  Controller('RootUsers')->update();
	redirect("?x=$x");
}
if(isset($_POST['dodaj'])) {
  Controller('Register')->addUser();
  redirect("?x=$x");
}

if(!empty($id) && $co == 'password') {
  Controller('RootUsers')->password($id);
  redirect("?x=$x");
}
if(!empty($id) && $co == 'ban'){
  Controller('RootUsers')->ban($id);
  redirect("?x=$x");
}
if(!empty($id) && $co == 'delete'){
  Controller('RootUsers')->destroy($id);
  redirect("?x=$x");
}
?>
<?
if(!empty($id)){
  $user = Controller('RootUsers')->edit();
?>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box">
        <div class="box-header">
          <h3 class="box-title">Edycja użytykowanika #<?= $user->user, " - ", $user->login; ?></h3>
          <div class="pull-right box-tools">
          </div>
        </div>
        <div class="box-body">
          <form name='edycja_from' method='post'>
            <input type='hidden' name='id' value='<?= $user->user; ?>'>
            <p><div class='form-group input-group'><span class='input-group-addon'>Login</span><input class='form-control' type='text' name='login' value='<?= $user->login; ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Mail</span><input class='form-control' type='text' name='mail' value='<?= $user->email; ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>STEAM 64</span><input class='form-control' type='number' name='steam' value='<?= $user->steam; ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Wirepusher</span><input class='form-control' type='text' name='wirepusher' value='<?= $user->wirepusher; ?>'/></div></p>
            <div class='form-group input-group'><span class='input-group-addon'>Grupa</span>
              <select class="form-control" name="grupa">
               <option value="<?= $user->grupa ?>"><?= $user->nazwa_grupy ?></option>
    					 <? $grups = SQL::all("SELECT * FROM `acp_users_grupy` WHERE `id` !=  $user->grupa;");
    					 foreach($grups as $grup){ ?>
    					 	<option value="<?= $grup->id ?>"><?= $grup->nazwa ?></option>
    					 <? } ?>
  					 </select>
           </div>
           <p><input name='edycja' class='btn btn-primary btn-rm btn-block' type='submit' value='Edytuj Użytkownika'/></p>
          </form>
        </div>
			</div>
		</div>
	</div>
<? } ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Lista Użytkowników</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
					<table data-page-length='50' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th></th>
								<th>Nick</th>
								<th>Grupa</th>
								<th>Data Rejestracji</th>
								<th>Ostatnio Logowany</th>
								<th>STEAM</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
						foreach(Controller('RootUsers')->index() as $user){
              $user->banned_text = ($user->banned == 0) ? " <button type='button' class='btn btn-danger btn-xs'>Zablokowany</button>" : "";
						?>
							<tr class="odd gradeX">
								<td><?= $user->user; ?></td>
								<td><img src="<?= $user->steam_avatar ?>" width="25px" height="auto"></img></td>
								<td><a href="?x=account&id=<?= $user->user; ?>"><?= $user->login; ?></a> ( <?= $user->steam_login ?> ) <?= $user->banned_text; ?></td>
								<td><?= $user->grupa; ?></td>
								<td><?= $user->data_rejestracji; ?></td>
								<td><?= $user->last_login; ?></td>
								<td><a href="https://steamcommunity.com/profiles/<?= $user->steam; ?>" target="_blank"><?= $user->steam; ?></a></td>
                <td>
                  <div class="btn-group">
                    <a href="<?= "?x=$x&id=$user->user&co=password" ?>" class="btn btn-default" role="button" aria-pressed="true"><i class="glyphicon glyphicon-lock"></i> Hasło</a>
                    <a href="<?= "?x=$x&id=$user->user&co=ban" ?>" class="btn btn-default" role="button" aria-pressed="true"><i class="fa fa-ban"></i> Blokada</a>
                    <a href="<?= "?x=$x&id=$user->user&co=edycja" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                    <a href="<?= "?x=$x&id=$user->user&co=delete" ?>" onclick="return confirm('Jesteś Pewny?')" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
                  </div>
                </td>
							</tr>
						<? } ?>
						</tbody>
					</table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj"><i class="fa fa-plus"></i> Dodaj Użytkonika</button>
        </div>
			</div>
		</div>
	</div>
  <div class="row">
    <div class="modal fade" id="dodaj">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj nowego użytkownika</h4>
          </div>
          <div class="modal-body">
            <form name='dodaj' method='post'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nick</span>
                  <input class="form-control" name="nick" placeholder="Login">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Hasło</span>
                  <input class="form-control" name="haslo" placeholder="Hasło">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>STEAM ID</span>
                  <input class="form-control" pattern="^STEAM_[01]:[01]:\d+$" placeholder="STEAM_X:X:XXXXXXXX" name="steam_id">
                </div>
              </p>
              <p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="dodaj" class="btn btn-primary">Dodaj</button>
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
