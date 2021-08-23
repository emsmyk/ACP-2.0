<header class="main-header">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <a href="?x=default" class="navbar-brand"><b><?= $acp_system['acp_nazwa'] ?> | </b>ACP</a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>
      <? require_once("./templates/master/przybornik/menu-belka.php");  ?>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <? if($player->user > 0) {?>
            <li class="dropdown messages-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="ilosc_wiadomosc"><?= $player->notyfi['message_icon'] ?></a>
              <ul class="dropdown-menu">
                <li>
                  <ul class="menu" id="wiadomosci">

                  </ul>
                </li>
                <li class="footer"><a href="?x=wiadomosci&xx=skrzynka&type=1">Zobacz Wszystkie</a></li>
              </ul>
            </li>
            <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle powiadomienia" data-toggle="dropdown" id="ilosc_powiadomien"><?= $player->notyfi['notyfication_icon']  ?></a>
              <ul class="dropdown-menu">
                <li>
                  <ul class="menu" id="powiadomienia">

                  </ul>
                </li>
                <li class="footer"><a href="?x=powiadomienia">Zobacz Wszystkie</a></li>
              </ul>
            </li>
            <li class="dropdown tasks-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="ilosc_zadan"><?= $player->notyfi['task_icon'] ?></a>
              <ul class="dropdown-menu">
                <li>
                  <ul class="menu" id="zadania">

  				        </ul>
                </li>
              </ul>
            </li>
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= $player->steam_avatar ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?= $player->login ?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header">
                  <img src="<?= $player->steam_avatar ?>" class="img-circle" alt="User Image">

                  <p>
                    <?= $player->login ?>
                    <small>Użytkownik od <?= Date::relative($player->data_rejestracji) ?></small>
                  </p>
                </li>
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="?x=account&id=<?= $player->user; ?>" class="btn btn-default btn-flat">Profil</a>
                  </div>
                  <div class="pull-right">
                    <a href="?x=logout" class="btn btn-default btn-flat">Wyloguj</a>
                  </div>
                </li>
              </ul>
            </li>
            <? } else {?>
            <li><a href="?x=login">Logowanie</a></li>
            <li><a href="?x=register">Rejestracja</a></li>
            <? } ?>
          </ul>
      </div>
    </div>
  </nav>
</header>
