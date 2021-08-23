<?php
class TaskModel
{
  function __construct()
  {
    $this->platforms = SQL::all("SELECT * FROM `acp_zadania_platforma`");
    $this->typs = SQL::all("SELECT * FROM `acp_zadania_typ`");
    $this->status = SQL::all("SELECT `id`, `nazwa` FROM `acp_zadania_status` ORDER BY `id` DESC");
  }

  function public_link($TaskId, $dostep)
  {
    Permission::check($dostep);

    SQL::update(
      'acp_zadania',
      [
        'public_code' => generujLosowyCiag(50),
      ],
      $TaskId
    );

    $Task = SQL::one("SELECT `temat` FROM `acp_zadania` WHERE `id` = $TaskId LIMIT 1");

    Logs::log("Wygenerowano publiczny link dla zadania ".$Task." (ID: ".$TaskId.")", "?x=zadania&xx=zadanie&id=".$TaskId."");

  }

  function task($id)
  {
    return SQL::row("SELECT * FROM `acp_zadania` WHERE `id` = $id");
  }

  function taskStatusPrc($id)
  {
    $task = $this->task($id);

    if($task->status == 3)
    {
      $ile = 100;
      $kolor = 'green';
    }
    else {
      $ile = $this->procent($id);
      $kolor = $this->kolor($ile);
    }

    SQL::update(
      'acp_zadania',
      [
        'procent_wykonania' => $ile,
        'kolor_wykonania' => $kolor
      ],
      $id
    );

    return ['prc' => $ile, 'prc_kolor' => $kolor ];
  }

  public function procent($id)
  {
    $ile_zadań = SQL::one("SELECT COUNT(`id`) FROM `acp_zadania_todo` WHERE `zadanie_id` = ".$id."");
    if($ile_zadań == 0)
    {
      return 0;
    }

    $ile_zrealizowano = SQL::one("SELECT COUNT(`id`) FROM `acp_zadania_todo` WHERE `zadanie_id` = ".$id." AND `zrealizowano` = 1");

    return round($ile_zrealizowano*100/$ile_zadań);
  }

  function kolor($prc)
  {
		if($prc < 30){
			$kolor = 'red';
		}
		else if($prc >= 30 && $prc < 50){
			$kolor = 'aqua';
		}
		else if($prc >= 50 && $prc < 70){
			$kolor = 'yellow';
		}
		else if($prc >= 70){
			$kolor = 'green';
		}

		return $kolor;
	}

}
?>
