<?php
class WpisyModel
{
  function __construct()
  {
    $this->user = User::get();

    $this->getCategory = SQL::all("SELECT `id`, `nazwa` FROM `acp_wpisy_kategorie` WHERE `id` != 0;");
		$this->COUNTWpisy = SQL::one("SELECT COUNT(*) FROM `acp_wpisy`;");
		$this->COUNTComment = SQL::one("SELECT COUNT(*) FROM `acp_wpisy_komentarze`;");
  }

  function wpis($id)
  {
    return SQL::row('SELECT * FROM `acp_wpisy` WHERE `id` = '.$id.' LIMIT 1');
  }

}
?>
