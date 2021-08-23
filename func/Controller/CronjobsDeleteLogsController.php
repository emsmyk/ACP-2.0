<?php
class CronjobsDeleteLogsController
{
  function __construct()
  {
    $this->db = DB::getInstance();
  }

  function deleteNoExist($tables)
  {
    foreach ($tables as $table) {
      $row = S$this->db->get_results("SELECT `id`, `serwer_id` AS `serwer`,
        (SELECT `ip` FROM `acp_serwery` WHERE `serwer_id` = `serwer` LIMIT 1) AS `ip`
      FROM `$table`", true);

      foreach ($row as $value) {
        if(is_null($value->ip)){
          $this->db->delete($co, [
            'id' => $row->id
          ]);
        }
        if(!next($row)){
          $this->optymize($table);
        }
      }
    }
    return;
  }

  function optymize($table)
  {
    $this->db->query("OPTIMIZE TABLE `$table`");
  }
}
?>
