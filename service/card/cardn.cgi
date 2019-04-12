<?php

/**
 * cardn.cgi of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 10:14 AM
 */

require("../../config/config.inc");
require("../../global_funcs/sec_functions.inc");
//defines

$gid = isset($_POST['gid']) ? intval($_POST['gid']) : NULL;
$mac_addr = isset($_POST['mac_addr']) ? $_POST['mac_addr'] : NULL;
$type = isset($_POST['type']) ? intval($_POST['type']) : NULL;
$card_no = isset($_POST['card_no']) ? ($_POST['card_no']) : NULL;
$tenpo_id = isset($_POST['tenpo_id']) ? intval($_POST['tenpo_id']) : NULL;
$v = isset($_POST['v']) ? intval($_POST['v']) : NULL;
$trid = isset($_POST['trid']) ? ($_POST['trid']) : NULL;
$cmd_str = isset($_POST['cmd_str']) ? intval($_POST['cmd_str']) : NULL;
$xml_in = isset($_POST['data']) ? ($_POST['data']) : NULL;


if ($mac_addr != NULL && checkmac($mac_addr)) exit;
if ($trid != NULL && checkMD5($trid)) exit;

$printString = "";

switch ((string)$gid)
{
    case "2110":
    {
        if ($card_no == "7020392000000000")
        {
            require_once("2110/song_unlock.php");
            switch ((string)$type)
            {

                case "402":
                {
                    $printString = getSession();
                    strOutput($printString->saveXML());
                }

                case "428":
                {
                    $printString = getSongUnlock();
                    strOutput($printString->saveXML());
                }

                case "465":
                {
                    $printString = getMusicExtra();
                    strOutput($printString->saveXML());
                }
                case "467":
                {
                    $printString = getMusicAou();
                    strOutput($printString->saveXML());
                }
            }
        }
        else
        {
            require_once("2110/card.php");
            switch ((string)$type)
            {
                case "771":
                {
                    $printString = card_writer($card_no,$xml_in);
                    //strOutput($printString->saveXML());
                }
                case "259":
                {
                    $printString = card($card_no);
                    strOutput($printString->saveXML());
                }
            }
        }
    }
    case "303203":
    {
        if ($card_no == "7020392000000000")
        {

        } else
        {
            require_once("3032/card.php");
            switch ((string)$type)
            {
                case "515":
                {
                    $printString = get_player_stats($xml_in);
                    exit;
                }
            }
        }
    }

}

//strOutput($printString->saveXML());

function strOutput($strParam)
{
    $strParam = "1\n1,1\n" . $strParam;
    header("Content-Type:application/octet-stream");
    header(sprintf("Content-Length: %d", strlen($strParam)));
    print($strParam);
    exit;
}