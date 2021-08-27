
<style>
.example-modal .modal { position: relative; top: auto; bottom: auto; right: auto; left: auto; display: block; z-index: 1; }
.example-modal .modal { background: transparent !important; }
.btn-file { position: relative; overflow: hidden; }
.btn-file input[type=file] { position: absolute; top: 0; right: 0; min-width: 100%; min-height: 100%; font-size: 999px; text-align: right; filter: alpha(opacity=0); opacity: 0; background: red; cursor: inherit; display: block; }
input[readonly] { background-color: white !important; cursor: text !important; }
</style>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
    <p><?= $Messe->show(); ?></p>
	</section>
  </div>
<?
Powiadomienia::read(Get::int('powiadomienie_id'));

$co =  Get::string('co');
$serverId = Controller('ServerUstawienia')->serverId;

if(isset($_POST['nowy_serwer'])){
  Controller('ServerUstawienia')->store($dostep->SerwerDodaj);
  redirect("?x=$x");
}
if(isset($_POST['edycja'])) {
  Controller('ServerUstawienia')->update($dostep->SerwerEdytuj);
	redirect("?x=$x&co=edycja&server_id=$serverId");
}
if(isset($_POST['cron'])) {
  Controller('ServerUstawienia')->updateCron($dostep->SerwerCron);
	redirect("?x=$x&co=cron&server_id=$serverId");
}
if(isset($_FILES['nazwa_pliku'])){
  Controller('ServerUstawienia')->updateImg($dostep->SerwerEdytuj);
  redirect("?x=$x");
}
if($co == "usun"){
  Controller('ServerUstawienia')->destroy($dostep->SerwerUsun);
	redirect("?x=$x");
}

$users_list = User::user_list('Wybierz');

$tak_nie_array = array(1 => 'Tak', 0 => 'Nie', -1 => 'Zablokowane');
$mapy_plugin_array = array('UMC' => 'UMC Mapcycle', 'mapchooser' => 'Mapchooser Extended');
if($co == 'edycja'):
  $severEdit = Controller('ServerUstawienia')->edit();
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Edycja Serwera</h3>
      </div>
      <div class="box-body">
        <form method='post'>
          <input type='hidden' name='id' value='<?= $severEdit->serwer_id ?>'>
          <input type='hidden' name='nazwa' value='<?= $severEdit->nazwa ?>'>
          <div class="col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>ID</span><input class='form-control' type='number' value='<?= $severEdit->serwer_id ?>' disabled /></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Serwera</span><input class='form-control' type='text' value='<?= $severEdit->nazwa ?>' disabled /></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Pozycja Serwera (Kolejność)</span><input class='form-control' type='number' name='istonosc' value='<?= $severEdit->istotnosc ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Mod</span><input class='form-control' type='text' name='mod' value='<?= $severEdit->mod ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>IP</span><input class='form-control' type='text' name='ip' value='<?= $severEdit->ip ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>PORT</span><input class='form-control' type='text' name='port' value='<?= $severEdit->port ?>'/></div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p>Podstawowe:</p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Serwer Włączony</span>
              <?= optionHtml($tak_nie_array, ['name' => 'wlaczony' , 'value' => $severEdit->serwer_on]); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Zdalna edycja plików?</span>
              <?= optionHtml($tak_nie_array, ['name' => 'cronjobs' , 'value' => $severEdit->cronjobs]); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Prefix Sourcebans</span><input class='form-control' type='text' name='prefix_sb' value='<?= $severEdit->prefix_sb ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Prefix HlStats</span><input class='form-control' type='text' name='prefix_hls' value='<?= $severEdit->prefix_hls ?>'/></div></p>

          </div>
          <div class="col-md-6 col-xs-12">
            <p>Połaczenie:</p>
            <p><div class='form-group input-group'><span class='input-group-addon'>RCON</span><input class='form-control' type='password' name='rcon' autocomplete='new-password'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>FTP Login</span><input class='form-control' type='text' name='ftpu' value='<?= $severEdit->ftp_user ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>FTP Hasło</span><input class='form-control' type='password' name='ftpp' autocomplete='new-password'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>FTP Host</span><input class='form-control' type='text' name='ftph' value='<?= $severEdit->ftp_host ?>'/></div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p>Różne:</p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Serwer Testowy</span>
              <?= optionHtml($tak_nie_array, ['name' => 'test_serwer' , 'value' => $severEdit->test_serwer]); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>IP Hlstats</span><input class='form-control' type='text' name='botip' value='<?= $severEdit->ip_bot_hlstats ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Link GOTV</span><input class='form-control' type='text' name='gotvlink' value='<?= $severEdit->link_gotv ?>'/></div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p>Osoby Odpowiedzialne:</p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Junior Admin</span>
              <?= optionHtml($users_list, ['name' => 'junioradmin' , 'value' => $severEdit->ser_a_jr]); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Opiekun</span>
              <?= optionHtml($users_list, ['name' => 'opiekun' , 'value' => $severEdit->ser_a_opiekun]); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Chef Admin</span>
              <?= optionHtml($users_list, ['name' => 'copiekun' , 'value' => $severEdit->ser_a_copiekun]); ?>
            </div></p>
          </div>
          <div class="col-md-12">
            <p><input name='edycja' class='btn btn-primary btn-block' type='submit' value='Edytuj Serwer'/></p>
          </div>
        </form>
        <form method="POST" enctype="multipart/form-data">
          <div class="col-md-12 col-xs-12">
            <p>Banner Serwera:</p>
            <? $severEdit->banner_img = (file_exists("www/server_banner/$severEdit->serwer_id.png")) ? "www/server_banner/$severEdit->serwer_id.png" : "https://acp.sloneczny-dust.pl/www/server_banner/0.png"; ?>
            <div class="col-md-6 col-xs-12">
              <p><img src='<?= $severEdit->banner_img; ?>' class='img-fluid'></img></p>
            </div>
            <div class="col-md-6 col-xs-12">
              <p>Link:</p>
              <p class='text-muted well well-sm no-shadow' style='margin-top: 10px;'><?= $severEdit->banner_img; ?></p>
              <p>
                <div class="input-group">
                  <span class="input-group-btn">
                    <span class="btn btn-default btn-file">
                      Wybierz Plik
                      <input name="nazwa_pliku" name="nazwa_pliku" type="file" id="image">
                      <input type="hidden" name="id" value="<?= $severEdit->serwer_id ?>">
                    </span>
                  </span>
                  <input readonly="readonly" placeholder="<?= $severEdit->banner_img ?>" class="form-control" name="nazwa_pliku" size="35" type="text"/>
                </div>
              </p>
              <p class="help-block">Uwaga! Jeśli istnieje już obrazek zostanie on zastąpiony..</p>
              <p><input name='wgraj_grafike_mapy' class='btn btn-primary btn btn-block' type='submit' value='Prześlij Banner'/></p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?
endif;
if($co == 'cron'):
  $serverCron = Controller('ServerUstawienia')->editCron();
  $serverCron->rangi = ($serverCron->rangi) ?: 0;
  $serverCron->reklamy = ($serverCron->reklamy) ?: 0;
  $serverCron->mapy = ($serverCron->mapy) ?: 0;
  $serverCron->bazy = ($serverCron->bazy) ?: 0;
  $serverCron->cvary = ($serverCron->cvary) ?: 0;
  $serverCron->hextags = ($serverCron->hextags) ?: 0;
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Cronjobs</h3>
      </div>
      <div class="box-body">
        <form method='post'>
          <input type='hidden' name='id' value='<?= $cron ?>'>
          <div class="col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>ID</span><input class='form-control' type='number' value='<?= $serverCron->serwer ?>' disabled /></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Serwera</span><input class='form-control' type='text' value='<?= $serverCron->nazwa ?>' disabled /></div></p>
          </div>
          <div class="col-md-12 col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>Katalog Główny</span><input class='form-control' type='text' name='katalog' value='<?= $serverCron->katalog ?>'/></div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Typ połaczenia</span>
              <?= optionHtml(
                ['ftp' => 'FTP', 'sftp' => 'SFTP (Nie działa)' ],
                ['name' => 'typ_polaczenia' , 'value' => $serverCron->typ_polaczenia]
              ); ?>
            </div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>Rangi [HexTags]</span>
              <?= optionHtml(
                $tak_nie_array,
                ['name' => 'hextags' , 'value' => $serverCron->hextags]
              ); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Reklamy</span>
              <?= optionHtml(
                $tak_nie_array,
                ['name' => 'reklamy' , 'value' => $serverCron->reklamy]
              ); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Bazy Danych</span>
              <?= optionHtml(
                $tak_nie_array,
                ['name' => 'bazy' , 'value' => $serverCron->bazy]
              ); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Usługi</span>
              <?= optionHtml(
                $tak_nie_array,
                ['name' => 'uslugi' , 'value' => $serverCron->uslugi]
              ); ?>
            </div></p>
          </div>
          <div class="col-md-6 col-xs-12">
            <p><div class='form-group input-group'><span class='input-group-addon'>Mapy</span>
              <?= optionHtml(
                $tak_nie_array,
                ['name' => 'mapy' , 'value' => $serverCron->mapy]
              ); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Mapy Plugin</span>
              <select class="form-control" name="mapy_plugin">
                <?
                if(is_null($serverCron->mapy_plugin)):
                  echo '<option value="">Domyślny</option>';
                else:
                  echo '<option value="'.$serverCron->mapy_plugin.'">'.$mapy_plugin_array[$serverCron->mapy_plugin].'</option>';
                endif;

                foreach ($mapy_plugin_array as $key => $value):
                  if($serverCron->mapy_plugin != $key && $key != -1)
                  echo '<option value="'.$key.'">'.$value.'</option>';
                endforeach;
                ?>
              </select>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Cvary</span>
              <?= optionHtml(
                $tak_nie_array,
                ['name' => 'cvary' , 'value' => $serverCron->cvary]
              ); ?>
            </div></p>
            <p><div class='form-group input-group'><span class='input-group-addon'>Help Menu</span>
              <?= optionHtml(
                $tak_nie_array,
                ['name' => 'helpmenu' , 'value' => $serverCron->help_menu]
              ); ?>
            </div></p>
          </div>
          <div class="col-md-12">
            <p><input name='cron' class='btn btn-primary btn-block' type='submit' value='Edytuj Serwer'/></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?
endif;
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
                <th>Nr</th>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Mod</th>
  							<th>IP:Port</th>
  							<th>Junior Admin</th>
  							<th>Opiekun</th>
  							<th>Chef Admin</th>
  							<th></th>
							</tr>
						</thead>
						<tbody>
						<?
  					foreach(Controller('ServerUstawienia')->index() as $server){
              $server->test_serwer = ($server->test_serwer == 0) ? '' : '<span class="btn btn-xs btn-warning">SERWER TESTOWY</span>';
              $server->serwer_on = ($server->serwer_on == 1) ? 'Włączony' : '<span class="btn btn-xs btn-danger">Nie</span>';
              $server->cronjobs = ($server->cronjobs == 1) ? '<span class="btn btn-xs btn-warning">Wyłączone</span>' : $server->cronjobs;
              $server->cronjobs = ($server->cronjobs == -1) ? '<span class="btn btn-xs btn-danger">Zablokowane</span>' : 'Poprawne';

              $server->jr_link = (empty($server->ser_jr)) ? "<i>Brak</i>" : "<a href='?x=account&id=$server->ser_a_jr'>$server->ser_jr</a>";
              $server->opiekun_link = (empty($server->ser_opiekun)) ? "<i>Brak</i>" : "<a href='?x=account&id=$server->ser_a_opiekun'>$server->ser_opiekun</a>";
              $server->copiekun_link = (empty($server->ser_copiekun)) ? "<i>Brak</i>" : "<a href='?x=account&id=$server->ser_a_copiekun'>$server->ser_copiekun</a>";
						?>
            <tr class="odd gradeX">
							<td><?= $server->istotnosc; ?></td>
              <td><?= $server->serwer_id; ?></td>
              <td>
                <?= $server->test_serwer ?>
                <a href="?x=serwery_det&serwer_id=<?= $server->serwer_id; ?>"><?= $server->nazwa; ?></a><br>
                <b>Status:</b> <?= $server->serwer_on; ?> <b>Połączenie FTP:</b> <?= $server->cronjobs ?>
              </td>
							<td><?= $server->mod; ?></td>
							<td><a href="steam://connect/<?= $server->ip.":".$server->port ?>/"><?= $server->ip.":".$server->port ?></a></td>
							<td><?= $server->jr_link; ?></td>
							<td><?= $server->opiekun_link; ?></td>
							<td><?= $server->copiekun_link; ?></td>
              <td>
                <div class="btn-group">
                  <a href="<?= "?x=$x&co=edycja&server_id=$server->serwer_id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                  <a href="<?= "?x=$x&co=cron&server_id=$server->serwer_id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-cube"></i> Prace</a>
                  <a href="<?= "?x=$x&co=usun&server_id=$server->serwer_id" ?>" onclick="return confirm('Jesteś Pewny?')" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
                </div>
              </td>
						</tr>
						<? } ?>
						</tbody>
					</table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj-serwer">
            <i class="fa fa-plus"></i>
            Dodaj Serwer</button>
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
            <h4 class="modal-title">Dodaj Serwer</h4>
          </div>
          <div class="modal-body">
            <form name='nowy_serwer' method='post' action='?x=<?= $x ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Gra</span>
                  <input class="form-control" name="new_gra" value="CSGO">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Mod</span>
                  <input class="form-control" name="mod">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>IP</span>
                  <input class="form-control" name="ip">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Port</span>
                  <input class="form-control" name="port">
                </div>
              </p>
              <p>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="test_serwer"> Serwer testowy <br><small>Serwer nie jest wyświetalny wszelkich listach publicznych.</small>
                  </label>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="nowy_serwer" class="btn btn-primary">Zapisz</button>
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
