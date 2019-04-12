<?php
/**
 * 3032/ranking.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 7:26 AM
 */


require("functions.php");

function checkipaddr($ip)
{
    return (preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $ip) == 0);
}

function checkmac($mac)
{
    return (preg_match('/([a-fA-F0-9]{2}){6}/', $mac) == 0);
}

$gid = isset($_GET['gid']) ? intval($_GET['gid']) : NULL;
$mac_addr = isset($_GET['mac_addr']) ? $_GET['mac_addr'] : NULL;
$cmd_type = isset($_GET['cmd_type']) ? intval($_GET['cmd_type']) : NULL;
$tenpo_id = isset($_GET['tenpo_id']) ? intval($_GET['tenpo_id']) : NULL;
$param = $_GET['param'];
//if (checkmac($mac_addr)) exit;
// parse param= xml and sanitization

$xml = simplexml_load_string($param) or die(403);

$rows_upper = ($xml->data->row_count == "") ? 100 : intval($xml->data->row_count);
$rows_lower = ($xml->data->offset == "") ? 1 : intval($xml->data->offset);
if ($rows_lower == 0) $rows_lower = 1;
$card_id = ($xml->info->card_id == "") ? NULL : intval($xml->info->card_id);
$remote_ip = ($xml->info->tenpo_ip_addr == "") ? NULL : $xml->info->tenpo_ip_addr;
//if ($remote_ip != NULL && checkipaddr($remote_ip)) exit;
$local_ip = ($xml->info->local_ip_addr == "") ? NULL : $xml->info->local_ip_addr;
//if ($local_ip != NULL && checkipaddr($local_ip)) exit;
$perf_name = ($xml->info->perf_name == "") ? NULL : intval($xml->info->perf_name);
$username = ($xml->info->tenpo_name == "") ? NULL : intval($xml->info->tenpo_name);

$data = "";
switch ((string)$cmd_type)
{
    case '4497':
    {
        $data = get_vp_rank($rows_lower, $rows_upper);
        break;
    }

    case '4498':
    {
        $data = get_m_vp_rank($rows_lower, $rows_upper);
        break;
    }

    case '4123':
    {
        $data = get_g_pcol1_score_rank($rows_lower, $rows_upper);
        break;
    }

    case '4122':
    {
        $data = get_m_g_pcol1_score_rank($rows_lower, $rows_upper);
        break;
    }

    case '4144':
    {
        $data = get_pcol1_score_rank($rows_lower, $rows_upper);
        break;
    }

    case '4241':
    {
        $data = get_m_pcol1_score_rank($rows_lower, $rows_upper);
        break;
    }

    default:
    {
        exit;
    }

}
strOutput($data->saveXML());

function strOutput($strParam)
{
    $strParam = "1\n".$strParam;
    header("Content-Type:application/octet-stream");
    header(sprintf("Content-Length: %d", strlen($strParam)));
    print($strParam);
    exit;
}