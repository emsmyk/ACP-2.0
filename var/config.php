<?php
define( 'SEND_ERRORS_TO', 'zabawa@emsmyk.pl' ); //set email notification email address
define( 'DISPLAY_DEBUG', true ); //display db errors?

define( 'ACP_V', '2.01' );
define( 'SITE', 'https://acp.sloneczny-dust.pl');

$db = new DB();

//ustawienia systemu acp
$acp_system_1 = array();
$acp_system_2 = array();

foreach($db->get_results("SELECT * FROM `acp_system`") as $acp_ustawienia){
	array_push($acp_system_1, $acp_ustawienia['conf_name']);
	array_push($acp_system_2, $acp_ustawienia['conf_value']);
}
$acp_system = array_combine($acp_system_1,$acp_system_2);

//strefa czasowa
date_default_timezone_set($acp_system['acp_timezone']);
?>
