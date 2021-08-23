<?
Controller('CronjobsDeleteLogs')->deleteNoExist([
  'acp_serwery_bledy', 'acp_serwery_logs_day', 'acp_serwery_logs_hour', 'acp_serwery_logs_month', 'acp_serwery_logs', 'acp_serwery_hlstats', 'acp_serwery_hlstats_top', 'acp_serwery_gosetti'
]);


// kasowanie starych logow z detali serwerow
Controller('ServerStatsController')->deleteOld($acp_system['cron_optym_log_serwerow_limit'], $acp_system['cron_optym_log_serwerow_day']);

// kasowanie wygasłych usług, rang, raklam
Controller('Uslugi')->destroyOld($acp_system['cron_optym_stare_reklamy_limit'], $acp_system['cron_optym_stare_reklamy_hour']);
Controller('ServerConReklamy')->destroyOld($acp_system['cron_optym_stare_reklamy_limit'], $acp_system['cron_optym_stare_reklamy_hour']);
Controller('ServerConHextags')->destroyOld($acp_system['cron_optym_stare_rangi_limit'], $acp_system['cron_optym_stare_rangi_hour']);

Controller('Mess')->destroyOld($acp_system['cron_optym_stare_wiadomosc_limit'], $acp_system['cron_optym_stare_wiadomosci_day']);

//
// Powiadomienia
//
echo Powiadomienie::deleteOld($acp_system['cron_optym_powiadomienia_usun']);

//
// Cronjobs optymalizator
//

$servers->destroyOldLogs(30, 1);

//
// Optymalizacja baz danych hlstats oraz sourcebans
//
if(strtotime($acp_system['hlx_optymalize_last'])< (time() - $acp_system['hlx_optymalize_time']) && $acp_system['hlx_optymalize_time'] != '0'){
  Model('Hlstats')->optymalize_all_tables();
  Model('Cronjobs')->UpdateTime('hlx_optymalize_last');
}
if(strtotime($acp_system['sb_optymalize_last'])< (time() - $acp_system['sb_optymalize_time']) && $acp_system['sb_optymalize_time'] != '0'){
  Model('Sourcebans')->optymalize_all_tables();
  Model('Cronjobs')->UpdateTime('sb_optymalize_last');
}

?>
