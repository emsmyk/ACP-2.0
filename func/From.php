<?php
class From
{
  public static function check($validate=null, $text=null)
  {
    $form = new stdClass();
    foreach ($_POST as $key => $value) {
      if($validate[$key]) {
        $validate_parm = explode('|', $validate[$key]);
        foreach ($validate_parm as $parm) {
          if($parm == 'reg'){
            if(empty($value)){
              $textReg = (empty($textReg)) ? "Pole $key nie może być puste" : $textReg;

              Messe::array([
                'type' => 'warning',
                'text' => $textReg
              ]);
            }
          }
          if($parm == 'text'){
            if(!is_string($value)){
              $textText = (empty($textText)) ? "Pole $key nie jest tekstem" : $textText;

              Messe::array([
                'type' => 'warning',
                'text' => $textText
              ]);
            }
          }
          if($parm == 'number'){
            if(!is_numeric($value)){
              $textNumber = (empty($textNumber)) ? "Pole $key nie jest liczbą" : $textNumber;

              Messe::array([
                'type' => 'warning',
                'text' => $textNumber
              ]);
            }
          }
          if(strpos($parm, "min:") !== false){
            $parm2 = explode(':', $parm);
            if(strlen($value) <= $parm2['1']){
              $textMin = (empty($textMin)) ? "Pole $key musi być dłuższe niż ".$parm2['1']." znaków" : $textMin;

              Messe::array([
                'type' => 'warning',
                'text' => $textMin
              ]);
            }
          }
          if(strpos($parm, "max:") !== false){
            $parm2 = explode(':', $parm);
            if(strlen($value) >= $parm2['1']){
              $textMax = (empty($textMax)) ? "Pole $key musi być krótsze niż ".$parm2['1']." znaków" : $textMax;

              Messe::array([
                'type' => 'warning',
                'text' => $textMax
              ]);
            }
          }
        }
      }
      $form->$key = $value;
    }

    if(!empty($_SESSION['messe'])){
      return back($_POST);
    }
    else {
      return $form;
    }
  }
}
?>
