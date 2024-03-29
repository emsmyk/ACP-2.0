<?
tytul_strony("RoundSound: Ustawienia");

//ustawienia rs
$rsSystem1 = [];
$rsSystem2 = [];

$rsUstawieniaQ = SQL::all("SELECT * FROM `rs_ustawienia`");
foreach($rsUstawieniaQ as $rsUstawienia){
	array_push($rsSystem1, "$rsUstawienia->conf_name");
	array_push($rsSystem2, "$rsUstawienia->conf_value");
}

$rsSystem = array_combine($rsSystem1, $rsSystem2);

$rsSystem['rs_serwery'] = json_decode($rsSystem['rs_serwery']);

?>
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
$dane_rs = new stdClass();
$dane_rs->aktualny = SQL::one("SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = '".$rsSystem['rs_roundsound']."' LIMIT 1");
$dane_rs->aktualny = (empty($dane_rs->aktualny)) ? 'Brak': $dane_rs->aktualny;
$dane_rs->przygotowaniu = SQL::one("SELECT `nazwa` FROM `rs_roundsound` WHERE `id` = '".$rsSystem['rs_roundsound_c']."' LIMIT 1");
$dane_rs->przygotowaniu = (empty($dane_rs->przygotowaniu)) ? 'Brak' : $dane_rs->przygotowaniu;

if(isset($_POST['edytuj'])) {
  Model('Roundsound')->zmien_wartosc($_POST['rs_on'], "rs_on", $dostep->RsUstPodstawowe);
  Model('Roundsound')->zmien_wartosc($_POST['rs_vote'], "rs_vote", $dostep->RsUstPodstawowe);
  Model('Roundsound')->zmien_wartosc($_POST['rs_vote_time'], "rs_vote_time", $dostep->RsUstPodstawowe);
  Model('Roundsound')->zmien_wartosc($_POST['rs_katalog'], "rs_katalog", $dostep->RsUstPodstawowe);
	redirect("?x=$x&xx=$xx");
}
if(!empty(Get::int('id')) && !empty(Get::string('on_off'))){
	Model('Roundsound')->ustawienia_OnOff($rsSystem['rs_serwery'], Get::int('id'), Get::string('on_off'), $dostep->RsUstSerwery);
	redirect("?x=$x&xx=$xx");
}

?>

	<div class="row">
		<div class="col-xs-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Podstawowe<br><small>Ustawienia podstawowe modulu roundsound</small></h3>
        </div>
        <div class="box-body">
          <form method='post' action="">
						<p><div class='form-group input-group'><span class='input-group-addon'>Włączony</span>
							<?= optionHtml([0 => 'Nie', 1 => 'Tak'], ['name' => 'rs_on' , 'value' => (int)$rsSystem['rs_on'], 'disable' => 1 ]); ?>
            </div></p>
           <p><div class='form-group input-group'><span class='input-group-addon'>Aktualnie Grana</span><input class='form-control' type='text' name='rs_roundsound' value='<?= $dane_rs->aktualny; ?>' disabled /></div></p>
           <p><div class='form-group input-group'><span class='input-group-addon'>W przygotwaniu</span><input class='form-control' type='text' name='rs_roundsound_c' value='<?= $dane_rs->przygotowaniu; ?>' disabled /></div></p>
           <p><div class='form-group input-group'><span class='input-group-addon'>Główny Katalog</span><input class='form-control' type='text' name='rs_katalog' value='<?= $rsSystem['rs_katalog']; ?>'/></div></p>
           <p><input name='edytuj' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
          </from>
        </div>
      </div>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Głosowania<br><small>Ograniczenia glosowania</small></h3>
        </div>
        <div class="box-body">
          <form method='post' action="<?= "?x=$x&xx=$xx" ?>">
						<p><div class='form-group input-group'><span class='input-group-addon'>Głosowanie</span>
							<?= optionHtml(
								[0 => 'Wiele piosenek na '.$rsSystem['rs_vote_time'].' minut', 1 => 'Tylko jedna piosenka na '.$rsSystem['rs_vote_time'].' minut'],
								['name' => 'rs_vote' , 'value' => (int)$rsSystem['rs_vote'], 'disable' => 1 ]
							); ?>
	          </div></p>
           <p><div class='form-group input-group'><span class='input-group-addon'>Ograniczenie czasowe</span><input class='form-control' type='text' name='rs_vote_time' value='<?= $rsSystem['rs_vote_time']; ?>'/></div></p>

					 <p><input name='edytuj' class='btn btn-primary btn-sm btn-block' type='submit' value='Edytuj'/></p>
          </from>
        </div>
      </div>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Praca zdalna<br><small>Czas ostatniego wykonania</small></h3>
        </div>
        <div class="box-body">
          <form method='post' action="<?= "?x=$x&xx=$xx" ?>">
           <p><div class='form-group input-group'><span class='input-group-addon'>Plik konfiguracyjny</span><input class='form-control' type='text' name='' value='<?= $rsSystem['rs_cron']; ?>' disabled/></div></p>
           <p><div class='form-group input-group'><span class='input-group-addon'>Utwory</span><input class='form-control' type='text' name='' value='<?= $rsSystem['rs_cron_utwory']; ?>' disabled/></div></p>
          </from>
        </div>
      </div>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Serwery<br><small>Lista serwerów na których grana jest muzyka</small></h3>
        </div>
        <div class="box-body">
          <ul class="todo-list">
            <?
            $serweryQ = SQL::all("SELECT `serwer_id`, `istotnosc`, `mod`, `nazwa`, `cronjobs` FROM `acp_serwery` ORDER BY `acp_serwery`.`istotnosc` ASC");
            foreach ($serweryQ as $row) {
                $row->kolor = (in_array($row->serwer_id, $rsSystem['rs_serwery'])) ? 'success' : 'danger';
                $row->text1 = (in_array($row->serwer_id, $rsSystem['rs_serwery'])) ? '<i class="fa fa-check"></i> ON' : 'OFF';
                $row->text2 = (in_array($row->serwer_id, $rsSystem['rs_serwery'])) ? 'Wyłącz' : 'Włącz';
                $row->OnOff = (in_array($row->serwer_id, $rsSystem['rs_serwery'])) ? 'off' : 'on';
								if($row->cronjobs == 1):
            ?>
              <li class="list-group-item">
                <b> <?= $row->istotnosc ?></b> <?= $row->nazwa ?> [<?= $row->mod ?>]
                <small class="label label-<?= $row->kolor ?>"><?= $row->text1 ?></small>
                <div class="tools">
                  <a href="<?= "?x=$x&xx=$xx&id=$row->serwer_id&on_off=$row->OnOff" ?>"><?= $row->text2 ?></a>
                </div>
              </li>
            <? endif; } ?>
          </ul>
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
<!-- SZABLONY -->
<script src="./www/dist/js/demo.js"></script>
<!-- page script -->
</body>
</html>
