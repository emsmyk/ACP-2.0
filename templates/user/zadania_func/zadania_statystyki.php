<?
tytul_strony("Zadania: Statystyki");
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
	<div class="row">
		<div class="col-xs-12">
      <div class="box box">
        <div class="box-body">
            <p class="text-center">
              <strong>Realizowanych zadań według Userów z dostępem do realizacji</strong>
            </p>
            <?
            $TaskStats = SQL::all("SELECT `id`, `nazwa` FROM `acp_users_grupy` WHERE `dostep` LIKE '%\"ZadaniePrzyjmnij\":\"1\"%' ");
            foreach ($TaskStats as $value) {
              $users = SQL::all("SELECT `user`, `login`, `steam`, `steam_avatar`, `steam_login`, `grupa` FROM `acp_users` WHERE `grupa` = '$value->id' ");
              foreach ($users as $user) {
                $user->zrealizowane = SQL::one("SELECT COUNT(`id`) FROM `acp_zadania` WHERE `technik_id` = $user->user AND `status` = 3");
                $user->w_realizacji = SQL::one("SELECT COUNT(`id`) FROM `acp_zadania` WHERE `technik_id` = $user->user AND `status` < 3 AND `status` != -2");
                $user->zadan = $user->zrealizowane + $user->w_realizacji;
                $user->zadan_prc = ($user->zadan == 0) ? 0 : round( $user->zrealizowane*100 / $user->zadan , 2);
                $user->kolor = ($user->zadan_prc > 80) ? 'green' : 'red';
                $user->grupa_nazwa = SQL::one("SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = $user->grupa LIMIT 1");
            ?>
              <div class="progress-group">
                <span class="progress-text"><?= $user->steam_login ?> <i>(<?= $user->login ?>)</i> - <b><?= $user->grupa_nazwa ?></b></span>
                <span class="progress-number"><b><?= $user->zrealizowane ?></b>/<?= $user->zadan ?> (<?= $user->zadan_prc ?> %)</span>

                <div class="progress sm">
                  <div class="progress-bar progress-bar-<?= $user->kolor ?>" style="width: <?= $user->zadan_prc ?>%"></div>
                </div>
              </div>
            <?
              }
            }
            ?>


            <p class="text-center">
              <strong>Ilość zadań według status</strong>
            </p>
            <?
            $tasksStatus = SQL::all("SELECT `status`, COUNT(`id`) AS `ilosc`, (SELECT `nazwa` FROM `acp_zadania_status` WHERE `id` = `status`) AS `nazwa` FROM `acp_zadania`GROUP BY `status` ORDER BY `status` DESC");
            $tasksCount = SQL::one("SELECT COUNT(`id`) FROM `acp_zadania`");
            foreach ($tasksStatus as $task):
            ?>
              <div class="progress-group">
                <span class="progress-text"><?= $task->nazwa ?></span>
                <span class="progress-number"><b><?= $task->ilosc ?></b>/<?= $tasksCount ?> </span>

                <div class="progress sm">
                  <div class="progress-bar progress-bar-yellow" style="width: <?= round($task->ilosc*100/$tasksCount,2) ?>%"></div>
                </div>
              </div>
            <? endforeach; ?>
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
  ['sort_type' => 'desc']
]); ?>
</body>
</html>
