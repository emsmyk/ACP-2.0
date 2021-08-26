<?
class RootUsersController
{
 public function __construct()
 {
   $this->db = DB::getInstance();

   $this->id = Get::int('id');
   $this->co = Get::string('co');
 }

 function index()
 {
   return $this->db->get_results("SELECT *, (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa` LIMIT 1) AS `grupa` FROM `acp_users` ORDER BY `user` +0 ASC;", true);
 }

 function edit()
 {
   return $this->db->get_row("SELECT user, grupa, login,	email, steam, wirepusher, (SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = `grupa`) AS `nazwa_grupy` FROM `acp_users` WHERE `user` = $this->id; ", true);
 }

 function update()
 {
   $from = From::check([
     'login' => 'reg',
     'steam' => 'reg|number'
   ],[
     'login.reg' => 'Pole login jest wymagane',
     'steam.reg' => 'Pole steam jest wymagane',
     'steam.number' => 'Pole steam musi składać się z cyfr'
   ]);

   $from->new->grupa_nazwa = $this->db->get_row("SELECT `nazwa` FROM `acp_users_grupy` WHERE `id` = ".$from->grupa." LIMIT 1")[0];
   $from->old = $this->db->get_row("SELECT `login`, `steam`, `email`, `grupa` FROM `acp_users` WHERE `user` = '$from->id' LIMIT 1", true);
   $from->old_grupa = $this->db->get_row("SELECT `id`, `nazwa` FROM `acp_users_grupy` WHERE `id` = ".$from->old->grupa." LIMIT 1", true);

   $this->db->update('acp_users',[
     'login' => $from->login,
     'steam' => $from->steam,
     'email' => $from->mail,
     'grupa' => $from->grupa,
     'wirepusher' => $from->wirepusher,
   ], [
     'user' => $from->id
   ]);

   $from->log = "Zaktualizowano konto użytkownika ID: $from->id Zmieniono:";
   if($from->old->login != $from->login){
     $from->log .= " Login: ".$from->old->login." -> ".$from->login;
   }
   if($from->old->steam != $from->steam){
     $from->log .= " Steam: ".$from->old->steam." -> ".$from->steam;
   }
   if($from->old->email != $from->mail){
     $from->log .= " Mail: ".$from->old->email." -> ".$from->mail;
   }
   if($from->old_grupa->id != $from->grupa){
     $from->log .= " Grupę: ".$from->old_grupa->nazwa."(ID: ".$from->old_grupa->id.") -> ".$from->new->grupa_nazwa." (ID: ".$from->grupa.")";
   }
   Logs::log($from->log, "?x=account&id=$id");
 }
 function password($id)
 {
   $pass = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"),0, 8);
   $user = $this->db->get_row("SELECT `login` FROM `acp_users` WHERE `user` = $id; ")[0];

   $this->db->update('acp_users',[
     'pass' => md5($pass)
   ], [
     'user' => $id
   ]);

   Logs::log("Wygenerowno nowe hasło dla użytkonika $user (ID: $id)");
   
   return Messe::array([
     'type' => 'success',
     'text' => "Wygenerowno nowe hasło dla użytkonika $user (ID: $id) Hasło: $pass"
   ]);
 }
 function ban($id)
 {
   $user = $this->db->get_row("SELECT `login`, `banned` FROM `acp_users` WHERE `user` = $id LIMIT 1;", true);

   if($user->banned == -1){
     $this->db->query("UPDATE `acp_users` SET `banned` = '0' WHERE `user` = $id LIMIT 1;");
     Logs::log("Użytkonik $user->login (ID: $id) został zablokowany", "?x=acp_users");
   }
   else if($user->banned == 0){
     $this->db->query("UPDATE `acp_users` SET `banned` = '-1' WHERE `user` = $id LIMIT 1;");
     Logs::log("Użytkonik $user->login (ID: $id) został odblokowany", "?x=acp_users");
   }
 }
 function destroy($id)
 {
   $user = $this->db->get_row("SELECT `login` FROM `acp_users` WHERE `user` = $id; ")[0];

   $this->db->query("DELETE FROM `acp_users` WHERE `user` = $id; ");
   Logs::log("Użytkonik $user (ID: $id) został usunięty", "?x=acp_users");
 }
}
?>
