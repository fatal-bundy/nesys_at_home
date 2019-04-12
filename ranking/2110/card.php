<?php
/**
 * 2110/cardn.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 9/9/2018
 * Time: 10:32 PM
 */

// defines
require("../../config/config.inc");

define('unlock_keynum writer', '1020');
define('card', '259');
define('card_details', '260');
define('card_detials_history', '261');
define('card_bdata', '264');
define('session_get', '401');
define('session_start', '402');
define('avatar', '418');
define('item', '420');
define('skin', '422');
define('title', '424');
define('music', '428');
define('event_reward', '441');
define('navigator', '443');
define('music_extra', '465');
define('music_aou', '467');
define('coin', '468');
define('unlock_reward', '507');
define('unlock_keynum', '509');
define('card_writer', '771');
define('card_details_writer', '772');
define('card_bdata writer', '776');
define('sound_effect', '8458');
define('get_message', '8461');
define('cond', '8465');
define('total_trophy', '8468');
define('avatar writer', '929');
define('item writer', '931');
define('title writer', '935');
define('music_detail writer', '941');
define('navigator writer', '954');
define('coin writer', '980');

//require("../../global_funcs/sec_functions.inc");



$gid = isset($_GET['gid']) ? intval($_GET['gid']) : NULL;
$mac_addr = isset($_GET['mac_addr']) ? $_GET['mac_addr'] : NULL;
$type = isset($_GET['type']) ? intval($_GET['type']) : NULL;
$card_no = isset($_GET['card_no']) ? ($_GET['card_no']) : NULL;
$tenpo_id = isset($_GET['tenpo_id']) ? intval($_GET['tenpo_id']) : NULL;
$v = isset($_GET['v']) ? intval($_GET['v']) : NULL;
$trid = isset($_GET['trid']) ? ($_GET['trid']) : NULL;
$cmd_str = isset($_GET['cmd_str']) ? intval($_GET['cmd_str']) : NULL;
$xml_in = isset($_GET['data']) ? ($_GET['data']) : NULL;

//if ($mac_addr != NULL && checkmac($mac_addr)) exit;
//if ($trid != NULL && checkMD5($trid)) exit;

function fnc_card_writer($data,$mac,$card_id)
{
    $xml=simplexml_load_string($data) or die("Error: Cannot create object");
    print($xml->data->pcol2);
}

switch($type)
{
    case card_details_writer:
    {
        fnc_card_writer($xml_in,$mac_addr,$card_no);
    }
}