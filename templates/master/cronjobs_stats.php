<?
require "func/simpleHTML/simple_html_dom.php";

// Statystki raz na godzinę
foreach($servers->servers as $server){
  if(strtotime($acp_system['acp_cron_stats_date_hour']) < strtotime(date("Y-m-d H:i"))) {
    Controller('ServerStats')->stats($server->serwer_id, 'stats_hour');
    Model('Cronjobs')->UpdateTime('acp_cron_stats_date_hour');
  }
}
// Statystyki raz na dzień
foreach($servers->servers as $server){
  if(strtotime($acp_system['acp_cron_stats_date_day']) != strtotime(date("Y-m-d"))) {
    Controller('ServerStats')->stats($server->serwer_id, 'stats_day');
    Model('Cronjobs')->UpdateTime('acp_cron_stats_date_day', date("Y-m-d"));
  }
}
// Statystyki raz na miesiac
foreach($servers->servers as $server){
  if(strtotime($acp_system['acp_cron_stats_date_month']) < strtotime(date("Y-m"))) {
    Controller('ServerStats')->stats($server->serwer_id, 'stats_month');
    Model('Cronjobs')->UpdateTime('acp_cron_stats_date_month', date("Y-m-d"));
  }
}

//GoSetti
foreach($servers->servers as $server){
  if(strtotime($acp_system['acp_cron_stats_gosetti']) != strtotime(date("Y-m-d"))) {
    Controller('ServerGosetti')->stats($server->serwer_id);
    Model('Cronjobs')->UpdateTime('acp_cron_stats_gosetti', date("Y-m-d"));
  }
}

//hlstats dane graczy i top 50
foreach ($servers->servers as $server) {
  if(strtotime($acp_system['acp_cron_stats_hlstats']) != strtotime(date("Y-m-d"))) {
    Controller('ServerHlstats')->stats($server->serwer_id);
    Controller('ServerHlstatsTop50')->top50($server->serwer_id);
    Model('Cronjobs')->UpdateTime('acp_cron_stats_hlstats', date("Y-m-d"));
  }
}
?>
