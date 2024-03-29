<?
$TaskController = Controller('Task');
$TaskModel = Model('Task');
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
Powiadomienia::read(Get::int('powiadomienie_id'));

$id = Get::int('id');
$co = Get::string('co');

if(isset($_POST['edytuj'])) {
  $TaskController->update($id, $dostep->ZadanieEdytuj);
  redirect("?x=$x&xx=$xx&id=$id");
}

if($co == 'usun') {
  $TaskController->destroy($id, $dostep->ZadanieEdytuj);
  redirect("?x=$x&xx=lista");
}
if($co == 'akceptuj') {
  $TaskController->task_akcept($id, $dostep->ZadanieAkcOdrz);
  redirect("?x=$x&xx=$xx&id=$id");
}
if($co == 'odrzuc') {
  $TaskController->task_odrzuc($id, $dostep->ZadanieAkcOdrz);
  redirect("?x=$x&xx=$xx&id=$id");
}
if($co == 'przyjmnij') {
  $TaskController->task_przyjmnij($id, $dostep->ZadaniePrzyjmnij);
  redirect("?x=$x&xx=$xx&id=$id");
}
if($co == 'zakoncz') {
  $TaskController->task_zakoncz($id, $dostep->ZadanieZakoncz);
  redirect("?x=$x&xx=$xx&id=$id");
}
if($co == 'anuluj') {
  $TaskController->task_anuluj($id, $dostep->ZadanieAnuluj);
  redirect("?x=$x&xx=$xx&id=$id");
}

if(isset($_POST['komentarz_tekst'])) {
  $TaskController->comment($id, $dostep->ZadanieKomentarze);
  redirect("?x=$x&xx=$xx&id=$id");
}
if(isset($_POST['todo_dodaj'])) {
  $TaskController->todoStore($id, $dostep->ZadanieToDo);
  redirect("?x=$x&xx=$xx&id=$id");
}

if($co == 'todo_status') {
  $TaskController->todoStatus(Get::int('todoid'), $dostep->ZadanieToDo);
  redirect("?x=$x&xx=$xx&id=$id");
}
if($co == 'todo_usun') {
  $TaskController->todoDestroy(Get::int('todoid'), $dostep->ZadanieToDo);
  redirect("?x=$x&xx=$xx&id=$id");
}
if(isset($_POST['zapros'])) {
  $TaskController->zapros($id, $dostep->ZadanieZapros);
  redirect("?x=$x&xx=$xx&id=$id");
}
if(Get::string('public_link')){
  $TaskModel->public_link($id, $dostep->ZadanieLink);
  redirect("?x=$x&xx=$xx&id=$id");
}

$zadanie = $TaskController->indexTask($id)['task'];
if(empty($zadanie)){
  redirect("?x=zadania&xx=lista");
}

tytul_strony("Zadania: $zadanie->temat");

$zadanie->akceptujacy = (empty($zadanie->akceptujacy) || is_null($zadanie->akceptujacy)) ? '<i>Brak danych</i>' : $zadanie->akceptujacy ;
$zadanie->technik = (empty($zadanie->technik) || is_null($zadanie->technik)) ? '<i>Brak danych</i>' : $zadanie->technik ;
$zadanie->zlecajacy = (empty($zadanie->zlecajacy) || is_null($zadanie->zlecajacy)) ? '<i>Brak danych</i>' : $zadanie->zlecajacy ;
$zadanie->nazwa_serwera = (empty($zadanie->nazwa_serwera)) ?'<i>Brak Serwera</i>' : $zadanie->nazwa_serwera;
$acp_text_akcept = ($zadanie->status == '-1') ? 'Odrzucił: ' : 'Akceptował: ';


$zadanie->bar = $TaskModel->taskStatusPrc($zadanie->id);
?>

	<div class="row">
		<div class="col-lg-12">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <li class="pull-left header"><i class="fa fa-info-circle"></i> Zadanie ID #<?= $zadanie->id ?></li>
        </ul>
        <div class="tab-content">
          <?= $zadanie->temat ?>
        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <li class="pull-left header"><i class="fa fa-inbox"></i> Detale Zadania</li>
        </ul>
        <div class="tab-content">
          <h4>Temat:</h4><p><?= $zadanie->temat ?></p>
					<h4>Opis:</h4><p><?= str_replace(array("\r\n", "\n", "\r"), "<br>", $zadanie->opis) ?></p>
					<h4>Status:</h4><p><button type='button' class='btn btn-<?= $zadanie->status_kolor ?> btn-xs'><?= $zadanie->status_text ?></button></p>
					<h4>Serwer:</h4><p><?= $zadanie->nazwa_serwera ?></p>
          <? if(empty($zadanie->public_code)): ?>
          <h4>Link:</h4>
          <p>
            Brak.. Wejdź <a href='<?= "?x=$x&xx=$xx&id=$id&public_link=yes" ?>'>tutaj</a> aby go wygenerować
          </p>
          <?
            else:
              $actual_link = "https://$_SERVER[HTTP_HOST]/";
          ?>
          <h4>Link:</h4>
          <p>
            <div class="input-group">
              <input type="text" class="form-control" value="<?= $actual_link ?>?x=pub_zadanie&xx=<?= $zadanie->public_code ?>">
              <span class="input-group-btn">
                <a href="<?= $actual_link ?>?x=pub_zadanie&xx=<?= $zadanie->public_code ?>" target="_blank" class="btn btn-default"><i class="fa fa-arrow-right"></i></a>
            </div>
          </p>
          <h4>Iframe:</h4>
          <p>
            <div class="input-group">
              <input type="text" class="form-control" value='<iframe allowfullscreen="" frameborder="1" height="400px" marginheight="0px" marginwidth="0px" name="myiFrame" scrolling="yes" src="<?= $actual_link ?>?x=pub_zadanie&xx=<?= $zadanie->public_code ?>" style="border:2px #000000 solid;" width="600px"></iframe>' id="copy-input">
              <span class="input-group-btn">
                <button onclick="CopyInput()" class="btn btn-default" type="button"><i class="fa fa-copy"></i></button>
            </div>
          </p>
          <? endif; ?>

					<h4>Postęp:</h4>
          <div class="progress text-center">
            <div class="progress-bar progress-bar-<?= $zadanie->bar['kolor'] ?>" role="progressbar" aria-valuenow="<?= $zadanie->bar['prc'] ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $prc_wykonania ?>%;">
              <span><?= $zadanie->bar['prc'] ?>% Wykonano</span>
            </div>
              <span><?= 100-$zadanie->bar['prc'] ?>% Pozostało</span>
          </div>
        </div>
        <div class="box-footer clearfix no-border text-right">
          <div class="btn-group">
            <?
            switch ($zadanie->status) {
              case '0':
                echo'
                  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#akceptuj"><i class="fa fa-check"></i> Akceptuj</button>
                  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#odrzuc"><i class="fa fa-close"></i> Odrzuć</button>';
                break;
              case '1':
                echo '
                  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#przyjmnij"><i class="fa fa-circle-thin"></i> Przyjmnij zadanie</button>';
                break;
              case '2':
                echo '
                  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#zakoncz"><i class="fa fa-circle-thin"></i> Zakończ zadanie</button>
                  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#anuluj"><i class="fa fa-circle-thin"></i> Anuluj zadanie</button>';
                break;
            }
            ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edytuj"><i class="fa fa-edit"></i> Edytuj</button>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#usun"><i class="fa fa-trash"></i> Usuń</button>
          </div>
        </div>
      </div>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <li class="pull-left header"><i class="fa fa-comments-o"></i> Komentarze</li>
        </ul>
        <div class="box-body chat" id="chat-box">
          <?
          if(empty($TaskController->indexTask($id)['comments'])) { echo '<div class="item">Brak komentarzy..</div>'; }
          foreach($TaskController->indexTask($id)['comments'] as $komentarze) {
            if(strtotime($komentarze->last_login) > (time() - 120)) { $online = 'online'; } else { $online = 'offline'; }
          ?>
          <div class="item">
            <img src="<?= $komentarze->steam_avatar ?>" alt="user image" class="<?= $online ?>">

            <p class="message">
              <a href="?x=account&id=<?= $komentarze->u_id ?>" class="name">
                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?= Date::relative($komentarze->data) ?></small>
                <?= $komentarze->steam_login ?> (<?= $komentarze->nick ?>)
              </a>
              <?= $komentarze->text ?>
            </p>
          </div>
          <? } ?>
        </div>
        <div class="box-footer">
          <form name='komentarz' method='post' action='<?= "?x=$x&xx=$xx&id=$id" ?>'>
          <div class="input-group">
              <input type="hidden" name="id" value="<?= $zadanie->id ?>">
              <input class="form-control" placeholder="Wpisz komentarz..." type="tekst" name="komentarz_tekst">

              <div class="input-group-btn">
                <button type="input" name="komentarz" class="btn btn-success"><i class="fa fa-plus"></i></button>
              </div>
          </div>
          </form>
        </div>
      </div>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <li class="pull-left header"><i class="ion ion-clipboard"></i> To Do List</li>
        </ul>
        <div class="tab-content">
          <ul class="todo-list">
            <?
            foreach ($TaskController->indexTask($id)['todo'] as $todo) {
              $todo->icon = (1 == $todo->zrealizowano) ? '<i class="fa fa-thumbs-down"></i>' : '<i class="fa fa-thumbs-up"></i>';
              $todo->tekst_s = (1 == $todo->zrealizowano) ? "<s>$todo->tekst</s>" : $todo->tekst;
              $todo->czas_realizacji = (strtotime($todo->zrealizowano_data)-strtotime($todo->data));
              $todo->pozostalo = (empty($todo->pozostalo)) ? '~' : $todo->pozostalo;
              $todo->realizuj_czas = (1 == $todo->zrealizowano) ? "Realizowano:  ".Date::secund($todo->czas_realizacji) : "Czas planowany: $todo->pozostalo minut";
              $todo->realizuj_kolor = (1 == $todo->zrealizowano) ? "default" : "success";
            ?>
              <li>
                <span class="text">#<?= $todo->id  ?> <?= $todo->tekst_s ?></span>
                <small class='label label-<?= $todo->realizuj_kolor ?>'><i class='fa fa-clock-o'></i> <?= $todo->realizuj_czas ?></small>
                <div class="tools">
                  <a href="<?= "?x=$x&xx=$xx&id=$id&co=todo_status&todoid=$todo->id" ?>"><?= $todo->icon ?></a>
                  <a href="<?= "?x=$x&xx=$xx&id=$id&co=todo_usun&todoid=$todo->id" ?>"><i class="fa fa-trash-o"></i></a>
                </div>
              </li>
            <? } ?>
          </ul>
        </div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#todo_dodaj"><i class="fa fa-plus"></i> Dodaj</button>
        </div>
      </div>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <li class="pull-left header"><i class="fa fa-file-archive-o"></i> Logi</li>
        </ul>
        <div class="tab-content">
          <div class="logi_hight">
            <?
            foreach ($TaskController->indexTask($id)['logs'] as $logi): ?>
              <p><?= $logi->data ?> - <?= $logi->tekst ?> ~ <?= $logi->login ?><p>
            <? endforeach;?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <li class="pull-left header"><i class="fa fa-glass"></i> Administracja</li>
        </ul>
        <div class="box-body tab-content">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-black-tie"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Zlecił</span>
                <span class="info-box-number"><a href="?x=account&id=<?= $zadanie->zlecajacy_id ?>"><?= $zadanie->zlecajacy ?></a></span>
                <span class="info-box-text"><?= Date::relative($zadanie->data) ?></span>
              </div>
            </div>
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="fa fa-mouse-pointer"></i></span>
              <div class="info-box-content">
                <span class="info-box-text"><?= $acp_text_akcept ?></span>
                <span class="info-box-number"><a href="?x=account&id=<?= $zadanie->akceptujacy_id ?>"><?= $zadanie->akceptujacy ?></a></span>
                <span class="info-box-text"><?= Date::relative($zadanie->a_data) ?></span>
              </div>
            </div>
          </div>

          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-yellow"><i class="fa fa-odnoklassniki"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Zajmuje się:</span>
                <span class="info-box-number"><a href="?x=account&id=<?= $zadanie->technik_id ?>"><?= $zadanie->technik ?></a></span>
                <span class="info-box-text"><?= Date::relative($zadanie->t_data) ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
          <li class="pull-left header"><i class="fa fa-asterisk"></i> Wzieli Udział</li>
        </ul>
        <div class="box-body tab-content">
          <div class="box-body no-padding">
      		  <ul class="users-list clearfix">
              <?
              $wzieli_udzial_q = SQL::all("SELECT *,
                (SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS `nick`,
                (SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_login`,
                (SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_avatar`
                FROM `acp_zadania_users` WHERE `id_zadania` = $id");

              if(empty($wzieli_udzial_q)) { echo 'Brak uczestników..'; }
              foreach ($wzieli_udzial_q as $wzieli_udzial) {
              ?>
                <li>
                  <img src="<?= $wzieli_udzial->steam_avatar ?>">
                  <a class="users-list-name" href="?x=account&id=<?= $wzieli_udzial->u_id ?>"><?= $wzieli_udzial->steam_login ?></a>
                  <span class="users-list-date">Dodany: <?= Date::relative($wzieli_udzial->data); ?></span>
                </li>
              <?
              }
              ?>
      		  </ul>
      		</div>
        </div>
        <div class="box-footer clearfix no-border">
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#zapros"><i class="fa fa-plus"></i> Zaproś</button>
        </div>
      </div>
    </div>
	</div>




  <div class="row">
    <div class="modal fade" id="edytuj">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edycja Zadania</br><small><?= $zadanie->temat ?> (ID: <?= $zadanie->id ?>)</small></h4>
          </div>
          <div class="modal-body">
            <form name='edytuj' method='post' action='<?= "?x=$x&xx=$xx&id=$zadanie->id"; ?>'>
              <input type='hidden' name='id' value='<?= $zadanie->id ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Platforma</span>
                  <select class="form-control" name="platforma">
                      <option value="<?= $zadanie->platforma ?>"><?= $zadanie->nazwa_platforma ?></option>
                    <?
                    $platformy_q = SQL::all("SELECT * FROM `acp_zadania_platforma` WHERE `id` != $zadanie->platforma");
                    foreach($platformy_q as $platformy){
                    ?>
                      <option value="<?= $platformy->id ?>"><?= $platformy->nazwa ?></option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Serwer</span>
                  <select class="form-control" name="serwer">
                    <?
                    $serwer_array = array(0 => 'Wszystkie');
                    foreach($servers->servers as $serwer_array_dane){
                      $serwer_array[$serwer_array_dane->serwer_id]="$serwer_array_dane->nazwa";
                    }

                    echo '<option value="'.$acp_r_d->serwer_id.'">'.$serwer_array[$zadanie->serwer_id].'</option>';
                    foreach ($serwer_array as $key => $value):
                      if($zadanie->serwer_id != $key)
                      echo '<option value="'.$key.'">'.$value.'</option>';
                    endforeach;

                    ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Typ</span>
                  <select class="form-control" name="typ">
                      <option value="<?= $zadanie->typ ?>"><?= $zadanie->nazwa_typ ?></option>
                    <?
                    $typ_q = SQL::all("SELECT * FROM `acp_zadania_typ` WHERE `id` != $zadanie->typ ");
                    foreach($typ_q as $typ){
                    ?>
                      <option value="<?= $typ->id ?>"><?= $typ->nazwa ?></option>
                    <? } ?>
                  </select>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Temat</span>
                  <input class="form-control" name="temat" type="text" value="<?= $zadanie->temat ?>">
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Opis</span>
                  <textarea class="form-control" rows="5" name="opis"><?= $zadanie->opis ?></textarea>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="edytuj" class="btn btn-primary">Edytuj</button>

            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="todo_dodaj">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj pozycję To Do</h4>
          </div>
          <div class="modal-body">
            <form name='todo_dodaj' method='post' action='<?= "?x=$x&xx=$xx&id=$zadanie->id"; ?>'>
              <input type='hidden' name='id' value='<?= $zadanie->id ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Tekst</span>
                  <textarea class="form-control" rows="3" name="todo_tekst"></textarea>
                </div>
              </p>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Czas Realizacji</span>
                  <input type="number" class="form-control" name="todo_czasrealizacji">
                  <span class="input-group-addon">minut</span>
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="todo_dodaj" class="btn btn-primary">Dodaj</button>

            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="zapros">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Dodaj użytkownika do zadania</h4>
          </div>
          <div class="modal-body">
            <form name='zapros' method='post' action='<?= "?x=$x&xx=$xx&id=$zadanie->id"; ?>'>
              <input type='hidden' name='id' value='<?= $zadanie->id ?>'>
              <p>
                <div class='form-group input-group'>
                  <span class='input-group-addon'>Nick</span>
                  <input type="text" class="form-control" name="zapros_text">
                </div>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Zamknij</button>
              <button type="input" name="zapros" class="btn btn-primary">Dodaj</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="usun" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content modal-danger">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Usuń zadanie (ID: <?= $zadanie->id ?>)</h4>
          </div>
          <div class="modal-body">
            Czy jesteś pewny że chcesz usunąć zadanie <?= $zadanie->temat ?> (ID: <?= $zadanie->id ?>) ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Nie</button>
            <a class="btn btn-outline pull-right" class="button"  href="<?= "?x=$x&xx=$xx&id=$id&co=usun" ?>">Tak</a>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="akceptuj" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Akceptuj zadanie (ID: <?= $zadanie->id ?>)</h4>
          </div>
          <div class="modal-body">
            Czy jesteś pewny że chcesz zaakceptować zadanie <?= $zadanie->temat ?> (ID: <?= $zadanie->id ?>) ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Nie</button>
            <a class="btn btn-primary pull-right" class="button"  href="<?= "?x=$x&xx=$xx&id=$id&co=akceptuj" ?>">Tak</a>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="odrzuc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Odrzuć zadanie (ID: <?= $zadanie->id ?>)</h4>
          </div>
          <div class="modal-body">
            Czy jesteś pewny że chcesz odrzucić zadanie <?= $zadanie->temat ?> (ID: <?= $zadanie->id ?>) ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Nie</button>
            <a class="btn btn-primary pull-right" class="button"  href="<?= "?x=$x&xx=$xx&id=$id&co=odrzuc" ?>">Tak</a>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="przyjmnij" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content ">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Przyjmnij zadanie (ID: <?= $zadanie->id ?>)</h4>
          </div>
          <div class="modal-body">
            Czy jesteś pewny że chcesz przyjąć zadanie <?= $zadanie->temat ?> (ID: <?= $zadanie->id ?>) do realizacji?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Nie</button>
            <a class="btn btn-primary pull-right" class="button"  href="<?= "?x=$x&xx=$xx&id=$id&co=przyjmnij" ?>">Tak</a>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="zakoncz" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Zakończ zadanie (ID: <?= $zadanie->id ?>)</h4>
          </div>
          <div class="modal-body">
            Czy jesteś pewny że chcesz zakończyć zadanie <?= $zadanie->temat ?> (ID: <?= $zadanie->id ?>) ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Nie</button>
            <a class="btn btn-primary pull-right" class="button"  href="<?= "?x=$x&xx=$xx&id=$id&co=zakoncz" ?>">Tak</a>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="anuluj" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Anuluj zadanie (ID: <?= $zadanie->id ?>)</h4>
          </div>
          <div class="modal-body">
            Czy jesteś pewny że chcesz anulować zadanie <?= $zadanie->temat ?> (ID: <?= $zadanie->id ?>) ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Nie</button>
            <a class="btn btn-primary pull-right" class="button"  href="<?= "?x=$x&xx=$xx&id=$id&co=anuluj" ?>">Tak</a>
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
<script>
$(document).ready(function () {
  $('.chat').slimScroll({
    start: 'bottom',
  });
  $('.logi_hight').slimScroll({});
});
</script>
<script>
function CopyInput() {
  var copyText = document.getElementById("copy-input");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");
}
</script>
</body>
</html>
