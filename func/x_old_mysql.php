<?
class SQL
{
  public static function query($sql)
  {
    $db = DB::getInstance();

    $db->query($sql);
  }

  public static function one($sql)
  {
    $db = DB::getInstance();

    $one = $db->get_row($sql);
    return $one[0];
  }

  public static function row($sql)
  {
    $db = DB::getInstance();
    return $db->get_results($sql, true)[0];
  }

  public static function all($sql)
  {
    $db = DB::getInstance();

    return $db->get_results($sql, true);
  }

  public static function insert($table, $array)
  {
    $db = DB::getInstance();
    $db->insert($table, $array);

    return $db->lastid();
  }

  public static function update($table, $fields, $id, $name_id='id')
  {
    $db = DB::getInstance();

    $db->update( $table, $fields, [$name_id => $id] );

    return;
  }
}

function query($sql) { return SQL::query($sql); }
function one($sql){ return SQL::one($sql); }
function row($sql){ return SQL::row($sql); }

function show($what, $die=true){
  echo "<pre><span style='color: red'>ACP | Admin Control Panel</br><small>return text/data</br></br></small></span>";
  print_r($what);
  echo "</pre>";

  if($die==true) { die; }
}
?>
