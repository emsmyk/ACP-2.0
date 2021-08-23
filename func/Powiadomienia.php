<?php
/**
 *
 */
class Powiadomienia
{
  function __construct()
  {

  }

  public static function new($users = [], $users_ignore=[], $link='#', $text='', $icon = 'fa-circle')
  {
    $users =  array_diff($users, $users_ignore);
    $users = array_unique($users);

  	foreach ($users as $i => $value) {
  		$user = (int)$users[$i];

      SQL::insert('acp_users_notification', [
        'u_id' => $user,
        'link' => $link,
        'text' => $text,
        'icon' => $icon,
      ]);
  	}
  }

  public static function read($id)
  {
    if(empty($id)){
      return;
    }

    $notification = SQL::row("SELECT * FROM `acp_users_notification` WHERE `id` = $id LIMIT 1");

    SQL::update('acp_users_notification',[
      'read' => '0'
    ],
    $id);

    Messe::array([
      'type' => 'info',
      'text' => "Powiadomienie <b>$notification->text</b> (ID: $id) zostało odczytane.."
    ]);

    return redirect($notification->link);
  }

  public static function deleteOld($day)
  {
    SQL::query("DELETE FROM `acp_users_notification` WHERE `read_date` < NOW() - INTERVAL $day DAY AND `read` = 0;");
    return "<p>Powiadomienia odczytane oraz starsze niż $day dni zostały skasowane</p>";
  }
}
?>
