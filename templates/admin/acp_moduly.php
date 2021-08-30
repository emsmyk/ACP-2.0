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
$id = Get::int('id');

if(isset($_POST['dodaj'])) {
  Controller('Ustawienia')->acp_moduly_dodaj();
  redirect("?x=$x");
}
if(isset($_POST['edycja_from'])) {
  Controller('Ustawienia')->acp_moduly_edytuj_modul();
  redirect("?x=$x&id=".$id."&co=edytuj");
}
if(isset($_POST['edycja_from_uprawnienia_add'])){
  Controller('Ustawienia')->edycja_from_uprawnienia_add();
  redirect("?x=$x&id=".$id."&co=edytuj");
}
if(isset($_POST['edycja_from_uprawnienia_zapisz'])){
  Controller('Ustawienia')->edycja_from_uprawnienia_zapisz();
  redirect("?x=$x&id=".$id."&co=edytuj");
}
if(isset($_POST['edycja_from_uprawnienia_usun'])){
  Controller('Ustawienia')->edycja_from_uprawnienia_usun();
  redirect("?x=$x&id=".$id."&co=edytuj");
}

if(isset($_POST['edycja_from_menu_add'])){
  Controller('Ustawienia')->edycja_from_menu_add();
  redirect("?x=$x&id=".$id."&co=edytuj");
}
if(isset($_POST['edycja_from_menu_zapisz'])){
  Controller('Ustawienia')->edycja_from_menu_zapisz();
  redirect("?x=$x&id=".$id."&co=edytuj");
}
if(isset($_POST['edycja_from_menu_usun'])){
  Controller('Ustawienia')->edycja_from_menu_usun();
  redirect("?x=$x&id=".$id."&co=edytuj");
}

if(Get::string('co') == 'usun'){
  Controller('Ustawienia')->acp_moduly_usun(Get::int('id'));
  redirect("?x=$x");
}
?>

<?
if(Get::string('co') == 'edytuj' && !empty(Get::int('id'))) {
  $edycja_mod = SQL::row("SELECT * FROM `acp_moduly` WHERE `id` = $id LIMIT 1;");
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Modułu ID: <?= Get::int('id') ?></h3>
        <div class="pull-right box-tools">
        </div>
      </div>
      <div class="box-body">
        <form name='edycja_from' method='post' action='<?= "?x=$x&id=$id&co=edytuj"; ?>'>
          <input type='hidden' name='e_id' value='<?= $edycja_mod->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa (PHP)</span><input class='form-control' type='text' name='e_nazwa' value='<?= $edycja_mod->nazwa ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Wyświetlana</span><input class='form-control' type='text' name='e_nazwa_wys' value='<?= $edycja_mod->nazwa_wys ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Ikona</span><input class='form-control' type='text' name='e_ikona' value='<?= $edycja_mod->ikona ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Menu</span>
            <?=
            optionHtml(
              ['0' => 'NIE (Brak pozycji w menu)','1' => 'Tak (Pojedyńczy link)',  '2' => 'Tak (Rozwiana lista)'],
              ['name' => 'e_menu' , 'value' => $edycja_mod->menu]
            );
            ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kategoria Menu</span>
            <?= optionHtml(['1' => 'Serwery Gier', '0' => 'Podstawowe', '2' => 'Administracja'], ['name' => 'e_menu_kategoria' , 'value' => $edycja_mod->menu_kategoria]); ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Opis</span><input class='form-control' type='text' name='e_opis' value='<?= $edycja_mod->opis ?>'/></div></p>
          <p><input name='edycja_from' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
        </form>

        <hr>
          <h4>Menu:</h4>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                <th width="5%">ID</th>
                <th>Ikona</th>
                <th>Nazwa</th>
                <th>Link</th>
                <th></th>
              </tr>
              <?
              $mod_menu_q = SQL::all("SELECT * FROM  `acp_moduly_menu` WHERE `modul_id` = $id; ");
              foreach ($mod_menu_q as $mod_menu) { ?>
              <tr>
              <form name='edycja_from_menu' method='post' action='<?= "?x=$x&id=$id&co=edytuj"; ?>'>
                <input type="hidden" name="e_n_id" value="<?= $mod_menu->id ?>">
                <input type="hidden" name="e_n_idmodulu" value="<?= $id ?>">
                <input type="hidden" name="e_n_nazamodulu" value="<?= $edycja_mod->nazwa_wys ?>">
                <td><input type="text" class="form-control" type="text" value="<?= $mod_menu->id ?>" disabled></td>
                <td><input type="text" class="form-control" type="text" name="e_n_ikona" value="<?= $mod_menu->ikona ?>" ></td>
                <td><input type="text" class="form-control" type="text" name="e_n_nazwa" value="<?= $mod_menu->nazwa ?>" ></td>
                <td><input type="text" class="form-control" type="text" name="e_n_link" value="<?= $mod_menu->link ?>" ></td>
                <td>
                  <input name='edycja_from_menu_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                  <input name='edycja_from_menu_usun' type="submit" class="btn btn-danger" value='Usuń'>
                </td>
              </form>
              </tr>
              <? } ?>
              <tr>
              <form name='edycja_from_menu_add' method='post' action='<?= "?x=$x&id=$id&co=edytuj"; ?>'>
                <td><input type="text" class="form-control" value="-" disabled></td>
                <input type="hidden" name="e_new_idmodulu" value="<?= $id ?>">
                <input type="hidden" name="e_new_nazamodulu" value="<?= $edycja_mod->nazwa_wys ?>">
                <td><input type="text" type="text" name="e_new_ikona" class="form-control"></td>
                <td><input type="text" type="text" name="e_new_nazwa" class="form-control"></td>
                <td><input type="text" type="text" name="e_new_link" class="form-control"></td>
                <td>
                  <input name='edycja_from_menu_add' type="submit" class="btn btn-default" value='Dodaj'>
                </td>
              </form>
              </tr>
            </table>
          </div>
          <hr>
        <hr>
          <h4>Uprawnienia:</h4>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                <th width="5%">ID</th>
                <th>Akcja (PHP)</th>
                <th>Akcja Nazwa</th>
                <th>Opis</th>
                <th></th>
              </tr>
              <?
              $akcje_q = SQL::all("SELECT * FROM  `acp_moduly_akcje` WHERE `modul_id` = $id; ");
              foreach ($akcje_q as $akcje) {
                if(empty($akcje->opis)) { $akcje->opis = 'brak opisu'; } ?>
              <tr>
              <form name='edycja_from_uprawnienia' method='post' action='<?= "?x=$x&id=$id&co=edytuj"; ?>'>
                <input type="hidden" name="e_n_id" value="<?= $akcje->id ?>">
                <input type="hidden" name="e_n_idmodulu" value="<?= $id ?>">
                <input type="hidden" name="e_n_nazamodulu" value="<?= $edycja_mod->nazwa_wys ?>">
                <td><input type="text" class="form-control" type="text" value="<?= $akcje->id ?>" disabled></td>
                <td><input type="text" class="form-control" type="text" name="e_n_akcja" value="<?= $akcje->akcja ?>" ></td>
                <td><input type="text" class="form-control" type="text" name="e_n_akcja_wys" value="<?= $akcje->akcja_wys ?>" ></td>
                <td><input type="text" class="form-control" type="text" name="e_n_opis" value="<?= $akcje->opis ?>" ></td>
                <td>
                  <input name='edycja_from_uprawnienia_zapisz' type="submit" class="btn btn-default" value='Zapisz'>
                  <input name='edycja_from_uprawnienia_usun' type="submit" class="btn btn-danger" value='Usuń'>
                </td>
              </form>
              </tr>
              <? } ?>
              <tr>
              <form name='edycja_from_uprawnienia_add' method='post' action='<?= "?x=$x&id=$id&co=edytuj"; ?>'>
                <td><input type="text" class="form-control" value="-" disabled></td>
                <input type="hidden" name="e_new_idmodulu" value="<?= $id ?>">
                <input type="hidden" name="e_new_nazamodulu" value="<?= $edycja_mod->nazwa_wys ?>">
                <td><input type="text" type="text" name="e_new_akcja" class="form-control"></td>
                <td><input type="text" type="text" name="e_new_akcja_wys" class="form-control"></td>
                <td><input type="text" type="text" name="e_new_opis" class="form-control"></td>
                <td>
                  <input name='edycja_from_uprawnienia_add' type="submit" class="btn btn-default" value='Dodaj'>
                </td>
              </form>
              </tr>
            </table>
          </div>
          <hr>
      </div>
    </div>
  </div>
</div>

<?
}
?>

	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Moduły</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nazwa</th>
                <th>Nazwa Wyświetlana</th>
                <th>Opis</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?
              $moduly_q = SQL::all("SELECT * FROM  `acp_moduly`  ORDER BY `id` +0 ASC;");
              foreach($moduly_q as $moduly){
              ?>
              <tr class="odd gradeX">
                <td><?= $moduly->id; ?></td>
                <td><?= $moduly->nazwa; ?></td>
                <td><?= $moduly->nazwa_wys; ?></td>
                <td><?= $moduly->opis; ?></td>
                <td>
                  <a href="<?= "?x=$x&id=$moduly->id&co=edytuj" ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                  <a href="<?= "?x=$x&id=$moduly->id&co=usun" ?>"><button type="button" onclick="return confirm('Jesteś Pewny?')" class="btn btn-danger"><i class="fa fa-times"></i></button></a>
                </td>
              </tr>
              <? } ?>
            </tbody>
          </table>
				</div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#dodaj"><i class="fa fa-plus"></i> Dodaj</button>
        </div>
			</div>
		</div>
	</div>

  <div class="row">
    <!-- okno wyskakujace -->
    <div class="modal fade" id="dodaj">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj Moduł</h4>
          </div>
          <div class="modal-body">
            <form name='dodaj' method='post' action='?x=<?= $x ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa (PHP)</span>
                  <input class="form-control" name="n_nazwa">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nazwa Wyświetlana</span>
                  <input class="form-control" name="n_nazwa_wys">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Opis</span>
                  <input class="form-control" name="n_opis">
                </div>
              </p>
              <p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="dodaj" class="btn btn-primary">Zapisz</button>
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
  ['name' => '#example'],
  ['name' => '#example2']
]); ?>
</body>
</html>
