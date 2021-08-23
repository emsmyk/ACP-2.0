<?php
class Text
{
	public static function limit($text, $length = '100')
  {
    if( strlen( $text) < $length){
      return $text;
    }

    list( $wrapped) = explode("\n", wordwrap( $text, $length));
    $remainder = substr( $text, strlen( $wrapped));
    $wrapped .= ' ... ';
    preg_match_all( '#<span class="highlight">[^<]+</span>#i', $remainder, $matches);
    $wrapped .= implode( ', ', $matches[0]);

    return $wrapped;
  }

	public static function clean($string)
	{
  	$aReplacePL = array('ą' => 'a', 'ę' => 'e', 'ś' => 's', 'ć' => 'c', 'ó' => 'o', 'ń' => 'n', 'ż' => 'z', 'ź' => 'z', 'ł' => 'l', 'Ą' => 'A', 'Ę' => 'E', 'Ś' => 'S', 'Ć' => 'C', 'Ó' => 'O', 'Ń' => 'N', 'Ż' => 'Z', 'Ź' => 'Z', 'Ł' => 'L');
     $string = str_replace(array_keys($aReplacePL), array_values($aReplacePL), $string);
     $string = strtolower($string);
     $string = str_replace(' ', '-', $string);
     $string = preg_replace('/[^0-9a-z\-]+/', '', $string);
     $string = preg_replace('/[\-]+/', '_', $string);
     $string = trim($string, '-');
     return $string;
  }

}
?>
