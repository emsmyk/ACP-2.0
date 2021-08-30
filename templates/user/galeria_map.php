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
if(!empty($id)):
  $galeria_map = SQL::row("SELECT `serwer_id` AS `srv_id`, `nazwa`, `mod`, (SELECT `id` FROM `acp_serwery_mapy` WHERE `serwer_id` = `srv_id` LIMIT 1) AS `id_grupy_map`, (SELECT COUNT(`id`) FROM `acp_serwery_mapy_det` WHERE `mapy_id` = `id_grupy_map`) AS `ilosc_map` FROM `acp_serwery` WHERE `serwer_on` = 1 AND `serwer_id` = $id");

  if(isset($_POST['wgraj_grafike_mapy'])) {
    Controller('GaleriaMap')->store($dostep->GaleriaMapWgraj);
    redirect("?x=$x&id=$id");
  }
?>
<div class="row">
  <div class="col-xs-12">
    <div class="box box">
      <div class="box-header">
        <h3 class="box-title">Galeria Map <br><small>Serwer: <b><?= $galeria_map->mod ?></b> <?= $galeria_map->nazwa ?></small></h3>
        <div class="pull-right box-tools">
        </div>
      </div>
      <div class="box-body">
        <?
        $map_list_grupa = SQL::one("SELECT `id` FROM `acp_serwery_mapy` WHERE `serwer_id` = $id LIMIT 1");
        $lista_map_q = SQL::all("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $map_list_grupa");
        foreach ($lista_map_q as $lista_map):
          $lista_map_img = SQL::row("SELECT * FROM `acp_serwery_mapy_img` WHERE `id_mapy` = $lista_map->id LIMIT 1");
          $lista_map->imgur_url_obrazek = (empty($lista_map_img->imgur_url)) ? $acp_system['galeria_map_noimage'] : $lista_map_img->imgur_url;
          $lista_map->imgur_url = (empty($lista_map_img->imgur_url)) ? '#' : $lista_map_img->imgur_url;
          $lista_map->imgur_url_color = (empty($lista_map_img->imgur_url_color)) ? 'default' : 'primary';

          $lista_map->display = (empty($lista_map->display)) ? 'brak danych': $lista_map->display;
          $lista_map->min_players = (empty($lista_map->min_players)) ? 'brak danych': $lista_map->min_players;
          $lista_map->max_players = (empty($lista_map->max_players)) ? 'brak danych': $lista_map->max_players;
        ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-3">
                <img src="<?= Controller('Imgur')->img($lista_map->imgur_url_obrazek, 4)?>" class="col-lg-12 img-thumbnail" alt="brak obrazka">
                <a href="<?= $lista_map->imgur_url ?>" target="_blank" ><button type="button" class="btn btn-<?= $lista_map->imgur_url_color ?> btn-block">Zobacz Obrazek</button></a>
              </div>
              <div class="col-lg-4">
                <ul class="list-group">
                  <li class="list-group-item"><b>Mapa:</b> <?= $lista_map->nazwa ?></li>
                  <li class="list-group-item"><b>Nazwa Wyświetlana:</b> <?= $lista_map->display ?></li>
                  <li class="list-group-item"><b>Minimalna liczba gracz:</b> <?= $lista_map->min_players ?></li>
                  <li class="list-group-item"><b>Maksymalna liczba gracz:</b> <?= $lista_map->max_players ?></li>
                  <a href="?x=serwery_konfiguracja&xx=mapy&edycja_mapy=<?= $lista_map->id ?>"><button type="button" class="btn btn-<?= $lista_map->imgur_url_color ?> btn-block">Edytuj Mapę</button></a>
                </ul>
              </div>
              <div class="col-lg-5">
                <form action="<?= "?x=$x&id=$id"; ?>" enctype="multipart/form-data" method="POST">
                  <input type='hidden' name='id' value='<?= $lista_map->id ?>'>
                  <input type='hidden' name='mapa' value='<?= $lista_map->nazwa ?>'>
                  <p>
                    <div class="input-group">
                      <span class="input-group-btn">
                        <span class="btn btn-default btn-file">
                          Wybierz Plik
                          <input name="img" size="35" accept="image/jpeg" name="img" type="file" id="image">
                        </span>
                      </span>
                      <input readonly="readonly" placeholder="<?= $lista_map->imgur_url ?>" class="form-control" name="img" size="35" type="text"/>
                    </div>
                  </p>
                  <p><input name='wgraj_grafike_mapy' class='btn btn-primary btn btn-block' type='submit' value='Wgraj'/></p>
                </form>
              </div>
            </div>
          </div>
          <hr>
        <? 
 endforeach; ?>
      </div>
    </div>
  </div>
</div>
<? else: ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box">
				<div class="box-header">
				  <h3 class="box-title">Wybierz Serwer</h3>
				  <div class="pull-right box-tools">
				  </div>
				</div>
				<div class="box-body">
          <ul class="list-group">
					<?
          foreach ($servers->servers as $server):
          ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <b><a href="<?= "?x=$x&id=$server->serwer_id" ?>"><?= $server->istotnosc ?></b> <?= $server->nazwa ?> <b>[<?= $server->mod ?>]</b></a>
            </li>
          <? endforeach; ?>
          </ul>
				</div>
			</div>
		</div>
	</div>
<? endif;?>

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
</body>
</html>
