<?php
class Notifi
{
  public static function notifi($user)
  {
    $messageCount = SQL::one("SELECT COUNT(*) FROM `acp_messages` INNER JOIN `acp_users` ON `m_from` = `user` WHERE `m_to` = $user AND `m_type` = 1 AND `m_status` = 0;");
    $messageIcon = ($messageCount == 0) ? '<i class="fa fa-envelope-o"></i>' : '<i class="fa fa-envelope-o"></i><span class="label label-success">'.$messageCount.'</span>';

    $task = SQL::all("SELECT `id`, `temat`, `procent_wykonania`, `kolor_wykonania` FROM `acp_zadania_users` LEFT JOIN (`acp_zadania`) ON `acp_zadania_users`.`id_zadania` = `acp_zadania`.`id` WHERE `u_id` = $user AND `status` IN (0, 1, 2) ORDER BY `id` DESC; ");
    $taskIcon = (count((array)$task) == 0 || empty($task)) ? '<i class="fa fa-flag-o"></i>' : '<i class="fa fa-flag-o"></i><span class="label label-danger">'.count((array)$task).'</span>';

    $notyficationCount = SQL::one("SELECT COUNT(*) FROM `acp_users_notification` WHERE `read` = 1 AND `u_id` = $user LIMIT 11;");
    $notyficationIcon = ($notyficationCount == 0) ? '<i class="fa fa-bell-o"></i>' : '<i class="fa fa-bell-o"></i><span class="label label-warning">'.$notyficationCount.'</span>';

    return [
      'message_count' => $messageCount,
      'message_icon' => $messageIcon,

      'task' => $task,
      'task_count' => count((array)$task),
      'task_icon' => $taskIcon,

      'notyfication_count' => $notyficationCount,
      'notyfication_icon' => $notyficationIcon,
    ];
  }
}

?>
