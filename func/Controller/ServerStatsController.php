<?php
class ServerStatsController
{
  function __construct()
  {
    $this->date = [
      'godzineTemu' => date('Y-m-d H', strtotime("-1 hour")),
      'ubieglyDzien' => date('Y-m-d', strtotime("-1 day")),
      'ubieglyMiesiac' => date('Y-m', strtotime("-1 month"))
    ];
  }

  function stats($sever_id, $where)
  {
    switch ($where) {
      case 'stats_hour':
        $dane = SQL::row("SELECT COUNT(*) AS liczba_danych, SUM(`graczy`) AS suma_graczy, SUM(`boty`) AS suma_boty, SUM(`sloty`) AS suma_sloty  FROM `acp_serwery_logs` WHERE `serwer_id` = $sever_id AND `data` LIKE '%".$this->date['godzineTemu']."%'");

        //insert do bazdy danych
        SQL::insert('acp_serwery_logs_hour',[
          'serwer_id' => $sever_id,
          'graczy' => round($dane->suma_graczy / $dane->liczba_danych),
          'boty' => round($dane->suma_boty / $dane->liczba_danych),
          'sloty' => round($dane->suma_sloty / $dane->liczba_danych),
          'suma_graczy' => $dane->suma_graczy,
          'suma_botow' => $dane->suma_boty,
          'suma_sloty' => $dane->suma_sloty,
          'data' => date("Y-m-d H:i:s"),
        ]);
        break;

      case 'stats_day':
        $dane = SQL::row("SELECT COUNT(*) AS liczba_danych, SUM(`graczy`) AS suma_graczy, SUM(`boty`) AS suma_boty, SUM(`sloty`) AS suma_sloty  FROM `acp_serwery_logs_hour` WHERE `serwer_id` = $sever_id AND `data` LIKE '%".$this->date['ubieglyDzien']."%'");

        SQL::insert('acp_serwery_logs_day',[
          'serwer_id' => $sever_id,
          'graczy' => round($dane->suma_graczy / $dane->liczba_danych),
          'boty' => round($dane->suma_boty / $dane->liczba_danych),
          'sloty' => round($dane->suma_sloty / $dane->liczba_danych),
          'suma_graczy' => $dane->suma_graczy,
          'suma_botow' => $dane->suma_boty,
          'suma_sloty' => $dane->suma_sloty,
          'data' => date("Y-m-d H:i:s"),
        ]);
        break;

      case 'stats_month':
        $dane = SQL::row("SELECT COUNT(*) AS liczba_danych, SUM(`graczy`) AS suma_graczy, SUM(`boty`) AS suma_boty, SUM(`sloty`) AS suma_sloty  FROM `acp_serwery_logs_day` WHERE `serwer_id` = $sever_id AND `data` LIKE '%".$this->date['ubieglyMiesiac']."%'");

        SQL::insert('acp_serwery_logs_month',[
          'serwer_id' => $sever_id,
          'graczy' => round($dane->suma_graczy / $dane->liczba_danych),
          'boty' => round($dane->suma_boty / $dane->liczba_danych),
          'sloty' => round($dane->suma_sloty / $dane->liczba_danych),
          'suma_graczy' => $dane->suma_graczy,
          'suma_botow' => $dane->suma_boty,
          'suma_sloty' => $dane->suma_sloty,
          'data' => date("Y-m-d H:i:s"),
        ]);
        break;
    }
  }

  function deleteOld($day, $limit)
  {
    return SQL::query("DELETE FROM `acp_serwery_logs` WHERE `data` < NOW() - INTERVAL $day DAY LIMIT $limit;");
  }
}

?>
