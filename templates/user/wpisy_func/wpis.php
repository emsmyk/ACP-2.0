<?
$wpis_one = Text::clean($_GET['wpis']);
$wpis_id = Controller('Wpisy')->WpisId;

Powiadomienia::read(Get::int('powiadomienie_id'));
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
	</section>
  </div>
<?
if(isset($_POST['komentarz'])) {
  Controller('Wpisy')->storeComment();
  header("Location: ?x=$x&xx=$xx&wpis=$wpis_one&wpisid=$wpis_id");
}

if(isset($_POST['zmiana_kategori'])){
  Controller('Wpisy')->kategoria($wpis_id, $dostep->WpisyKategoria);
  header("Location: ?x=$x&xx=$xx&wpis=$wpis_one&wpisid=$wpis_id");
}
if(isset($_POST['edytuj_wpis'])){
  Controller('Wpisy')->update($wpis_id, $dostep->WpisyEdytujWpis);
  header("Location: ?x=$x&xx=$xx&wpis=$wpis_one&wpisid=$wpis_id");
}
if(isset($_GET['close_open'])){
  Controller('Wpisy')->close($_GET['close_open'], $dostep->WpisyZamknij);
  header("Location: ?x=$x");
}
if(isset($_GET['usun'])){
  Controller('Wpisy')->destroy($_GET['usun'], $dostep->WpisyUsun);
  header("Location: ?x=$x");
}
if(isset($_GET['ogloszenie'])){
  Controller('Wpisy')->ogloszenie($_GET['ogloszenie'], $dostep->WpisyOgloszenie);
  header("Location: ?x=$x");
}

?>
<div class="row">
  <div class="col-md-12">
  	<?
    $wpis = Controller('Wpisy')->edit();
    $wpis->closed = ($wpis->closed == 1) ? 'Otwórz' : 'Zamknij' ;
    tytul_strony("Wpis: $wpis->tytul");
  	?>
      <div class="box box-widget">
    	<div class="box-header with-border">
    	  <div class="user-block">
    		<img class="img-circle" src="<?= User::Avatar($wpis->steam_avatar);; ?>">
    		<span class="username"><a href="?x=account&id=<?= $wpis->u_id; ?>"><?= User::LoginSteam($wpis->steam_login, $wpis->login); ?></a> - <span style="word-wrap:break-word;"><?= $wpis->tytul; ?></span></span>
    		<span class="description"><a href="<?= "?x=$x&xx=category&nazwa=$wpis->kategoria_nazwa&id=$wpis->kategoria" ?>"><?= $wpis->kategoria_nazwa; ?></a> - <?= Date::relative($wpis->data); ?></span>
    	  </div>
    	</div>
    	<div class="box-body">
    	  <p style="word-wrap:break-word;"><?= $wpis->text; ?></p>
        <span class="pull-right">
          <? if(Permission::check($dostep->WpisyOgloszenie, false) == 1): ?>
            <a href="<?= "?x=$x&xx=$xx&ogloszenie=$wpis->id" ?>"><button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-bullhorn"></i> Ogłoszenie</button></a>
          <? endif; ?>

          <? if(Permission::check($dostep->WpisyKategoria, false) == 1): ?>
            <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#wpis_kategoria"><i class="fa fa fa-keyboard-o"></i> Kategoria</button>
          <? endif; ?>

          <? if(Permission::check($dostep->WpisyZamknij, false) == 1): ?>
            <a href="<?= "?x=$x&xx=$xx&close_open=$wpis->id" ?>"><button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i> <?= $wpis->closed ?></button></a>
          <? endif; ?>

          <? if(Permission::check($dostep->WpisyEdytujWpis, false) == 1): ?>
              <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#wpis_edytuj"><i class="fa fa-edit"></i> Edytuj</button>
          <? endif; ?>

          <? if(Permission::check($dostep->WpisyUsun, false) == 1): ?>
              <a href="<?= "?x=$x&xx=$xx&usun=$wpis->id" ?>"><button type="button" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-remove"></i> Usuń</button></a>
          <? endif; ?>
        </span>
    	</div>
    	<div class="box-footer box-comments">
    		<? foreach(Controller('Wpisy')->indexComments()['comments'] as $comment): ?>
  		  <div class="box-comment">
  			  <img class="img-circle img-sm" src="<?= User::Avatar($comment->koemntujacy_steam_avatar); ?>">
    			<div class="comment-text">
    				<span class="username">
      				<a href="?x=account&id=<?= $comment->user_id; ?>"><?= User::LoginSteam($comment->koemntujacy_steam_login, $comment->koemntujacy_login);  ?></a>
      				<span class="text-muted pull-right"><?= Date::relative($comment->data); ?></span>
    				</span>
    			  <p><?= $comment->text; ?></p>
    			</div>
  		  </div>
        <? endforeach; ?>
    	</div>
    	<? if($wpis->closed == 1):?>
    	<div class="box-footer">
    	 <div class="input-group-btn">
    	  <button type="button" class="btn btn-danger btn-block">Zablokowany</button>
    	 </div>
    	</div>
    	<? else: ?>
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
    	<? endif; ?>
    </div>
	</div>
</div>

<div class="row">
  <div class="modal fade" id="wpis_kategoria">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Zmień Kateogrię</h4>
        </div>
        <div class="modal-body">
          <form method='post'>
            <input type="hidden" name="id" value="<?=$wpis->id ?>">
            <p>
              <div class='form-group input-group'>
                <span class='input-group-addon'>Kategoria</span>
                <select class="form-control" name="kategoria">
                  <?
                  $kat_list = array();
                  $kat_list_q = SQL::all("SELECT `id`, `nazwa` FROM `acp_wpisy_kategorie`");
                  foreach($kat_list_q as $kat_list_s){
                    $kat_list[$kat_list_s->id]="$kat_list_s->nazwa";
                  }

                  echo '<option value="'.$wpis->kategoria.'">'.$kat_list[$wpis->kategoria].'</option>';
                  foreach ($kat_list as $key => $value):
                    if($wpis->kategoria != $key)
                    echo '<option value="'.$key.'">'.$value.'</option>';
                  endforeach;
                  ?>
                </select>
              </div>
            </p>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
            <button type="input" name="zmiana_kategori" class="btn btn-primary">Zmień</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="wpis_edytuj">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edytuj Wpis</h4>
        </div>
        <div class="modal-body">
          <form method='post'>
            <input type="hidden" name="id" value="<?=$wpis->id ?>">
            <p><div class="form-group input-group"><span class="input-group-addon">Tytuł</span><input class="form-control" type="text" name="tytul" value="<?=$wpis->tytul ?>"></div></p>
            <p><textarea class="form-control" rows="7" name="tekst"><?= $wpis->text ?></textarea></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
            <button type="input" name="edytuj_wpis" class="btn btn-primary">Zapisz</button>
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
<!-- SlimScroll -->
<script src="./www/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./www/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./www/dist/js/adminlte.min.js"></script>
