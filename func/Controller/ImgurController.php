<?php
class ImgurController
{
  function img($url, $size)
  {
    if($url == 'https://acp.sloneczny-dust.pl/www/maps/nomap.jpg'){
      return $url;
    }

    $url = pathinfo($url);

    switch ($size) {
      case '1':
        $url = $url[dirname].'/'.$url[filename].'s.'.$url[extension];
        break;
      case '2':
        $url = $url[dirname].'/'.$url[filename].'b.'.$url[extension];
        break;
      case '3':
        $url = $url[dirname].'/'.$url[filename].'t.'.$url[extension];
        break;
      case '4':
        $url = $url[dirname].'/'.$url[filename].'m.'.$url[extension];
        break;
      case '5':
        $url = $url[dirname].'/'.$url[filename].'l.'.$url[extension];
        break;
      case '6':
        $url = $url[dirname].'/'.$url[filename].'h.'.$url[extension];
        break;
    }
    return $url;
  }
}
 ?>
