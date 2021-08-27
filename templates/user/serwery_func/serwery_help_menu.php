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
$co =  Get::string('co');
if(isset($_POST['nowy_rekord'])){
  Controller('ServerConHelpMenu')->store($dostep->SerwerHelpMenuDodaj);
  redirect("?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])){
  Controller('ServerConHelpMenu')->update($dostep->SerwerHelpMenuEdytuj);
  redirect("?x=$x&xx=$xx");
}
if($co == "usun"){
  Controller('ServerConHelpMenu')->destroy($dostep->SerwerHelpMenuUsun);
  redirect("?x=$x&xx=$xx");
}

if(Get::int('wymus_aktualizacje') == 1){
  Model('ServerKonfiguracja')->UpdateNow($dostep->SerwerWymusAktualizacje);
  redirect("?x=$x&xx=$xx");
}

$konfiguruj =  Get::string('konfiguruj');

if(isset($_POST['opis_vipa_form_add'])) {
  Controller('ServerConHelpMenu')->storeVip($dostep->SerwerHelpMenuKonfiguracja);
  redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=opis_vipa");
}
if(isset($_POST['opis_vipa_form_zapisz'])) {
  Controller('ServerConHelpMenu')->updateVip($dostep->SerwerHelpMenuKonfiguracja);
  redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=opis_vipa");
}
if(isset($_POST['opis_vipa_kolejonosc_up'])) {
  Model('ServerKonfiguracja')->sortKolejnosc(
    [
      'kierunek' => 'up',
      'id' => $_POST['id'],
      'ColumnSort' => 'kolejnosc',
      'table' => 'acp_serwery_helpmenu_vip',
      'ColumnId' => 'id'
    ],
    $dostep->SerwerHelpMenuKonfiguracja
  );
  redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=opis_vipa");
}
if(isset($_POST['opis_vipa_kolejonosc_down'])) {
  Model('ServerKonfiguracja')->sortKolejnosc(
    [
      'kierunek' => 'down',
      'id' => $_POST['id'],
      'ColumnSort' => 'kolejnosc',
      'table' => 'acp_serwery_helpmenu_vip',
      'ColumnId' => 'id'
    ],
    $dostep->SerwerHelpMenuKonfiguracja
  );
  redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=opis_vipa");
}
if(isset($_POST['opis_vipa_usun'])){
 Controller('ServerConHelpMenu')->destroyVip($player->user, $dostep->SerwerHelpMenuKonfiguracja);
 redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=opis_vipa");
}

if(isset($_POST['komenda_form_add'])) {
  Controller('ServerConHelpMenu')->storeKomenda($dostep->SerwerHelpMenuKonfiguracja);
  redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=lista_komend");
}
if(isset($_POST['komenda_form_zapisz'])) {
  Controller('ServerConHelpMenu')->updateKomenda($dostep->SerwerHelpMenuKonfiguracja);
  redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=lista_komend");
}
if(isset($_POST['komenda_kolejonosc_up'])){
  Model('ServerKonfiguracja')->sortKolejnosc(
    [
      'kierunek' => 'up',
      'id' => $_POST['id'],
      'ColumnSort' => 'kolejnosc',
      'table' => 'acp_serwery_helpmenu_komendy',
      'ColumnId' => 'id'
    ],
    $dostep->SerwerHelpMenuKonfiguracja
  );
  redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=lista_komend");
}
if(isset($_POST['komenda_kolejonosc_down'])){
  Model('ServerKonfiguracja')->sortKolejnosc(
    [
      'kierunek' => 'down',
      'id' => $_POST['id'],
      'ColumnSort' => 'kolejnosc',
      'table' => 'acp_serwery_helpmenu_komendy',
      'ColumnId' => 'id'
    ],
    $dostep->SerwerHelpMenuKonfiguracja
  );
  redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=lista_komend");
}
if(isset($_POST['komenda_usun'])){
 Controller('ServerConHelpMenu')->destroyKomenda($dostep->SerwerHelpMenuKonfiguracja);
 redirect("?x=$x&xx=$xx&edycja=".Controller('ServerConHelpMenu')->id."&konfiguruj=lista_komend");
}

$serwer_array = $servers->servers_list();
$wl_wyl_array = Controller('ServerConHelpMenu')->OnOff;
?>

<?
if(Controller('ServerConHelpMenu')->id):
  $acp_r_d = Controller('ServerConHelpMenu')->edit();
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
        <? if(empty($konfiguruj)): ?>
        <form method='post'>
          <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
          <input type='hidden' name='serwer' value='<?= $acp_r_d->serwer_id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
            <?= optionHtml($serwer_array, ['name' => 'e_serwerid' , 'value' => $acp_r_d->serwer_id]); ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Lista Serwerów</span>
            <?= optionHtml($wl_wyl_array, ['name' => 'wl_wyl_array' , 'value' => $acp_r_d->lista_serwerow]); ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Lista Adminów</span>
            <?= optionHtml($wl_wyl_array, ['name' => 'lista_adminow' , 'value' => $acp_r_d->lista_adminow]); ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Opis Vipa</span>
            <?= optionHtml($wl_wyl_array, ['name' => 'opis_vipa' , 'value' => $acp_r_d->opis_vipa]); ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Lista Komend</span>
            <?= optionHtml($wl_wyl_array, ['name' => 'lista_komend' , 'value' => $acp_r_d->lista_komend]); ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Statystyki</span>
            <?= optionHtml($wl_wyl_array, ['name' => 'statystyki' , 'value' => $acp_r_d->statystyki]); ?>
          </div></p>
          <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
        </form>
        <? endif; ?>

        <? if($konfiguruj == 'opis_vipa'): ?>
        <div class="col-xs-12">
          <table class="table table-condensed">
            <tbody>
              <tr>
                <th class="col-xs-1">#</th>
                <th class="col-xs-8">Tekst</th>
                <th class="col-xs-3"></th>
              </tr>
              <? $opis_vipa_q = SQL::all("SELECT * FROM `acp_serwery_helpmenu_vip` WHERE `helpmenu_id` = ".Controller('ServerConHelpMenu')->id." ORDER BY `kolejnosc` ASC");?>
              <?php foreach ($opis_vipa_q as $key): ?>
                <tr>
                  <form method='post'>
                    <input type="hidden" name="id" value="<?= $key->id ?>">
                    <input type="hidden" name="helpmenu_id" value="<?= $key->helpmenu_id ?>">
                    <input type="hidden" name="serwer_id" value="<?= $key->serwer_id ?>">

                    <td><input type="number" class="form-control" value="<?= $key->kolejnosc ?>" disabled></td>
                    <td><input type="text" class="form-control" name="tekst" value="<?= $key->tekst ?>" ></td>
                    <td>
                      <div class="btn-group">
                        <input name='opis_vipa_form_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                        <input name='opis_vipa_kolejonosc_up' type="submit" class="btn btn-default" value='UP'>
                        <input name='opis_vipa_kolejonosc_down' type="submit" class="btn btn-default" value='DOWN'>
                        <input name='opis_vipa_usun' type="submit" class="btn btn-danger" value='Usuń'>
                      </div>
                    </td>
                  </form>
                </tr>
              <?php endforeach; ?>
              <tr>
                <form method='post'>
                  <input type="hidden" name="id" value="<?= $key->id ?>">
                  <input type="hidden" name="helpmenu_id" value="<?= $acp_r_d->id ?>">
                  <input type="hidden" name="serwer_id" value="<?= $acp_r_d->serwer_id ?>">

                  <td><input type="number" class="form-control" disabled></td>
                  <td><input type="text" class="form-control" name="tekst"></td>
                  <td>
                    <input name='opis_vipa_form_add' type="submit" class="btn btn-default" value='Dodaj'>
                  </td>
                </form>
              </tr>
            </tbody>
          </table>
        </div>
        <? endif; ?>
        <? if($konfiguruj == 'lista_komend'): ?>
        <div class="col-xs-12">
          <table class="table table-condensed">
            <tbody>
              <tr>
                <th class="col-xs-1">#</th>
                <th class="col-xs-4">Komenda</th>
                <th class="col-xs-4">Tekst</th>
                <th class="col-xs-3"></th>
              </tr>
              <? $lista_komend_q = SQL::all("SELECT * FROM `acp_serwery_helpmenu_komendy` WHERE `helpmenu_id` = ".Controller('ServerConHelpMenu')->id." ORDER BY `kolejnosc` ASC");?>
              <?php foreach ($lista_komend_q as $key):?>
                <tr>
                  <form method='post' action='<?= "?x=$x&xx=$xx&edycja=$acp_r_d->id"; ?>'>
                    <input type="hidden" name="id" value="<?= $key->id ?>">
                    <input type="hidden" name="helpmenu_id" value="<?= $key->helpmenu_id ?>">
                    <input type="hidden" name="serwer_id" value="<?= $key->serwer_id ?>">

                    <td><input type="number" class="form-control" value="<?= $key->kolejnosc ?>" disabled></td>
                    <td><input type="text" class="form-control" name="komenda" value="<?= $key->komenda ?>" ></td>
                    <td><input type="text" class="form-control" name="tekst" value="<?= $key->tekst ?>" ></td>
                    <td>
                      <div class="btn-group">
                        <input name='komenda_form_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                        <input name='komenda_kolejonosc_up' type="submit" class="btn btn-default" value='UP'>
                        <input name='komenda_kolejonosc_down' type="submit" class="btn btn-default" value='DOWN'>
                        <input name='komenda_usun' type="submit" class="btn btn-danger" value='Usuń'>
                      </div>
                    </td>
                  </form>
                </tr>
              <?php endforeach; ?>
              <tr>
                <form method='post' action='<?= "?x=$x&xx=$xx&edycja=$acp_r_d->id"; ?>'>
                  <input type="hidden" name="id" value="<?= $key->id ?>">
                  <input type="hidden" name="helpmenu_id" value="<?= $acp_r_d->id ?>">
                  <input type="hidden" name="serwer_id" value="<?= $acp_r_d->serwer_id ?>">

                  <td><input type="number" class="form-control" disabled></td>
                  <td><input type="text" class="form-control" name="komenda"></td>
                  <td><input type="text" class="form-control" name="tekst"></td>
                  <td>
                    <input name='komenda_form_add' type="submit" class="btn btn-default" value='Dodaj'>
                  </td>
                </form>
              </tr>
            </tbody>
          </table>
        </div>
        <? endif; ?>

      </div>
    </div>
  </div>
</div>
<? endif; ?>

	<div class="row">
		<div class="col-lg-8">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Help Menu</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Lista Serwerów</th>
								<th>Lista Adminów</th>
								<th>Opis Vipa</th>
								<th>Lista Komend</th>
								<th>Statytyki</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
  					foreach(Controller('ServerConHelpMenu')->index() as $serwer){
              $serwer->serwer_nazwa = ($serwer->serwer_id==0) ? 'Wszystkie' : Model('Server')->mod($serwer->serwer_id);
						?>
            <tr class="odd gradeX">
              <td><?= $serwer->id ?></td>
							<td><?= $serwer->serwer_nazwa ?></td>
							<td><?= $wl_wyl_array[$serwer->lista_serwerow] ?></td>
							<td><?= $wl_wyl_array[$serwer->lista_adminow] ?></td>
							<td><?= $wl_wyl_array[$serwer->opis_vipa] ?> <a href="<?= "?x=$x&xx=$xx&co=edycja&id=$serwer->id&konfiguruj=opis_vipa" ?>"><span class="label label-primary">Konfiguruj</span></a></td>
							<td><?= $wl_wyl_array[$serwer->lista_komend] ?> <a href="<?= "?x=$x&xx=$xx&co=edycja&id=$serwer->id&konfiguruj=lista_komend" ?>"><span class="label label-primary">Konfiguruj</span></a></td>
							<td><?= $wl_wyl_array[$serwer->statystyki] ?></td>
              <td>
                <div class="btn-group">
                  <a href="<?= "?x=$x&xx=$xx&co=edycja&id=$serwer->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                  <a href="<?= "?x=$x&xx=$xx&co=usun&id=$serwer->id" ?>" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
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
            <h4 class="modal-title">Utwórz <b>Help Menu</b> dla serwera</h4>
          </div>
          <div class="modal-body">
            <form method='post' action='<?= "?x=$x&xx=$xx"; ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <?= optionHtml($serwer_array, ['name' => 'serwer' , 'value' => '']); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Lista Serwerów</span>
                  <?= optionHtml($wl_wyl_array, ['name' => 'lista_serwerow' , 'value' => '']); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Lista Adminów</span>
                  <?= optionHtml($wl_wyl_array, ['name' => 'lista_adminow' , 'value' => '']); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Opis Vipa</span>
                  <?= optionHtml($wl_wyl_array, ['name' => 'opis_vipa' , 'value' => '']); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Lista Komend</span>
                  <?= optionHtml($wl_wyl_array, ['name' => 'lista_komend' , 'value' => '']); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Statystyki</span>
                  <?= optionHtml($wl_wyl_array, ['name' => 'statystyki' , 'value' => '']); ?>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" class="btn btn-primary" name="nowy_rekord">Dodaj</button>
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
