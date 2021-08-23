<?php
class Steam
{
  public function __construct($api)
  {
    $this->apikey = $api;
  }

  function GetSteamData($steam)
  {
    $steam = $this->toCommunityID($steam);

    $ftp_path = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$this->apikey.'&steamids='.$steam.'&format=json';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ftp_path);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    curl_close($curl);

    if($data) {
      return json_decode($data, true)['response']['players'][0];
    }
  }

  function toCommunityID($id)
  {
    if (preg_match('/^STEAM_/', $id)) {
      $parts = explode(':', $id);
      return bcadd(bcadd(bcmul($parts[2], '2'), '76561197960265728'), $parts[1]);
    } elseif (is_numeric($id) && strlen($id) < 16) {
      return bcadd($id, '76561197960265728');
    } else {
      return $id;
    }
  }

  function toSteamID($id)
  {
    if (is_numeric($id) && strlen($id) >= 16) {
      $z = bcdiv(bcsub($id, '76561197960265728'), '2');
    } elseif (is_numeric($id)) {
      $z = bcdiv($id, '2');
    } else {
      return $id;
    }
    $y = bcmod($id, '2');
    return 'STEAM_0:' . $y . ':' . floor($z);
  }
}

$Steam = new Steam($acp_system['acp_steam_api']);
?>
