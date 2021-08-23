<?
class Messe {
  public function __construct()
  {
    $this->messe = (empty($_SESSION['messe'])) ? '' : $_SESSION['messe'] ;
  }

  public function show()
  {
    if(isset($_SESSION['messe'])){
      foreach ((array)$_SESSION['messe'] as $messe) {
        echo $this->one($messe['type'], $messe['text']);
      }
      unset($_SESSION['messe']);
    }

    if(!empty($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

  }

  public static function one($type='info', $text='')
  {
    return "<div class='alert alert-".$type." alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>$text</div>";
  }

  public static function expanded($type='info', $text='', $title='brak tytułu', $icon='fa fa-info')
  {
    return '<div class="alert alert-'.$type.' alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon '.$icon.'"></i> '.$title.'</h4>
                  '.$text.'
                </div>';
  }

  public static function array($array = [])
  {
    return $_SESSION['messe'][] = $array;
  }

}

$Messe = new Messe();
?>
