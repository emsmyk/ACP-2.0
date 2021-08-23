<?php
class TaskController
{
  function __construct()
  {
    $this->user = User::get();
  }

  function indexMy()
  {
    $this->zgloszonych = SQL::one("SELECT COUNT(`id`) as `ile` FROM `acp_zadania` WHERE `zlecajacy_id` = $this->user");
    $this->zgloszonych = (empty($this->zgloszonych)) ?: 0;
    $this->zaakceptowanych = SQL::one("SELECT COUNT(`id`) as `ile` FROM `acp_zadania` WHERE `zlecajacy_id` = $this->user AND `status` != '-1' ");
    $this->zaakceptowanych = (empty($this->zaakceptowanych)) ?: 0;

    $this->odrzuconych = SQL::one("SELECT COUNT(`id`) as `ile` FROM `acp_zadania` WHERE `zlecajacy_id` = $this->user AND `status` = '-1' ");
    $this->odrzuconych = (empty($this->odrzuconych)) ?: 0;

    $this->zrealizowanych = SQL::one("SELECT COUNT(`id`) as `ile` FROM `acp_zadania` WHERE `zlecajacy_id` = $this->user AND `status` = '3' ");
    $this->zrealizowanych = (empty($this->zrealizowanych)) ?: 0;

    $this->procent = ($this->zgloszonych == 0) ? 0 : round($this->zrealizowanych*100/$this->zgloszonych, 2);

    $this->zrealizowanychAll = SQL::all("SELECT *, `serwer_id` AS `id_serwera`,
      (SELECT `nazwa` FROM `acp_zadania_platforma` WHERE `id` = `platforma` LIMIT 1) AS platforma,
      (SELECT `web` FROM `acp_zadania_platforma` WHERE `id` = `platforma` LIMIT 1) AS platforma_web,
      (SELECT `nazwa` FROM `acp_zadania_typ` WHERE `id` = `typ` LIMIT 1) AS typ,
      (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `id_serwera` LIMIT 1) AS nazwa_serwera,
      (SELECT `nazwa` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS status_nazwa,
      (SELECT `typ` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS kolor
    FROM `acp_zadania` WHERE `technik_id` = $this->user AND `status` IN ('2')  ORDER BY `id` DESC");

    $this->zleconychAll =  SQL::all("SELECT *, `serwer_id` AS `id_serwera`,
      (SELECT `nazwa` FROM `acp_zadania_platforma` WHERE `id` = `platforma` LIMIT 1) AS platforma,
      (SELECT `web` FROM `acp_zadania_platforma` WHERE `id` = `platforma` LIMIT 1) AS platforma_web,
      (SELECT `nazwa` FROM `acp_zadania_typ` WHERE `id` = `typ` LIMIT 1) AS typ,
      (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `id_serwera` LIMIT 1) AS nazwa_serwera,
      (SELECT `nazwa` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS status_nazwa,
      (SELECT `typ` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS kolor
    FROM `acp_zadania` WHERE `zlecajacy_id` = $this->user AND `status` IN ('0', '1', '2')  ORDER BY `id` DESC");

    return [
      'user' => [
        'zgloszonych' => $this->zgloszonych,
        'zaakceptowanych' => $this->zaakceptowanych,
        'odrzuconych' => $this->odrzuconych,
        'zrealizowanych' => $this->zrealizowanych,
        'procent' => $this->procent
      ],
      'zrealizowanychAll' => $this->zrealizowanychAll,
      'zleconychAll' => $this->zleconychAll
    ];
  }

  function store($dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'platforma' => 'reg',
      'typ' => 'reg',
      'temat' => 'reg|min:3',
      'opis' => 'reg|min:5',
    ],[
      'platforma.reg' => 'Należy Wybrać platformę',
      'typ.reg' => 'Należy określić typ zadania',
      'temat.reg' => 'Temat zadania nie może być pusty',
      'temat.min:3' => 'Temat zadania musi być dłuższu niż 3 znaki',
      'opis.reg' => 'Opis zadania jest wymagany',
      'opis.min:5' => 'Opis zadania musi być dłuższy niż 5 znaków'
    ]);

    $last_insert = SQL::insert('acp_zadania',[
        'platforma' => $from->platforma,
        'typ' => $from->typ,
        'serwer_id' => $from->serwer,
        'temat' => $from->temat,
        'opis' => $from->opis,
        'zlecajacy_id' => $this->user
      ]
    );

    Logs::log("Dodano nowe zadanie $from->temat (ID: $last_insert) Typ: $from->typ", "?x=zadania&xx=zadanie&id=$last_insert");

    // powiadomienie
    // osob posiadających dostęp do akceptacja lub odrzucenia zadania
    Powiadomienia::new(
      User::getUserHavePermission('ZadanieAkcOdrz'),
      [],
      "?x=zadania&xx=zadanie&id=$last_insert",
      "Zadania | Dodano nowe zadanie ".$from->temat." które oczekuje na akceptację",
      "fa fa-flag-o"
    );
  }

  function indexTask($taskId)
  {
    return [
      'task' =>  SQL::row("SELECT *, `serwer_id` AS `id_serwera`, (SELECT `login` FROM `acp_users` WHERE `user` = `zlecajacy_id` LIMIT 1) AS zlecajacy, (SELECT `login` FROM `acp_users` WHERE `user` = `technik_id` LIMIT 1) AS technik, (SELECT `login` FROM `acp_users` WHERE `user` = `akceptujacy_id` LIMIT 1) AS akceptujacy, (SELECT `nazwa` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS status_text, (SELECT `typ` FROM `acp_zadania_status` WHERE `id` = `status` LIMIT 1) AS status_kolor, (SELECT `nazwa` FROM `acp_serwery` WHERE `serwer_id` = `id_serwera` LIMIT 1) AS nazwa_serwera, (SELECT `nazwa` FROM `acp_zadania_platforma` WHERE `id` = `platforma` LIMIT 1) AS nazwa_platforma, (SELECT `nazwa` FROM `acp_zadania_typ` WHERE `id` = `typ` LIMIT 1) AS nazwa_typ FROM `acp_zadania` WHERE `id` = $taskId LIMIT 1; "),

      'comments' => SQL::all("SELECT *, (SELECT `login` FROM `acp_users` WHERE `user` = `u_id`) AS `nick`, (SELECT `steam_login` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_login`, (SELECT `steam_avatar` FROM `acp_users` WHERE `user` = `u_id`) AS `steam_avatar`, (SELECT `last_login` FROM `acp_users` WHERE `user` = `u_id`) AS `last_login` FROM `acp_zadania_com` WHERE `id_z` = $taskId;"),

      'todo' => SQL::all("SELECT * FROM `acp_zadania_todo` WHERE `zadanie_id` = $taskId"),

       'logs' => SQL::all("SELECT *,`user` AS `user_id`,(SELECT `login` FROM `acp_users` WHERE `user` = `user_id` LIMIT 1) AS `login` FROM `acp_log` WHERE `page` LIKE '%?x=zadania&xx=zadanie&id=$taskId%' ORDER BY `id` DESC")
    ];
  }

  function update($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'platforma' => 'reg',
      'typ' => 'reg',
      'temat' => 'reg|min:3',
      'opis' => 'reg|min:5',
    ],[
      'platforma.reg' => 'Należy Wybrać platformę',
      'typ.reg' => 'Należy określić typ zadania',
      'temat.reg' => 'Temat zadania nie może być pusty',
      'temat.min:3' => 'Temat zadania musi być dłuższu niż 3 znaki',
      'opis.reg' => 'Opis zadania jest wymagany',
      'opis.min:5' => 'Opis zadania musi być dłuższy niż 5 znaków'
    ]);

    SQL::update('acp_zadania',[
        'platforma' => $from->platforma,
        'typ' => $from->typ,
        'serwer_id' => $from->serwer,
        'temat' => $from->temat,
        'opis' => $from->opis,
      ],
      $from->id
    );
    Logs::log("Zedytowano zadanie $from->temat (ID: $from->id)", "?x=zadania&xx=zadanie&id=$from->id");
  }

  function destroy($id, $dostep)
  {
    Permission::check($dostep);

    $dane = SQL::row("SELECT `temat` FROM `acp_zadania` WHERE `id` = $this->id; ");
    SQL::query("DELETE FROM `acp_zadania` WHERE `id` = $this->id;");
    Logs::log("Usunięto zadanie $dane->temat (ID: $this->id)", "?x=zadania&xx=lista");
  }

  function task_akcept($id, $dostep)
  {
    Permission::check($dostep);

    SQL::update(
      'acp_zadania',
      [
        'status' => '1',
        'akceptujacy_id' => $this->user,
        'a_data' => date("Y-m-d H:i:s")
      ],
      $id
    );

    SQL::insert(
      'acp_zadania_users',
      [
        'id_zadania' => $id,
        'u_id' => $this->user
      ]
    );

    Logs::log("Zakceptowano zadanie ".Model('Task')->task($id)->temat." (ID: ".$id.")", "?x=zadania&xx=zadanie&id=".$id."");

    Powiadomienia::new(
      User::getUserHavePermission('ZadaniePrzyjmnij'),
      [],
      "?x=zadania&xx=zadanie&id=".$id."",
      "Zadania | ".Model('Task')->task($id)->temat." zostało zakceptowane, czeka na realizację..",
      "fa fa-flag-o"
    );
  }

  function task_odrzuc($id, $dostep)
  {
    Permission::check($dostep);

    SQL::update(
      'acp_zadania',
      [
        'status' => '-1',
        'akceptujacy_id' => $this->user,
        'a_data' => date("Y-m-d H:i:s")
      ],
      $id
    );

    Logs::log("Odrzucono zadanie ".Model('Task')->task($id)->temat." (ID: ".$id.")", "?x=zadania&xx=zadanie&id=".$id."");

    Powiadomienia::new(
      [$dane->zlecajacy_id],
      [],
      "?x=zadania&xx=zadanie&id=".$id."",
      "Zadania | ".Model('Task')->task($id)->temat." zostało odrzucone",
      "fa fa-flag-o"
    );
  }

  function task_przyjmnij($id, $dostep)
  {
    Permission::check($dostep);

    SQL::update(
      'acp_zadania',
      [
        'status' => '2',
        'technik_id' => $this->user,
        't_data' => date("Y-m-d H:i:s")
      ],
      $id
    );

    $czy_user_bierze_udzial = SQL::one("SELECT `u_id` FROM `acp_zadania_users` WHERE `id_zadania` = ".$id." AND `u_id` = $this->user");
    if(empty($czy_user_bierze_udzial)){
      SQL::insert(
        'acp_zadania_users',
        [
          'id_zadania' => $id,
          'u_id' =>$this->user
        ]
      );
    }

    Logs::log("Przyjęto zadanie ".Model('Task')->task($id)->temat." (ID: ".$id.") do realizacji przez ".User::Name($this->user)->steam_login." (".User::Name($this->user)->login.")", "?x=zadania&xx=zadanie&id=".$id."");

    Powiadomienia::new(
      [Model('Task')->task($id)->zlecajacy_id, Model('Task')->task($id)->akceptujacy_id],
      [],
      "?x=zadania&xx=zadanie&id=".$id."",
      "Zadania | ".Model('Task')->task($id)->temat." zostało przyjęte do realizacji przez ".User::Name($this->user)->steam_login." (".User::Name($this->user)->login.")",
      "fa fa-flag-o"
    );

  }

  function task_zakoncz($id, $dostep)
  {
    Permission::check($dostep);

    SQL::update(
      'acp_zadania',
      [
        'status' => '3',
        'procent_wykonania' => '100',
        'kolor_wykonania' => 'green',
        'time_end' => date("Y-m-d H:i:s"),
      ],
      $id
    );

    Logs::log("Zakończono zadanie ".Model('Task')->task($id)->temat." (ID: ".$id.")", "?x=zadania&xx=zadanie&id=".$id."");

    // users for powiadomienie
    $usersList = [];
    $zadanie_users = SQL::all("SELECT `u_id` FROM `acp_zadania_users` WHERE `id_zadania` = ".$id."");
    foreach ($zadanie_users as $zadanie_user) {
      $usersList[] = $zadanie_user->u_id;
    }
    // ignore users for Powiadomienie
    $usersListIgnore = [];
    ($this->user =! Model('Task')->task($id)->zlecajacy_id) ?: $usersListIgnore[] = Model('Task')->task($id)->zlecajacy_id;
    ($this->user =! Model('Task')->task($id)->akceptujacy_id) ?: $usersListIgnore[] = Model('Task')->task($id)->akceptujacy_id;

    Powiadomienia::new(
      $usersList,
      $usersListIgnore,
      "?x=zadania&xx=zadanie&id=".$id."",
      "Zadania | ".Model('Task')->task($id)->temat." zostało anulowane",
      "fa fa-flag-o"
    );
  }

  function task_anuluj($id, $dostep)
  {
    Permission::check($dostep);

    SQL::update(
      'acp_zadania',
      [
        'status' => '-2',
        'time_end' => date("Y-m-d H:i:s"),
      ],
      $id
    );

    Logs::log("Anulowano zadanie ".Model('Task')->task($id)->temat." (ID: ".$id.")", "?x=zadania&xx=zadanie&id=".$id."");

    // ignore users for Powiadomienie
    $usersListIgnore = [];
    ($this->user =! Model('Task')->task($id)->zlecajacy_id) ?: $usersListIgnore[] = Model('Task')->task($id)->zlecajacy_id;
    ($this->user =! Model('Task')->task($id)->akceptujacy_id) ?: $usersListIgnore[] = Model('Task')->task($id)->akceptujacy_id;
    ($this->user =! Model('Task')->task($id)->technik_id) ?: $usersListIgnore[] = Model('Task')->task($id)->technik_id;


    // users for powiadomienie
    $usersList = [];
    $zadanie_users = SQL::all("SELECT `u_id` FROM `acp_zadania_users` WHERE `id_zadania` = ".$id."");
    foreach ($zadanie_users as $zadanie_user) {
      $usersList[] = $zadanie_user->u_id;
    }
    // ignore users for Powiadomienie
    $usersListIgnore = [];
    ($this->user =! Model('Task')->task($id)->zlecajacy_id) ?: $usersListIgnore[] = Model('Task')->task($id)->zlecajacy_id;
    ($this->user =! Model('Task')->task($id)->akceptujacy_id) ?: $usersListIgnore[] = Model('Task')->task($id)->akceptujacy_id;
    ($this->user =! Model('Task')->task($id)->technik_id) ?: $usersListIgnore[] = Model('Task')->task($id)->technik_id;

    Powiadomienia::new(
      $usersList,
      $usersListIgnore,
      "?x=zadania&xx=zadanie&id=".$id."",
      "Zadania | ".Model('Task')->task($id)->temat." zostało anulowane",
      "fa fa-flag-o"
    );
  }

  function comment($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'komentarz_tekst' => 'reg'
    ],[
      'komentarz_tekst.reg' => 'Tekst komentarza nie może być pusty...'
    ]);

    SQL::insert(
      'acp_zadania_com',
      [
        'id_z' => $id,
        'u_id' => $this->user,
        'text' => $from->komentarz_tekst
      ]
    );

    Logs::log("Dodano komentarz do zadania ".Model('Task')->task($id)->temat." (ID: ".$id.")", "?x=zadania&xx=zadanie&id=".$id."");

    // users for powiadomienie
    $usersList = [];
    $zadanie_users = SQL::all("SELECT `u_id` FROM `acp_zadania_users` WHERE `id_zadania` = ".$id."");
    foreach ($zadanie_users as $zadanie_user) {
      $usersList[] = $zadanie_user->u_id;
    }
    // ignore users for Powiadomienie
    $usersListIgnore = [];
    ($this->user =! Model('Task')->task($id)->zlecajacy_id) ?: $usersListIgnore[] = Model('Task')->task($id)->zlecajacy_id;
    ($this->user =! Model('Task')->task($id)->akceptujacy_id) ?: $usersListIgnore[] = Model('Task')->task($id)->akceptujacy_id;
    ($this->user =! Model('Task')->task($id)->technik_id) ?: $usersListIgnore[] = Model('Task')->task($id)->technik_id;

    Powiadomienia::new(
      $usersList,
      $usersListIgnore,
      "?x=zadania&xx=zadanie&id=".$id."",
      "Zadania | ".Model('Task')->task($id)->temat." zostało anulowane",
      "fa fa-flag-o"
    );
  }

  function todoStore($id, $dostep)
  {
    Permission::check($dostep);

    $from = From::check([
      'todo_tekst' => 'reg'
    ],[
      'todo_tekst.reg' => 'Nazwa pozycji to-do jest wymagana'
    ]);

    SQL::insert(
      'acp_zadania_todo',
      [
        'zadanie_id' => $id,
        'tekst'=> $from->todo_tekst,
        'data' => date("Y-m-d H:i:s"),
        'pozostalo' => $from->todo_czasrealizacji,
        'zrealizowano' => 0
      ]
    );

    Logs::log("Dodano pozycję To Do do zadania ".Model('Task')->task($id)->temat." (ID: $id)", "?x=zadania&xx=zadanie&id=$id");
  }

  function todoDestroy($id, $dostep)
  {
    Permission::check($dostep);
		SQL::query("DELETE FROM `acp_zadania_todo` WHERE `id` = $id;");
		Logs::log("Usunięto pozycję To Do (ID: $id) w zadaniu ".Model('Task')->task($id)->temat." (ID: ".$id.")", "?x=zadania&xx=zadanie&id=".$id."", "?x=zadania&xx=zadanie&id=$id");
  }

  function todoStatus($id, $dostep)
  {
    Permission::check($dostep);

    $status = SQL::row("SELECT `zrealizowano` FROM `acp_zadania_todo` WHERE `id` = $id; ");
    $status->int = (1 == $status->zrealizowano) ? 0 : 1;
    $status->text = (1 == $status->zrealizowano) ? 'niezrealizowane' : 'zrealizowane';

    SQL::update(
      'acp_zadania_todo',
      [
        'zrealizowano' => $status->int,
        'zrealizowano_data' => date("Y-m-d H:i:s"),
      ],
      $id
    );

    Logs::log("Zmieniono status pozycji To Do (ID: $id) na $status->text w zadaniu ".Model('Task')->task($id)->temat." (ID: ".$id.")", "?x=zadania&xx=zadanie&id=".$id);

  }

  function zapros($id)
  {
    Permission::check($dostep);

    $from = From::check();

    if(empty(User::find($from->zapros_text)))
    {
      return Messe::array([
        'type' => 'warning',
        'text' => "Nie odnaleziono użytkownika."
      ]);
    }

    $form->user_dane_ist = SQL::row("SELECT * FROM `acp_zadania_users` WHERE `u_id` = ".User::find($from->zapros_text)." AND `id_zadania` = $id LIMIT 1");
    if($form->user_dane_ist->u_id == User::find($from->zapros_text)){
      return;
    }

    SQL::insert(
      'acp_zadania_users',
      [
        'id_zadania' => $id,
        'u_id' => User::find($from->zapros_text),
      ]
    );

    Logs::log("Dodano użytkownika ".User::Name(User::find($from->zapros_text))->steam_login." (".User::Name(User::find($from->zapros_text))->login." ID: ".User::find($from->zapros_text).") do zadania ID: $id", "?x=zadania&xx=zadanie&id=$id");

    Powiadomienia::new(
      [ User::find($from->zapros_text) ],
      [],
      "?x=zadania&xx=zadanie&id=$id_zadanie",
      "Zadania | Zostałeś dodany do zadania $temat. Weź w nim czynny udział.",
      "fa fa-flag-o"
    );
  }
}
?>
