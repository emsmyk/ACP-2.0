<header class="main-header">
    <a href="?x=wpisy" class="logo">
      <span class="logo-mini"><b>A</b>CP</span>
      <span class="logo-lg"><b><?= $acp_system['acp_nazwa'] ?></b> | ACP</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
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
            <a href="#" class="dropdown-toggle powiadomienia" data-toggle="dropdown" id="ilosc_powiadomien"><?= $player->notyfi['notyfication_icon'] ?></a>
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
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
