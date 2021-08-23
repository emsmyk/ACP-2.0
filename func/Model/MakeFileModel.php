<?php
class MakeFileModel
{
  function __construct()
  {
    $this->file = [
      'naglowek' => "////////////////////////////////////// \n//////////// EMCE ACP //////////////// \n////////////////////////////////////// \n\n",
      'stopka' => "\n////////////////////////////////////// \n//// Wykonano ".date("Y-m-d H:i:s")." //// \n//////////////////////////////////////"
    ];
  }

  function dir_exist($serwer)
  {
    $path = "www/upload/serwer_$serwer";
    if(!file_exists("$path")) {
      mkdir("www/upload/serwer_$serwer", 0700, true);
    }

    return $path;
  }


  function makeFile($name, $serwer, $type)
  {
    $path = $this->dir_exist($serwer);

    $file = fopen($path.$name, 'w');
    $text =  $this->file['naglowek'];

    $text .=  $this->makeFileType($type, $serwer);

    $text .=  $this->file['stopka'];
    fclose($file);

    return $name;
  }

  function makeFileType($type, $serwer)
  {
    $text = '';

    switch ($type) {
      case 'uslugi':
        $uslugi = SQL::all("SELECT *, (SELECT `nazwa` FROM `acp_uslugi_rodzaje` WHERE `id` = `rodzaj`) AS `nazwa`, (SELECT `flags` FROM `acp_uslugi_rodzaje` WHERE `id` = `rodzaj`) AS `flagi` FROM `acp_uslugi` WHERE `serwer` = $serwer");
        foreach($uslugi as $usluga){
          $text .= "//Rodzaj: $usluga->nazwa\n//Usługa do: $usluga->koniec  \n\"$usluga->steam_id\" \"$usluga->flagi\" \n";
        }
        break;
      case 'reklamy':
        $reklamaCoIle = SQL::one("SELECT `czas_reklam` FROM `acp_serwery` WHERE `serwer_id` = $serwer");
        $reklamaCoIle = ($reklamaCoIle < 10) ? '30' : $reklamaCoIle;

        $text .= "\"Reklama\" \n{ \n\"time\" \"$reklamaCoIle\" \n\"text\" \n	{ \n";

        $id = 1;
        $reklamy = SQL::all("SELECT * FROM `acp_serwery_reklamy` WHERE `serwer_id` IN ( 0, '".$serwer."' ) ORDER BY `id` +0 ASC;");
        foreach($reklamy as $reklama){
          if(date("j") >= (int)$reklama->zakres_start && date("j") <= (int)$reklama->zakres_stop && (int)$reklama->zakres == 1):
            $text .= "		\"".$id++."\" \n		{ \n			\"$reklama->gdzie\" \"$reklama->tekst\" \n		} \n";
          endif;
          if($reklama->zakres == 0):
            $text .= "		\"".$id++."\" \n		{ \n			\"$reklama->gdzie\" \"$reklama->tekst\" \n		} \n";
          endif;
        }
        $text .= "	} \n}";
        break;
      case 'hextags':
        $text .= "\"HexTags\" \n{";
        $hextags = SQL::all("SELECT * FROM `acp_serwery_hextags` WHERE `serwer_id` IN ( 0, '".$serwer."' ) ORDER BY `istotnosc` +0 DESC;");
        foreach($hextags as $hextag){
          $text .= "	\n	\"$hextag->hextags\" \n	{ \n		\"TagName\"	\"$hextag->TagName \" \n		\"ScoreTag\"	\"$hextag->ScoreTag \" \n		\"ChatTag\"	\"{".$hextag->TagColor."} $hextag->ChatTag \" \n		\"ChatColor\"	\"{".$hextag->ChatColor."}\" \n		\"NameColor\"	\"{".$hextag->NameColor."}\" \n		\"Force\"	\"$hextag->Force\" \n	}";
        }
        $text .= "\n}";
        break;
      case 'database':
        $text .= "\"Databases\" \n{ \n \"driver_default\"		\"mysql\" \n";
        $text .= "\n	\"storage-local\" \n	{ \n		\"driver\"	\"sqlite\" \n		\"host\"	\"sourcemod-local\"  \n	}";

        $databases = SQL::all("SELECT * FROM `acp_serwery_baza` WHERE `serwer_id` IN ( 0, $serwer);");
        foreach($databases as $database){
          if($database->d_time_port_on == 1) {

            $text .= "\n	\"$database->nazwa\" \n	{ \n		\"driver\"	\"$database->d_driver\" \n		\"host\"	\"$database->d_host\" \n		\"database\"	\"$database->d_baze\" \n		\"user\"	\"$database->d_user\" \n		\"pass\"	\"$database->d_pass\" \n		\"timeout\"	\"$database->d_timeout\" \n		\"port\"	\"$database->d_port\" \n	}";
          }
          else {
            $text .= "\n	\"$database->nazwa\" \n	{ \n		\"driver\"	\"$database->d_driver\" \n		\"host\"	\"$database->d_host\" \n		\"database\"	\"$database->d_baze\" \n		\"user\"	\"$database->d_user\" \n		\"pass\"	\"$database->d_pass\"  \n	}";
          }
        }
        break;
      case 'mapy_umc':
        $text .= "\"umc_mapcycle\" \n{";

        $grupy_q = SQL::all("SELECT * FROM `acp_serwery_mapy` WHERE `serwer_id` IN ( 0, $serwer); ");
        foreach($grupy_q as $grupy){
          $text .= "\n  \"$grupy->nazwa\" \n  {";
            if(!empty($grupy->display_template) || $grupy->display_template != 0){
              $text .= "\n    \"display-template\"     \"$grupy->display_template\" ";
            }
            if(!empty($grupy->maps_invote) || $grupy->maps_invote != 0){
              $text .= "\n    \"maps_invote\"     \"$grupy->maps_invote\" ";
            }
            if(!empty($grupy->group_weight) || $grupy->group_weight != 0){
              $text .= "\n    \"group_weight\"     \"$grupy->group_weight\" ";
            }
            if(!empty($grupy->next_mapgroup) || $grupy->next_mapgroup != 0){
              $text .= "\n    \"next_mapgroup\"     \"$grupy->next_mapgroup\" ";
            }
            if(!empty($grupy->default_min_players) || $grupy->default_min_players != 0){
              $text .= "\n    \"default_min_players\"     \"$grupy->default_min_players\" ";
            }
            if(!empty($grupy->default_max_players) || $grupy->default_max_players != 0){
              $text .= "\n    \"default_max_players\"     \"$grupy->default_max_players\" ";
            }
            if(!empty($grupy->default_min_time) || $grupy->default_min_time != 0){
              $text .= "\n    \"default_min_time\"     \"$grupy->default_min_time\" ";
            }
            if(!empty($grupy->default_max_time) || $grupy->default_max_time != 0){
              $text .= "\n    \"default_max_time\"     \"$grupy->default_max_time\" ";
            }
            if(!empty($grupy->default_allow_every) || $grupy->default_allow_every != 0){
              $text .= "\n    \"default_allow_every\"     \"$grupy->default_allow_every\" ";
            }
            if(!empty($grupy->command) || $grupy->command != 0){
              $text .= "\n    \"command\"     \"$grupy->command\" ";
            }
            if(!empty($grupy->nominate_flags) || $grupy->nominate_flags != 0){
              $text .= "\n    \"nominate_flags\"     \"$grupy->nominate_flags\" ";
            }
            if(!empty($grupy->adminmenu_flags) || $grupy->adminmenu_flags != 0){
              $text .= "\n    \"adminmenu_flags\"     \"$grupy->adminmenu_flags\" ";
            }

            $mapy_q = SQL::all("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $grupy->id ORDER BY `nazwa` ASC; ");
            foreach($mapy_q as $mapy){
              $text .= "\n    \"$mapy->nazwa\" \n     {";
                if(!empty($mapy->display) || $mapy->display != 0){
                  $text .= "\n      \"display\"     \"$mapy->display\" ";
                }
                if(!empty($mapy->weight) || $mapy->weight != 0){
                  $text .= "\n      \"weight\"     \"$mapy->weight\" ";
                }
                if(!empty($mapy->next_mapgroup) || $mapy->next_mapgroup != 0){
                  $text .= "\n      \"next_mapgroup\"     \"$mapy->next_mapgroup\" ";
                }
                if(!empty($mapy->min_players) || $mapy->min_players != 0){
                  $text .= "\n      \"min_players\"     \"$mapy->min_players\" ";
                }
                if(!empty($mapy->max_players) || $mapy->max_players != 0){
                  $text .= "\n      \"max_players\"     \"$mapy->max_players\" ";
                }
                if(!empty($mapy->min_time) || $mapy->min_time != 0){
                  $text .= "\n      \"min_time\"     \"$mapy->min_time\" ";
                }
                if(!empty($mapy->max_time) || $mapy->max_time != 0){
                  $text .= "\n      \"max_time\"     \"$mapy->max_time\" ";
                }
                if(!empty($mapy->allow_every) || $mapy->allow_every != 0){
                  $text .= "\n      \"allow_every\"     \"$mapy->allow_every\" ";
                }
                if(!empty($mapy->command) || $mapy->command != 0){
                  $text .= "\n      \"command\"     \"$mapy->command\" ";
                }
                if(!empty($mapy->nominate_flags) || $mapy->nominate_flags != 0){
                  $text .= "\n      \"nominate_flags\"     \"$mapy->nominate_flags\" ";
                }
                if(!empty($mapy->adminmenu_flags) || $mapy->adminmenu_flags != 0){
                  $text .= "\n      \"adminmenu_flags\"     \"$mapy->adminmenu_flags\" ";
                }
                if(!empty($mapy->nominate_group) || $mapy->nominate_group != 0){
                  $text .= "\n      \"nominate_group\"     \"$mapy->nominate_group\" ";
                }
              $text .= "\n     } \n";
            }

          $text .= "\n  } \n";
        }

        $text .= "  \n} \n";
        break;
      case 'mapchooser':
        $grupy_q = SQL::all("SELECT * FROM `acp_serwery_mapy` WHERE `serwer_id` IN ( 0, $serwer); ");
        foreach($grupy_q as $grupy){
          $text .= "//Grupa Map: $grupy->nazwa\n";
          $mapy_q = SQL::all("SELECT * FROM `acp_serwery_mapy_det` WHERE `mapy_id` = $grupy->id ORDER BY `nazwa` ASC; ");
          foreach($mapy_q as $mapy){
            $text .= "$mapy->nazwa\n";
          }
        }
        break;
      case 'help_menu':
        $text .= "\"helpmenu\" \n{";
        $query = SQL::all("SELECT * FROM `acp_serwery_helpmenu` WHERE `serwer_id` = $serwer; ");
        foreach($query as $row){
          if($row->lista_serwerow == 1){
            $text .= "\n    \"listserwer\" \n     {\n      \"nazwa\"  \"Lista Serwerów\" \n      \"komenda\"  \"sm_serwery\" \n     }";
          }
          if($row->lista_adminow == 1){
            $text .= "\n    \"listaadminow\" \n     {\n      \"nazwa\"  \"Lista Adminów\" \n      \"komenda\"  \"sm_admini\" \n     }";
          }
          if($row->opis_vipa == 1){
            $text .= "\n    \"vip\" \n     {\n      \"nazwa\"  \"Opis Vipa\" \n      \"komenda\"  \"sm_vip\" \n     }";
          }
          if($row->lista_komend == 1){
            $text .= "\n    \"komendy\" \n     {\n      \"nazwa\"  \"Lista komend\" \n      \"komenda\"  \"sm_komendy\" \n     }";
          }
          if($row->statystyki == 1){
            $text .= "\n    \"statytki\" \n     {\n      \"nazwa\"  \"Statystki\" \n      \"komenda\"  \"sm_statystki\" \n     }";
          }
        }
        $text .= "  \n} \n";
        break;
      case 'help_menu_listaserwerow':
        $text .= "\"listserwer\" \n{";
        $query = SQL::all("SELECT `istotnosc`, `nazwa`, `mod`, `graczy`, `max_graczy` FROM `acp_serwery` WHERE `serwer_on` = 1 ORDER BY `istotnosc` ASC");
        foreach($query as $row){
          $text .= "\n    \"$row->istotnosc\" \n     {\n      \"nazwa\"  \"[$row->mod] $row->nazwa\" \n      \"graczy\"  \"$row->graczy\" \n      \"sloty\"  \"$row->max_graczy\" \n      \"ID\"  \"$row->istotnosc\" \n     }";
        }
        $text .= "  \n} \n";
        break;
      case 'help_menu_listaserwerow_details':
        $text .= "\"serverdetale\" \n{";
        $query = SQL::all("SELECT `istotnosc`, `nazwa`, `mod`, `graczy`, `max_graczy`, `ip`, `port`, `mapa` FROM `acp_serwery` WHERE `serwer_on` = 1 ORDER BY `istotnosc` ASC");
        foreach($query as $row){
          $text .= "\n    \"$row->istotnosc\" \n     {\n      \"nazwa\"  \"[$row->mod] $row->nazwa\" \n      \"graczy\"  \"$row->graczy\" \n      \"sloty\"  \"$row->max_graczy\" \n      \"mapa\"  \"$row->mapa\" \n      \"ip\"  \"$row->ip:$row->port\" \n      \"id\"  \"$row->istotnosc\" \n     }";
        }
        $text .= "  \n} \n";
        break;
      case 'help_menu_listaadminow':
        $text .= "\"admins\" \n{";
        $dane = SQL::one("SELECT `dane` FROM `acp_cache_api` WHERE `get` = 'serwer_id".$serwer."_admin' LIMIT 1;");
        $i = 1;
        foreach(json_decode($dane) as $row){
          switch ($row->steam_status) {
            case 0:
              $row->steam_status_tekst = 'Offline';
              break;
            case 1:
              $row->steam_status_tekst = 'Online';
              break;
            case 3:
              $row->steam_status_tekst = 'Zajęty';
              break;
            case 4:
              $row->steam_status_tekst = 'Zajęty';
              break;
            default:
              $row->steam_status_tekst = '-';
              break;
          }
          $text .= "\n    \"".$i++."\" \n     {\n      \"nick\"  \"$row->steam_nick\" \n      \"ranga\"  \"$row->srv_group\" \n      \"steamID\"  \"$row->authid\" \n      \"status\"  \"$row->steam_status_tekst\" \n     }";
        }
        $text .= "  \n} \n";
        break;
      case 'help_menu_opisvipa':
        $text .= "\"vippanel\" \n{";
        $dane = SQL::all("SELECT `tekst`, `kolejnosc` FROM `acp_serwery_helpmenu_vip` WHERE `serwer_id` = '$serwer' ORDER BY `kolejnosc` ASC;");
        foreach($dane as $row){
          $text .= "\n    \"$row->kolejnosc\" \n     {\n      \"nazwa\"  \"$row->tekst\" \n      \"nr\"  \"$row->kolejnosc\" \n     }";
        }
        $text .= "  \n} \n";
        break;
      case 'help_menu_komendy':
        $text .= "\"komendy\" \n{";
        $dane = SQL::all("SELECT `komenda`, `tekst`, `kolejnosc` FROM `acp_serwery_helpmenu_komendy` WHERE `serwer_id` = '$serwer' ORDER BY `kolejnosc` ASC;");
        foreach($dane as $row){
          $text .= "\n    \"$row->kolejnosc\" \n     {\n      \"komenda\"  \"$row->komenda\" \n      \"opis\"  \"$row->tekst\" \n     }";
        }
        $text .= "  \n} \n";
        break;
      case 'help_menu_statystyki':
        $text .= "\"statystyki\" \n{";
          $text .= "\n    \"1\" \n     {\n      \"komenda\"  \"say top10\" \n      \"opis\"  \"Top10\" \n     }";
          $text .= "\n    \"2\" \n     {\n      \"komenda\"  \"say rank\" \n      \"opis\"  \"Moja pozycja\" \n     }";
        $text .= "  \n} \n";
        break;
      case 'roundsound':
        $text .= "\"Abner Res\" \n{";
          $aktualiny_rs = SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound' LIMIT 1");
          $lista_piosenek = SQL::one("SELECT `lista_piosenek` FROM `rs_roundsound` WHERE `id` = $aktualiny_rs LIMIT 1");
          $lista_piosenek = json_decode($lista_piosenek);
          foreach ($lista_piosenek as $value) {
            $piosenka = SQL::row("SELECT `id`, `nazwa`, `wykonawca`, `album`, `mp3_code` FROM `rs_utwory` WHERE `id` = $value LIMIT 1");
            $text .= "\n    \"".$piosenka->mp3_code.".mp3\" \n     {\n      \"songname\"  \"".$piosenka->nazwa." - ".$piosenka->wykonawca."\" \n     }";
          }

        $text .= "  \n} \n";

        $text .= $stopka;
        fclose($file);
        break;
      case 'roundsound_cfg':
        $text .= "\n";
        $text .= "// ConVars for plugin abner_res.smx\n";
        $text .= " abner_res_version \"4.1\" \n";
        $text .= " res_client_preferences \"1\" \n";
        $rs_katalog = SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_katalog' LIMIT 1");
        $rs_roundsound = SQL::one("SELECT `conf_value` FROM `rs_ustawienia` WHERE `conf_name` = 'rs_roundsound' LIMIT 1");
        $text .= " res_ct_path \"$rs_katalog/$rs_roundsound\" \n";
        $text .= " res_tr_path \"$rs_katalog/$rs_roundsound\" \n";
        $text .= " res_default_volume \"0.75\" \n";
        $text .= " res_draw_path \"1\" \n";
        $text .= " res_play_to_the_end \"0\" \n";
        $text .= " res_play_type \"1\" \n";
        $text .= " res_print_to_chat_mp3_name \"1\" \n";
        $text .= " res_stop_map_music \"1\" \n";
        break;
      default:
        $text .= 'Błąd, brak wybranego stylu pliku..';
        break;
    }

    return $text;
  }
}
 ?>
