<?
if(empty($player)){
  die ('user nie zalogowany');
}

$xxx = Get::string('xxx');

switch ($xx) {
  case 'header':
    switch ($xxx) {
      case 'ilosc_powiadomien':
        $return = ($player->notyfi['notyfication_count'] == 0) ? '<i class="fa fa-bell-o"></i>' : '<i class="fa fa-bell-o"></i><span class="label label-warning">'.$player->notyfi['notyfication_count'].'</span><script>playSound();</script>';
        echo $return;
        break;
      case 'ilosc_zadan':
        $return = ($player->notyfi['task_count'] == 0) ? '<i class="fa fa-flag-o"></i>' : '<i class="fa fa-flag-o"></i><span class="label label-danger">'.$player->notyfi['task_count'].'</span>';
        echo $return;
        break;
      case 'ilosc_wiadomosc':
        $return = ($player->notyfi['message_count'] == 0) ? '<i class="fa fa-envelope-o"></i>' : '<i class="fa fa-envelope-o"></i><span class="label label-success">'.$player->notyfi['message_count'].'</span><script>playSound();</script>';
        echo $return;
        break;

      case 'powiadomienia':
        $query_q = SQL::all("SELECT `id`, `link`, `text`, `icon`, `data`, `read` FROM `acp_users_notification` WHERE `u_id` = $player->user AND `read` = 1 ORDER BY `id` DESC LIMIT 10;");
        if(empty($query_q)) {
          break;
        }
        foreach($query_q as $query){
          $query->icon_kolor = ($query->read==1) ? 'text-aqua' : '';
        ?>
        <li>
          <a href="<?= $query->link; ?>&powiadomienie_id=<?= $query->id; ?>">
            <i class="fa <?= $query->icon.' '.$query->icon_kolor ?>"></i> <?= $query->text ?>
          </a>
        </li>
        <? }
        break;
      case 'zadania':
        $query_q = SQL::all("SELECT `id`, `temat`, `procent_wykonania`, `kolor_wykonania` FROM `acp_zadania_users` LEFT JOIN (`acp_zadania`) ON `acp_zadania_users`.`id_zadania` = `acp_zadania`.`id` WHERE `u_id` = $player->user AND `status` IN (0, 1, 2) ORDER BY `id` DESC;");
        if(empty($query_q)) {
          break;
        }
        foreach($query_q as $query){
        ?>
        <li>
          <a href="?x=zadania&xx=zadanie&id=<?= $query->id; ?>">
            <h3>
              <?= Text::limit($query->temat, 50) ?>
              <small class="pull-right"><?= $query->procent_wykonania; ?>%</small>
            </h3>
            <div class="progress xs">
              <div class="progress-bar progress-bar-<?=  $query->kolor_wykonania; ?>" style="width: <?= $query->procent_wykonania; ?>%" role="progressbar"
                   aria-valuenow="<?= $query->procent_wykonania; ?>" aria-valuemin="0" aria-valuemax="100">
                <span class="sr-only"><?= $query->procent_wykonania; ?>% Complete</span>
              </div>
            </div>
          </a>
        </li>
        <? }
        break;
      case 'wiadomosci':
        $query_q = SQL::all("SELECT `m_id`, `m_type`, `login`, `m_date`, `m_status`, `m_text`, `steam_avatar` FROM `acp_messages` INNER JOIN `acp_users` ON `m_from` = `user` WHERE `m_to` = $player->user AND `m_type` = 1 ORDER BY `m_status` ASC, `m_id` DESC LIMIT 5;");
        if(empty($query_q)) {
          break;
        }
        foreach($query_q as $query){
        ?>
        <li>
          <a href="?x=wiadomosci&xx=czytaj&type=1&id=<?= $query->m_id; ?>&read=1">
            <div class="pull-left">
              <img src="<?= $query->steam_avatar ?>" class="img-circle" alt="User Image">
            </div>
            <h4>
              <?= $query->login; ?>
              <small><i class="fa fa-clock-o"></i> <?= Date::relative($query->m_date); ?></small>
            </h4>
            <p><?= Text::limit(strip_tags($query->m_text), 80); ?></p>
          </a>
        </li>
        <? }
        break;
    }
    break;

}
?>
