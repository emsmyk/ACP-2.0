<?
tytul_strony("Wpisy");

$limit =  Controller('Wpisy')->limitPerPage;
$limit_komentarzy = Controller('Wpisy')->limitComents;

if (!isset(Controller('Wpisy')->page)) {
 $limit1 = 0;
 $limit2 = $limit;
 $nr_kolejnej_strony = 2;
}
else {
 $limit1 = $limit * Controller('Wpisy')->page - $limit;
 $limit2 = $limit;
 $nr_kolejnej_strony = Controller('Wpisy')->page + 1;
}
?>
<div class="content-wrapper">
<section class="content">
  <div class="row">
	<section class="col-lg-12">
    <p><?= $Messe->show(); ?></p>
	</section>
  </div>

<?
if(isset($_POST['komentarz'])) {
  Controller('WpisyCom')->storeComment();
  redirect("?x=$x");
}
if(isset($_POST['nowy_wpis'])) {
  Controller('Wpisy')->store();
  redirect("?x=$x");
}
?>
  <div class="row">
	<div class="col-lg-12">
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Dodaj Wpis</h3>
			</div>
			<div class="box-body">
				<form method='post'>
					<div class='form-group input-group'><span class='input-group-addon'>Kategoria</span>
          <select class="form-control" name="nowy_kategoria">
					 <option value="0">Brak</option>
					 <? foreach(Model('Wpisy')->getCategory as $new_kategoria){ ?>
					 	<option value="<?= $new_kategoria->id ?>"><?= $new_kategoria->nazwa ?></option>
					 <? } ?>
					</select>
          </div>
					<p><div class='form-group input-group'><span class='input-group-addon'>Tytuł</span><input class='form-control' type='text' name='nowy_tytul'/></div></p>
					<p><textarea class="form-control" rows="3" name="nowy_tekst"></textarea></p>
					<p><input name='nowy_wpis' class='btn btn-primary btn btn-block' type='submit' value='Dodaj nowy wpis'/></p>
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
  	<?
  	foreach(Controller('Wpisy')->index([$limit1, $limit2])['wpisy'] as $wpis){
  	?>
	  <div class="box box-widget">
		<div class="box-header with-border">
		  <div class="user-block">
			<img class="img-circle" src="<?= User::Avatar($wpis->steam_avatar); ?>">
			<span class="username"><a href="?x=account&id=<?= $wpis->u_id; ?>"><?= User::LoginSteam($wpis->steam_login, $wpis->login); ?></a> - <span style="word-wrap:break-word;"><?= $wpis->tytul; ?></span></span>
			<span class="description"><a href="?x=wpisy&xx=category&nazwa=<?= $wpis->kategoria_nazwa; ?>&id=<?= $wpis->kategoria ?>"><?= $wpis->kategoria_nazwa; ?></a> - <?= Date::relative($wpis->data); ?></span>
		  </div>
		  <div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		  </div>
		</div>
		<div class="box-body">
		  <p style="word-wrap:break-word;"><?= $wpis->text; ?></p>
		  <a href="?x=wpisy&xx=wpis&wpis=<?= Text::clean($wpis->tytul); ?>&wpisid=<?= $wpis->id; ?>"><button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Czytaj całość</button></a>
		  <span class="pull-right text-muted"><?= $wpis->komentarzy ?> komentarzy - <?= $wpis->komentowalo ?> komentujacych</span>
		</div>
		<div class="box-footer box-comments">
			<?foreach($wpis->comments as $comment){ ?>
		  <div class="box-comment">
			<img class="img-circle img-sm" src="<?= User::Avatar($comment->koemntujacy_steam_avatar); ; ?>" alt="User Image">

			<div class="comment-text">
				  <span class="username">
					<a href="?x=account&id=<?= $comment->user_id; ?>"><?= User::LoginSteam($comment->koemntujacy_steam_login, $comment->koemntujacy_login);  ?></a>
					<span class="text-muted pull-right"><?=  Date::relative($comment->data); ?></span>
				  </span>
			  <?=  $comment->text; ?>
			</div>
		  </div>
  		<? } ?>
		</div>
		<? if($wpis->closed == 1) {?>
		<div class="box-footer">
		 <div class="input-group-btn">
		  <button type="button" class="btn btn-danger btn-block btn-xs">Zablokowany <?= $wpis->closed_data ?></button>
		 </div>
		</div>
		<? } else {?>
		<div class="box-footer">
		 <form method='post'>
		  <div class="input-group">
			<input type='hidden' name='komentarz_id' value='<?= $wpis->id; ?>'>
			<input type="text" class="form-control" name='komentarz_tekst' placeholder="Wpisz wiadomość...">
			<div class="input-group-btn">
			<input name='komentarz' class='btn btn-success' type='submit' value='Napisz'/>
			</div>
		  </div>
		 </form>
		</div>
		<? } ?>
	  </div>
	<? }
	if(is_null($wpis->id)) {?>
	 <div class="box box-widget">
		<div class="box-body">
		 <p> Brak wpisów, coś za daleko trafiłeś..</p>
		</div>
		<div class="box-footer text-center">
		  <a href="?x=wpisy">Powórt</a>
		</div>
	 </div>
	<?} else { 	?>
	 <div class="box box-widget">
		<div class="box-footer text-center">
		  <a href="?x=wpisy&str=<?= $nr_kolejnej_strony; ?>">Załaduj Kolejne</a>
		</div>
	 </div>
	<? } ?>
	</div>

	<div class="col-lg-4">
	  <div class="info-box">
  		<span class="info-box-icon bg-green"><i class="fa fa-keyboard-o"></i></span>
  		<div class="info-box-content">
  		  <span class="info-box-text">Wszystkich Wpisów</span>
  		  <span class="info-box-number"><?= Model('Wpisy')->COUNTWpisy ?></span>
  		</div>
	  </div>
	</div>
	<div class="col-lg-4">
	  <div class="info-box">
  		<span class="info-box-icon bg-aqua"><i class="fa fa-comment-o"></i></span>
  		<div class="info-box-content">
  		  <span class="info-box-text">Komentarzy</span>
  		  <span class="info-box-number"><?= Model('Wpisy')->COUNTComment ?></span>
  		</div>
	  </div>
	</div>
	<div class="col-lg-4">
	  <div class="box box-success">
  		<div class="box-header with-border">
  		  <h3 class="box-title">Dostępne Kategorie</h3>
  		  <div class="box-tools pull-right">
  			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
  		  </div>
  		</div>
  		<div class="box-body">
        <ul>
    		<? foreach(Model('Wpisy')->getCategory as $category){ ?>
    			<li><a href="?x=wpisy&xx=category&nazwa=<?= $category->nazwa; ?>&id=<?= $category->id; ?>"><?= $category->nazwa; ?></a></li>
    		<? } ?>
    		</ul>
      </div>
	  </div>
	</div>
<? if($acp_system['wpisy_last_login_on'] == 1) { ?>
	<div class="col-lg-4">
	  <div class="box box-danger">
		<div class="box-header with-border">
		  <h3 class="box-title">Ostatnio Zalogowani</h3>
		  <div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
			</button>
		  </div>
		</div>
		<div class="box-body no-padding">
		  <ul class="users-list clearfix">
			<?
      $limit_last_login = $acp_system['wpisy_last_login_liczba'];
			$wpisy_ostatnio_logowani_q = SQL::all("SELECT `login`, `last_login`, `user`, `steam_login`, `steam_avatar` FROM `acp_users` ORDER BY `last_login` DESC LIMIT $limit_last_login ");
			foreach($wpisy_ostatnio_logowani_q as $wpisy_ostatnio){
        $wpisy_ostatnio->last_login = (strtotime($wpisy_ostatnio->last_login) > (time() - 60)) ? 'Aktywny Teraz' : Date::relative($wpisy_ostatnio->last_login);;
			?>
			<li>
			  <img src="<?= User::Avatar($wpisy_ostatnio->steam_avatar) ?>">
			  <a class="users-list-name" href="?x=account&id=<?= $wpisy_ostatnio->user ?>"><?= User::printName($wpisy_ostatnio->user); ?></a>
			  <span class="users-list-date"><?= $wpisy_ostatnio->last_login; ?></span>
			</li>
			<? } ?>
		  </ul>
		</div>
	  </div>
	</div>
<? } ?>
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
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
