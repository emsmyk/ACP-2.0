<?
tytul_strony("Wpisy Kategoria: ".$_GET['nazwa']);

$category_id = Get::int('id');
$category_dane = SQL::row("SELECT * FROM `acp_wpisy_kategorie` WHERE `id` = $category_id");

$limit =  5; //Liczba wpisów na jednej stronie
$strona = Get::int('str');
//$strona = $_GET['str']; // Pobranie numeru strony

if (!isset($strona)) {
    $limit1 = 0;
    $limit2 = $limit;
} else {
    $limit1 = $limit * $strona - $limit;
    $limit2 = $limit;
}
?>
<div class="content-wrapper">
<section class="content">
  <div class="row">
  	<section class="col-lg-12">
      <p><?= $Messe->show(); ?></p>
  	</section>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-widget">
        <div class="box-header with-border">
          Kategoria: <?= $category_dane->nazwa; ?>
        </div>
      </div>
    </div>
  	<div class="col-md-12">
  	<?$acp_wpis_category_q = SQL::all("SELECT *,
  	(SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS steam_avatar,
  	(SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS login,
  	(SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS steam_login,

  	(SELECT `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` = `kategoria`) AS kategoria_nazwa
  	FROM `acp_wpisy` WHERE `kategoria` = $category_id ORDER BY `id` DESC LIMIT $limit1, $limit2;");
  	foreach($acp_wpis_category_q as $acp_wpis){
  		$komentarzy = SQL::one("SELECT COUNT(`id`) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $acp_wpis->id");
  		$komentowalo = SQL::one("SELECT COUNT(DISTINCT `user_id`) FROM `acp_wpisy_komentarze` WHERE `wpis_id` = $acp_wpis->id");

      $acp_wpis->kategoria_nazwa = (empty($acp_wpis->kategoria)) ? 'Brak kategorii' : $acp_wpis->kategoria_nazwa;
  	?>
  	  <div class="box box-widget">
  		<div class="box-header with-border">
  		  <div class="user-block">
  			<img class="img-circle" src="<?= User::Avatar($acp_wpis->steam_avatar); ?>" alt="User Image">
  			<span class="username"><?= User::printName($acp_wpis->u_id, true); ?> - <span style="word-wrap:break-word;"><?= $acp_wpis->tytul; ?></span></span>
  			<span class="description"><a href="?x=wpisy&xx=category&nazwa=<?= $acp_wpis->kategoria_nazwa; ?>&id=<?= $acp_wpis->kategoria ?>"><?= $acp_wpis->kategoria_nazwa; ?></a> - <?= Date::relative($acp_wpis->data); ?></span>
  		  </div>
  		  <div class="box-tools">
  			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
  			</button>
  			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
  		  </div>
  		</div>
  		<div class="box-body">
  		  <p style="word-wrap:break-word;"><?= str_replace(array("\r\n", "\n", "\r"), "<br>", strip_tags($acp_wpis->text)); ?></p>
  		  <a href="<?= "?x=$x&xx=wpis&wpis=".Text::clean($acp_wpis->tytul)."&wpisid=$acp_wpis->id" ?>"><button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Czytaj całość</button></a>

  		  <span class="pull-right text-muted"><?= $komentarzy ?> komentarzy - <?= $komentowalo ?> komentujacych</span>
  		</div>
  	  </div>
  	<? } ?>
  	</div>
  	<div class="col-md-12 text-right">
  	  <div class="btn-group " role="group" aria-label="First group">
  		<?
  		$ile = SQL::one("SELECT COUNT(*) FROM `acp_wpisy` WHERE `kategoria` = '".$category_id."';");

  		$liczba_stron = $ile / $limit;
  		$liczba_stron = ceil($liczba_stron);

  		if($liczba_stron != 0){
  			echo '<button type="button" class="btn btn-secondary">Strony:</button>';
  		}
  		for ($v = 1; $v <= $liczba_stron; $v++) {
  			if ($liczba_stron == 1) {
  				echo '<button type="button" class="btn btn-secondary">'.$v.'</button>';
  			} else {
  				echo '<a href="?x=wpisy-category&nazwa='.$_GET['nazwa'].'&id='.$category_id.'&str='.$v.'"><button type="button" class="btn btn-secondary">'.$v.'</button></a>';
  			}
  		}
  		?>
  	  </div>
  	</div>
  </div>
</div>


<? require_once("./templates/user/stopka.php");  ?>
<div class="control-sidebar-bg"></div>
</div>

<!-- jQuery 3 -->
<script src="./www/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./www/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
