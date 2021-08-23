<?php
class ImgurUploadFileModel
{
  function __construct()
  {
    $this->api = SQL::one("SELECT `conf_value` FROM `acp_system` WHERE `conf_name` = 'GaleriaMap_api' LIMIT 1");
  }

  function upload($file)
  {
    $handle = fopen($file, "r");
    $data = fread($handle, filesize($file));
    $pvars   = array('image' => base64_encode($data));
    $timeout = 30;
    $curl    = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $this->api));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
    $out = curl_exec($curl);
    curl_close ($curl);
    $pms = json_decode($out,true);
    $url=$pms['data']['link'];

    return $url;
  }
}
 ?>
