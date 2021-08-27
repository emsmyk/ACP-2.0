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
	</section >
  </div>
<?
$id = Get::int('id');
$co = Get::string('co');
$edycja_id = Get::int('edycja');
$wymus_aktualizacje = Get::int('wymus_aktualizacje');
$edycja_id_mapy = Get::int('edycja_mapy');

if(isset($_POST['edycja_from'])) {
  Controller('ServerConMapy')->updateMapsGrup($_POST['edycja_from'], $dostep->SerwerMapyGrupaEdytuj);
  redirect("?x=$x&xx=$xx");
}
if($co == 'usun'){
  Controller('ServerConMapy')->destroyMapsGrup($id, $dostep->SerwerMapyGrupaUsun);
  redirect("?x=$x&xx=$xx");
}
if(isset($_POST['mapy_dodaj'])) {
  Controller('ServerConMapy')->storeMap($_POST['mapy_dodaj'], $dostep->SerwerMapaDodaj);
  redirect("?x=$x&xx=$xx&edycja=$edycja_id");
}
if(isset($_POST['mapy_usun'])) {
  Controller('ServerConMapy')->destroyMap($_POST['mapy_usun'], $dostep->SerwerMapaUsun);
  redirect("?x=$x&xx=$xx&edycja=$edycja_id");
}
if(isset($_POST['edycja_from_mapa'])) {
  Controller('ServerConMapy')->editMap($_POST['edycja_from_mapa'], $dostep->SerwerMapaEdytuj);
  redirect("?x=$x&xx=$xx&edycja_mapy=$edycja_id_mapy");
}
if(isset($_POST['edycja_from_mapa_grafiki'])) {
  Controller('GaleriaMap')->store($dostep->SerwerMapaGaleria);
  redirect("?x=$x&xx=$xx&edycja_mapy=$edycja_id_mapy");
}
if(isset($_POST['mapy_zapisz'])) {
  Controller('ServerConMapy')->updateMap($_POST['mapy_zapisz'], $dostep->SerwerMapyZapisz);
  redirect("?x=$x&xx=$xx&edycja=$edycja_id");
}
if(isset($_POST['nowy_rekord'])) {
  Controller('ServerConMapy')->storeMapsGrup($_POST['nowy_rekord'], $dostep->SerwerMapyGrupaDodaj);
  redirect("?x=$x&xx=$xx");
}
if(Get::int('wymus_aktualizacje') == 1){
  Model('ServerKonfiguracja')->UpdateNow($dostep->SerwerWymusAktualizacje);
  redirect("?x=$x&xx=$xx");
}

$serwer_array = $servers->servers_list();

?>
<?
if(!empty($edycja_id)){
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Edycja Grupy</h3>
      </div>
      <div class="box-body">
        <form name='edycja_from' method='post' action='<?= "?x=$x&xx=$xx&edycja=$edycja_id"; ?>'>
          <? $acp_r_d = SQL::row("SELECT * FROM `acp_serwery_mapy` WHERE `id` = $edycja_id LIMIT 1;"); ?>
          <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
            <?= optionHtml($serwer_array, ['name' => 'e_serwerid' , 'value' => $acp_r_d->serwer_id]); ?>
          </div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Group Name</span><input class='form-control' type='text' name='e_nazwa' value='<?= $acp_r_d->nazwa ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Nazwa</span><input class='form-control' type='text' name='e_display_template' value='<?= $acp_r_d->display_template ?>'/></div></p>
          <hr>
          <p><div class='form-group input-group'><span class='input-group-addon'>maps_invote</span><input class='form-control' type='number' name='e_maps_invote' value='<?= $acp_r_d->maps_invote ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>group_weight</span><input class='form-control' type='number' name='e_group_weight' value='<?= $acp_r_d->group_weight ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>next_mapgroup</span><input class='form-control' type='text' name='e_next_mapgroup' value='<?= $acp_r_d->next_mapgroup ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>default_min_players</span><input class='form-control' type='number' name='e_default_min_players' value='<?= $acp_r_d->default_min_players ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>default_max_players</span><input class='form-control' type='number' name='e_default_max_players' value='<?= $acp_r_d->default_max_players ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>default_min_time</span><input class='form-control' type='number' name='e_default_min_time' value='<?= $acp_r_d->default_min_time ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>default_max_time</span><input class='form-control' type='number' name='e_default_max_time' value='<?= $acp_r_d->default_max_time ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>default_allow_every</span><input class='form-control' type='number' name='e_default_allow_every' value='<?= $acp_r_d->default_allow_every ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>nominate_flags	</span><input class='form-control' type='text' name='e_nominate_flags' value='<?= $acp_r_d->nominate_flags ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>adminmenu_flag</span><input class='form-control' type='text' name='e_adminmenu_flag' value='<?= $acp_r_d->adminmenu_flag ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Komenty RCON</span><textarea name="e_command" class="form-control" rows="4"><?= $acp_r_d->command ?></textarea></div></p>


          <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
        </form>
        <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Lista Map Grupy</h3>
        </div>
        <div class="box-body">
          <table class="table table-hover">
            <tr>
              <th width="5%">ID</th>
              <th>Mapa</th>
              <th>Nazwa</th>
              <th></th>
            </tr>
            <?
            $lista_map_q = SQL::all("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $edycja_id; ");
            foreach ($lista_map_q as $lista_map) { ?>
            <tr>
            <form name='edycja_from_menu' method='post' action='<?= "?x=$x&xx=$xx&edycja=$acp_r_d->id"; ?>'>
              <input type="hidden" name="id" value="<?= $lista_map->id ?>">
              <td><input type="text" class="form-control" type="text" value="<?= $lista_map->id ?>" disabled></td>
              <td><input type="text" class="form-control" type="text" name="mapy_nazwa" value="<?= $lista_map->nazwa ?>" ></td>
              <td><input type="text" class="form-control" type="text" name="mapy_display" value="<?= $lista_map->display ?>" ></td>
              <td>
                <input name='mapy_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                <a href="<?= "?x=$x&xx=$xx&edycja_mapy=$lista_map->id" ?>"><button type="button" class="btn btn-default">Detale</button></a>
                <input name='mapy_usun' type="submit" class="btn btn-danger" value='Usuń'>
              </td>
            </form>
            </tr>
            <? } ?>
            <tr>
            <form name='edycja_from_menu_add' method='post' action='<?= "?x=$x&xx=$xx&edycja=$acp_r_d->id"; ?>'>
              <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
              <input type='hidden' name='nazwa_grupy' value='<?= $acp_r_d->nazwa ?>'>
              <td><input type="text" class="form-control" value="-" disabled></td>
              <td><input type="text" type="text" name="mapy_nazwa" class="form-control"></td>
              <td><input type="text" type="text" name="mapy_display" class="form-control"></td>
              <td>
                <input name='mapy_dodaj' type="submit" class="btn btn-default" value='Dodaj'>
              </td>
            </form>
            </tr>
          </table>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
<?
}
?>
<?
if(!empty($edycja_id_mapy)){
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Detale Ustawień Mapy</h3>
      </div>
      <div class="box-body">
        <form name='edycja_from_mapa' method='post' action='<?= "?x=$x&xx=$xx&edycja_mapy=$edycja_id_mapy"; ?>'>
          <? $acp_r_d = SQL::row("SELECT * FROM `acp_serwery_mapy_det` WHERE `id` = $edycja_id_mapy LIMIT 1;"); ?>
          <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Mapa</span><input class='form-control' type='text' name='e_nazwa' value='<?= $acp_r_d->nazwa ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa</span><input class='form-control' type='text' name='e_display' value='<?= $acp_r_d->display ?>'/></div></p>
          <hr>
          <p><div class='form-group input-group'><span class='input-group-addon'>weight</span><input class='form-control' type='number' name='e_weight' value='<?= $acp_r_d->weight ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>next_mapgroup</span><input class='form-control' type='text' name='e_next_mapgroup' value='<?= $acp_r_d->next_mapgroup ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>min_players</span><input class='form-control' type='number' name='e_min_players' value='<?= $acp_r_d->min_players ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>max_players</span><input class='form-control' type='number' name='e_max_players' value='<?= $acp_r_d->max_players ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>min_time</span><input class='form-control' type='number' name='e_min_time' value='<?= $acp_r_d->min_time ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>max_time</span><input class='form-control' type='number' name='e_max_time' value='<?= $acp_r_d->max_time ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>allow_every</span><input class='form-control' type='number' name='e_allow_every' value='<?= $acp_r_d->allow_every ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>nominate_flags</span><input class='form-control' type='text' name='e_nominate_flags' value='<?= $acp_r_d->nominate_flags ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>adminmenu_flag</span><input class='form-control' type='text' name='e_adminmenu_flag' value='<?= $acp_r_d->adminmenu_flag ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Komendy RCON</span><textarea name="e_command" class="form-control" rows="4"><?= $acp_r_d->command ?></textarea></div></p>


          <p><input name='edycja_from_mapa' class='btn btn-primary btn btn-block' type='submit' value='Edytuj'/></p>
        </form>
        </hr>
        <form action="<?= "?x=$x&xx=$xx&edycja_mapy=$edycja_id_mapy"; ?>" enctype="multipart/form-data" method="POST">
          <? $acp_r_d_graf = SQL::row("SELECT * FROM `acp_serwery_mapy_img` WHERE `id_mapy` = $edycja_id_mapy LIMIT 1;"); ?>
          <p><b><? if(!empty($acp_r_d_graf->imgur_url)){ echo 'Dla tej mapy istenieje obraz mapy.'; } ?></b></p>
          <input type='hidden' name='id' value='<?= $acp_r_d->id ?>'>
          <input type='hidden' name='mapa' value='<?= $acp_r_d->nazwa ?>'>
          <p>
            <div class="input-group">
              <span class="input-group-btn">
                <span class="btn btn-default btn-file">
                  Wybierz Plik
                  <input name="img" size="35" accept="image/jpeg" name="img" type="file" id="image">
                </span>
              </span>
              <input readonly="readonly" placeholder="<?= $acp_r_d_graf->imgur_url ?>" class="form-control" name="img" size="35" type="text"/>
            </div>
          </p>
          <p><input name='edycja_from_mapa_grafiki' class='btn btn-primary btn btn-block' type='submit' value='Edytuj Grafiki'/></p>
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
				  <h3 class="box-title">Grupy Map/Mapy</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Group Name</th>
								<th>Nazwa</th>
								<th>Liczba Map</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
            $acp_wyswietl_all_q = SQL::all("SELECT *, `serwer_id` AS `serwer_id_table`, (SELECT `mod` FROM `acp_serwery` WHERE `serwer_id` = `serwer_id_table`) AS serwer_nazwa FROM `acp_serwery_mapy`");
  					foreach($acp_wyswietl_all_q as $acp_r){
              $acp_r->liczba_map = SQL::one("SELECT COUNT(`id`) FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $acp_r->id; ");
              $acp_r->serwer_nazwa = ($acp_r->serwer_id_table==0) ? 'Wszystkie' : $acp_r->serwer_nazwa ;
              $acp_r->serwer_nazwa = (empty($acp_r->serwer_nazwa)) ? '<i>Serwer nie istnieje</i>' : $acp_r->serwer_nazwa;
						?>
            <tr class="odd gradeX">
              <td><?= $acp_r->id ?></td>
							<td><?= $acp_r->serwer_nazwa ?></td>
							<td><?= $acp_r->nazwa ?></td>
							<td><?= $acp_r->display_template ?></td>
							<td><?= $acp_r->liczba_map ?></td>
              <td>
                <div class="btn-group">
                  <a href="<?= "?x=$x&xx=$xx&edycja=$acp_r->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                  <a href="<?= "?x=$x&xx=$xx&co=usun&id=$acp_r->id" ?>" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
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
          <?= Model('ServerKonfiguracja')->UpdateLast(); ?>
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
          <?= Model('ServerKonfiguracja')->UpdateNext(); ?>
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
            <h4 class="modal-title">Dodaj</h4>
          </div>
          <div class="modal-body">
            <form name='now_rekord' method='post' action='<?= "?x=$x&xx=$xx"; ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <?= optionHtml($serwer_array, ['name' => 'n_serwer' , 'value' => '']); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Group Name</span>
                  <input class="form-control" name="n_nazwa">
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
