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
$co =  Get::string('co');
if(isset($_POST['nowy_rekord'])){
  Controller('ServerConHextags')->store($dostep->SerwerRangiDodaj);
  redirect("?x=$x&xx=$xx");
}
if(isset($_POST['edycja_from'])){
  Controller('ServerConHextags')->update($dostep->SerwerRangiEdytuj);
  redirect("?x=$x&xx=$xx");
}
if($co == "usun"){
  Controller('ServerConHextags')->destroy($dostep->SerwerRangiUsun);
  redirect("?x=$x&xx=$xx");
}

if(!empty(Controller('ServerConHextags')->id) && $co == "kolejonosc_up"){
  Model('ServerKonfiguracja')->sortKolejnosc(
    [
      'kierunek' => 'up',
      'id' => Controller('ServerConHextags')->id,
      'ColumnSort' => 'istotnosc',
      'table' => 'acp_serwery_hextags',
      'ColumnId' => 'id'
    ],
    $dostep->SerwerRangiEdytuj
  );
  redirect("?x=$x&xx=$xx");
}
if(!empty(Controller('ServerConHextags')->id) && $co == "kolejonosc_down"){
  Model('ServerKonfiguracja')->sortKolejnosc(
    [
      'kierunek' => 'down',
      'id' => Controller('ServerConHextags')->id,
      'ColumnSort' => 'istotnosc',
      'table' => 'acp_serwery_hextags',
      'ColumnId' => 'id'
    ],
    $dostep->SerwerRangiEdytuj
  );
  redirect("?x=$x&xx=$xx");
}
if(Get::int('wymus_aktualizacje') == 1){
  Model('ServerKonfiguracja')->UpdateNow($dostep->SerwerWymusAktualizacje);
  redirect("?x=$x&xx=$xx");
}

$serwer_array = $servers->servers_list();

?>
<?
if(Controller('ServerConHextags')->id){
  $rangEdit = Controller('ServerConHextags')->edit();
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Edycja </br>
          <small>Ranga: <?= $rangEdit->TagName ?> [ID: <?= $rangEdit->id ?>]</small>
        </h3>
        <div class="pull-right box-tools">
        </div>
      </div>
      <div class="box-body">
        <form name='edycja_from' method='post' action='<?= "?x=$x&xx=$xx&edycja=$rangEdit->id"; ?>'>
          <input type='hidden' name='id' value='<?= $rangEdit->id ?>'>
          <p><div class='form-group input-group'><span class='input-group-addon'>Serwer</span>
            <?= optionHtml($serwer_array, ['name' => 'e_serwerid' , 'value' => $rangEdit->serwer_id, 'disable' => 1 ]); ?>
          </div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Typ</span><input class='form-control' type='text' name='e_hextags' value='<?= $rangEdit->hextags ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Nazwa Tagu</span><input class='form-control' type='text' name='e_TagName' value='<?= $rangEdit->TagName ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Tag Tabela</span><input class='form-control' type='text' name='e_ScoreTag' value='<?= $rangEdit->ScoreTag ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Tag Say</span><input class='form-control' type='text' name='e_ChatTag' value='<?= $rangEdit->ChatTag ?>'/></div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Tagu Say</span>
            <?= optionHtml(Controller('ServerConHextags')->colors, ['name' => 'e_TagColor' , 'value' => $rangEdit->TagColor, 'disable' => 1 ]); ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Say</span>
            <?= optionHtml(Controller('ServerConHextags')->colors, ['name' => 'e_ChatColor' , 'value' => $rangEdit->ChatColor, 'disable' => 1 ]); ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Kolor Nicku Say</span>
            <?= optionHtml(Controller('ServerConHextags')->colors, ['name' => 'e_NameColor' , 'value' => $rangEdit->NameColor, 'disable' => 1 ]); ?>
          </div></p>
          <p><div class='form-group input-group'><span class='input-group-addon'>Force</span>
            <?= optionHtml(Controller('ServerConHextags')->YesNo, ['name' => 'e_Force' , 'value' => $rangEdit->Force, 'disable' => 1 ]); ?>
          </div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Komentarz</span><input class='form-control' type='text' name='e_komentarz' value='<?= $rangEdit->komentarz ?>'/></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Czasowa</span><input class='form-control' type='text' name='e_komentarz' value='<?= Controller('ServerConHextags')->type[$rangEdit->czasowa] ?>' disabled /></div></p>
					<p><div class='form-group input-group'><span class='input-group-addon'>Czasowa Koniec</span><input class='form-control' type='text' name='e_komentarz' value='<?= $rangEdit->czasowa_end ?>' disabled /></div></p>

          <p><input name='edycja_from' class='btn btn-primary btn btn-block' type='submit' value='Edytuj Rangę'/></p>
        </form>
      </div>
    </div>
  </div>
</div>
<?
}
?>
	<div class="row">
		<div class="col-lg-9">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">HexTags</h3>
				</div>
				<div class="box-body">
          <table data-page-length='10' id="example" class="table table-bordered table-striped" width="100%">
						<thead>
							<tr>
                <th>ID</th>
								<th>Serwer</th>
								<th>Typ</th>
								<th>Tag Tabela</th>
								<th>Tag Say</th>
								<th>Kolor Tag Say</th>
								<th>Kolor Say</th>
								<th>Kolor Nick</th>
								<th>Force</th>
								<th>Istotność</th>
								<th>Komentarz</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?
  					foreach(Controller('ServerConHextags')->index() as $rang){
              $rang->serwer_nazwa = ($rang->serwer_id==0) ? 'Wszystkie' : Model('Server')->mod($rang->serwer_id);
					  ?>
            <tr class="odd gradeX">
              <td><?= $rang->id ?></td>
              <td><?= $rang->serwer_nazwa ?></td>
              <td><?= $rang->hextags ?> <br><i><small>Ranga: <?= Controller('ServerConHextags')->type[$rang->czasowa] ?></i><small></td>
              <td><?= $rang->ScoreTag ?></td>
              <td><?= $rang->ChatTag ?></td>
              <td><?= Controller('ServerConHextags')->colors[$rang->TagColor] ?></td>
              <td><?= Controller('ServerConHextags')->colors[$rang->ChatColor] ?></td>
              <td><?= Controller('ServerConHextags')->colors[$rang->NameColor] ?></td>
              <td><?= Controller('ServerConHextags')->YesNo[$rang->Force] ?></td>
              <td><?= $rang->istotnosc ?></td>
              <td><?= $rang->komentarz ?></td>
              <td width="100%">
                <div class="btn-group">
                  <a href="<?= "?x=$x&xx=$xx&co=kolejonosc_up&id=$rang->id" ?>" class="btn btn-default" role="button" aria-pressed="true"><i class="fa fa-angle-double-up"></i></a>
                  <a href="<?= "?x=$x&xx=$xx&co=kolejonosc_down&id=$rang->id" ?>" class="btn btn-default" role="button" aria-pressed="true"><i class="fa fa-angle-double-down"></i></a>
                  <a href="<?= "?x=$x&xx=$xx&co=edycja&id=$rang->id" ?>" class="btn btn-primary" role="button" aria-pressed="true"><i class="fa fa-edit"></i> Edytuj</a>
                  <a href="<?= "?x=$x&xx=$xx&co=usun&id=$rang->id" ?>" onclick="return confirm('Jesteś Pewny?')" class="btn btn-danger" role="button" aria-pressed="true"><i class="fa fa-times"></i> Usuń</a>
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
    <div class="col-lg-3 col-xs-12">
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
    <div class="col-lg-3 col-xs-12">
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
    <div class="col-lg-3 col-xs-12">
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
                  <?= optionHtml($serwer_array, ['name' => 'n_serwer' , 'value' => '' ]); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Typ</span>
                  <input class="form-control" name="n_typ">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Tag Tabela</span>
                  <input class="form-control" name="n_tag_tabela">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Tag Say</span>
                  <input class="form-control" name="n_tag_say">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Kolor Tagu Say</span>
                  <?= optionHtml(Controller('ServerConHextags')->colors, ['name' => 'n_kolor_tag_tag' , 'value' => '' ]); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Kolor Say</span>
                  <?= optionHtml(Controller('ServerConHextags')->colors, ['name' => 'n_kolor_tag' , 'value' => '' ]); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Kolor Nicku Say</span>
                  <?= optionHtml(Controller('ServerConHextags')->colors, ['name' => 'n_kolor_nick' , 'value' => '' ]); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Force</span>
                  <?= optionHtml(Controller('ServerConHextags')->YesNo, ['name' => 'n_force' , 'value' => '' ]); ?>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Komentarz</span>
                  <input class="form-control" name="n_komentarz">
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
  ['name' => '#example', 'sort' => '9','sort_type' => 'desc']
]); ?>
</body>
</html>
