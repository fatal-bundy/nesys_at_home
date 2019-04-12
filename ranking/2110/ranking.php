<?php

/**
 * 2110/ranking.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 10:14 AM
 */

require("../../config/config.inc");

$gid = isset($_GET['gid']) ? intval($_GET['gid']) : NULL;
$mac_addr = isset($_GET['mac_addr']) ? $_GET['mac_addr'] : NULL;
$cmd_type = isset($_GET['cmd_type']) ? intval($_GET['cmd_type']) : NULL;
$tenpo_id = isset($_GET['tenpo_id']) ? intval($_GET['tenpo_id']) : NULL;
$param = $_GET['param'];

if (checkmac($mac_addr)) exit;

$username = MYSQL_USERNAME;
$password = MYSQL_PASSWORD;
$hostname = MYSQL_HOSTNAME;
$port = MYSQL_PORT;
$service_name = MYSQL_SERVICE_NAME;
$connection_string = RANKING_2110_DATABASE;
$dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_2110_DATABASE;

// parse param= xml and sanitization

$xml = simplexml_load_string($param) or exit;

$rows_upper = ($xml->data->row_count == "") ? 100 : intval($xml->data->row_count);
$rows_lower = ($xml->data->offset == "") ? 1 : intval($xml->data->offset);
if ($rows_lower == 0) $rows_lower = 1;
$card_id = ($xml->info->card_id == "") ? NULL : intval($xml->info->card_id);
$remote_ip = ($xml->info->tenpo_ip_addr == "") ? NULL : $xml->info->tenpo_ip_addr;
if ($remote_ip != NULL && checkipaddr($remote_ip)) exit;
$local_ip = ($xml->info->local_ip_addr == "") ? NULL : $xml->info->local_ip_addr;
if ($local_ip != NULL && checkipaddr($local_ip)) exit;
$perf_name = ($xml->info->perf_name == "") ? NULL : intval($xml->info->perf_name);
$username = ($xml->info->tenpo_name == "") ? NULL : intval($xml->info->tenpo_name);

$data = "";

switch ((string)$cmd_type)
{
    case '4119':
    {
        $data = getGlobalRanking($rows_lower, $rows_upper);
        break;
    }

    case '6657':
    {
        $data = getPlayNumRank($rows_lower, $rows_upper);
        break;
    }

    case '6661':
    {
        $data = getEventRank($rows_lower, $rows_upper);
        break;
    }

    default:
    {
        exit;
    }

}
strOutput($data->saveXML());

function getGlobalRanking($rows_lower, $rows_upper)
{
    $table = "global";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_2110_DATABASE;
    $dbh = NULL;

    try
    {
        // Connect
        $dbh = new PDO($dsn_ranking, MYSQL_USERNAME, MYSQL_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 0); // AutoCommit OFF
        $dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    } catch (PDOException $e)
    {
        // Exception
        //quit(ERROR_009);
    }

    $hash_ref_rank_entry = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("score_rank");
    $rank_root->appendChild($rank_xml_rankscore);
    $rank_root->appendChild($ranking_status = $rank_xml->createElement('ranking_status'));

    for ($i = $rows_lower; $i <= $rows_upper; $i++)
    {
        $rank_xml_rank_record = $rank_xml->createElement('record');
        $rank_xml_rank_record->setAttribute('id', $i);
        $rank_xml_rankscore->appendChild($rank_xml_rank_record);

        try
        {
            $columns = implode(',', array('rank',
                                          'rank2',
                                          'card_id',
                                          'player_name',
                                          'score_i1',
                                          'score_bi1',
                                          'last_play_tenpo_id',
                                          'fcol1',
                                          'fcol2',
                                          'tenpo_name',
                                          'pref_id',
                                          'pref',
                                          'area_id',
                                          'area',
                                          'title',
                                          'org_rank',
                                          'org_rank2'));
            $query = "SELECT $columns FROM $table WHERE rank=:rank";
            $sth = $dbh->prepare($query);
            $sth->bindParam(':rank', $i, PDO::PARAM_INT);
            $sth->execute();
            $hash_ref_rank_entry = $sth->fetch(PDO::FETCH_ASSOC);
            $sth = NULL;
        } catch (PDOException $e)
        {
            // Exception
            quit(ERROR_010);
        }

        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank', $hash_ref_rank_entry['rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank2', $hash_ref_rank_entry['rank2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('card_id', $hash_ref_rank_entry['card_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_i1', $hash_ref_rank_entry['score_i1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_bi1', $hash_ref_rank_entry['score_bi1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('last_play_tenpo_id', $hash_ref_rank_entry['last_play_tenpo_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('fcol1', $hash_ref_rank_entry['fcol1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('fcol2', $hash_ref_rank_entry['fcol2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('tenpo_name', $hash_ref_rank_entry['tenpo_name']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref_id', $hash_ref_rank_entry['pref_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref', $hash_ref_rank_entry['pref']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area_id', $hash_ref_rank_entry['area_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area', $hash_ref_rank_entry['area']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('title', $hash_ref_rank_entry['title']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('org_rank', $hash_ref_rank_entry['org_rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('org_rank2', $hash_ref_rank_entry['org_rank2']));
    }

    $ranking_status->appendChild($rank_xml->createElement('table_name', 'score_rank'));
    $ranking_status->appendChild($rank_xml->createElement('start_date', '2017-05-30 02:10:02'));
    $ranking_status->appendChild($rank_xml->createElement('end_date', '2018-05-30 02:10:02'));
    $ranking_status->appendChild($rank_xml->createElement('status', '1'));
    $ranking_status->appendChild($rank_xml->createElement('rows', (string)$rows_upper));

    return $rank_xml;
}

function getEventRank($rows_lower, $rows_upper)
{
    $table = "event_rank";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_2110_DATABASE;
    $dbh = NULL;

    try
    {
        // Connect
        $dbh = new PDO($dsn_ranking, MYSQL_USERNAME, MYSQL_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 0); // AutoCommit OFF
        $dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    } catch (PDOException $e)
    {
        // Exception
        //quit(ERROR_009);
    }

    $hash_ref_rank_entry = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("event_rank");
    $rank_root->appendChild($rank_xml_rankscore);
    $rank_root->appendChild($ranking_status = $rank_xml->createElement('ranking_status'));

    for ($i = $rows_lower; $i <= $rows_upper; $i++)
    {
        $rank_xml_rank_record = $rank_xml->createElement('record');
        $rank_xml_rank_record->setAttribute('id', $i);
        $rank_xml_rankscore->appendChild($rank_xml_rank_record);


        try
        {
            $columns = implode(',', array('rank',
                                          'rank2',
                                          'card_id',
                                          'player_name',
                                          'score_i1',
                                          'score_bi1',
                                          'last_play_tenpo_id',
                                          'fcol1',
                                          'fcol2',
                                          'tenpo_name',
                                          'pref_id',
                                          'pref',
                                          'area_id',
                                          'area',
                                          'title',
                                          'org_rank',
                                          'org_rank2'));
            $query = "SELECT $columns FROM $table WHERE rank=:rank";
            $sth = $dbh->prepare($query);
            $sth->bindParam(':rank', $i, PDO::PARAM_INT);
            $sth->execute();
            $hash_ref_rank_entry = $sth->fetch(PDO::FETCH_ASSOC);
            $sth = NULL;
        } catch (PDOException $e)
        {
            // Exception
            //quit(ERROR_010);
            exit;
        }
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank', $hash_ref_rank_entry['rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank2', $hash_ref_rank_entry['rank2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('card_id', $hash_ref_rank_entry['card_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_i1', $hash_ref_rank_entry['score_i1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_bi1', $hash_ref_rank_entry['score_bi1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('last_play_tenpo_id', $hash_ref_rank_entry['last_play_tenpo_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('fcol1', $hash_ref_rank_entry['fcol1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('fcol2', $hash_ref_rank_entry['fcol2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('tenpo_name', $hash_ref_rank_entry['tenpo_name']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref_id', $hash_ref_rank_entry['pref_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref', $hash_ref_rank_entry['pref']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area_id', $hash_ref_rank_entry['area_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area', $hash_ref_rank_entry['area']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('title', $hash_ref_rank_entry['title']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('org_rank', $hash_ref_rank_entry['org_rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('org_rank2', $hash_ref_rank_entry['org_rank2']));
    }

    $ranking_status->appendChild($rank_xml->createElement('table_name', 'score_rank'));
    $ranking_status->appendChild($rank_xml->createElement('start_date', '2017-05-30 02:10:02'));
    $ranking_status->appendChild($rank_xml->createElement('end_date', '2018-05-30 02:10:02'));
    $ranking_status->appendChild($rank_xml->createElement('status', '1'));
    $ranking_status->appendChild($rank_xml->createElement('rows', (string)$rows_upper));

    return $rank_xml;
}

function getPlayNumRank($rows_lower, $rows_upper)
{
    $table = "play_num_rank";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_2110_DATABASE;
    $dbh = NULL;

    try
    {
        // Connect
        $dbh = new PDO($dsn_ranking, MYSQL_USERNAME, MYSQL_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 0); // AutoCommit OFF
        $dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    } catch (PDOException $e)
    {
        // Exception
        //quit(ERROR_009);
        exit;
    }

    $hash_ref_rank_entry = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("play_num_rank");
    $rank_root->appendChild($rank_xml_rankscore);
    $rank_root->appendChild($ranking_status = $rank_xml->createElement('ranking_status'));

    for ($i = $rows_lower; $i <= $rows_upper; $i++)
    {
        $rank_xml_rank_record = $rank_xml->createElement('record');
        $rank_xml_rank_record->setAttribute('id', $i);
        $rank_xml_rankscore->appendChild($rank_xml_rank_record);

        try
        {
            $columns = implode(',', array('rank',
                                          'rank2',
                                          'pcol1',
                                          'score_bi1',
                                          'title',
                                          'artist',
                                          'org_rank',
                                          'org_rank2'));
            $query = "SELECT $columns FROM $table WHERE rank=:rank";
            $sth = $dbh->prepare($query);
            $sth->bindParam(':rank', $i, PDO::PARAM_INT);
            $sth->execute();
            $hash_ref_rank_entry = $sth->fetch(PDO::FETCH_ASSOC);
            $sth = NULL;
        } catch (PDOException $e)
        {
            // Exception
            //quit(ERROR_010);
            exit;
        }
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank', $hash_ref_rank_entry['rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank2', $hash_ref_rank_entry['rank2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('card_id', $hash_ref_rank_entry['card_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_i1', $hash_ref_rank_entry['score_i1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_bi1', $hash_ref_rank_entry['score_bi1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('last_play_tenpo_id', $hash_ref_rank_entry['last_play_tenpo_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('fcol1', $hash_ref_rank_entry['fcol1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('fcol2', $hash_ref_rank_entry['fcol2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('tenpo_name', $hash_ref_rank_entry['tenpo_name']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref_id', $hash_ref_rank_entry['pref_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref', $hash_ref_rank_entry['pref']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area_id', $hash_ref_rank_entry['area_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area', $hash_ref_rank_entry['area']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('title', $hash_ref_rank_entry['title']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('org_rank', $hash_ref_rank_entry['org_rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('org_rank2', $hash_ref_rank_entry['org_rank2']));
    }

    $ranking_status->appendChild($rank_xml->createElement('table_name', 'score_rank'));
    $ranking_status->appendChild($rank_xml->createElement('start_date', '2017-05-30 02:10:02'));
    $ranking_status->appendChild($rank_xml->createElement('end_date', '2018-05-30 02:10:02'));
    $ranking_status->appendChild($rank_xml->createElement('status', '1'));
    $ranking_status->appendChild($rank_xml->createElement('rows', (string)$rows_upper));

    return $rank_xml;
}

function checkipaddr($ip)
{
    return (preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $ip) == 0);
}

function checkmac($mac)
{
    return (preg_match('/([a-fA-F0-9]{2}){6}/', $mac) == 0);
}

function strOutput($strParam)
{
    $strParam = "1\n".$strParam;
    header("Content-Type:application/octet-stream");
    header(sprintf("Content-Length: %d", strlen($strParam)));
    print($strParam);
    exit;
}