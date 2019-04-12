<?php
/**
 * 3032/functions.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 8:28 AM
 */
require("../../config/config.inc");


function get_vp_rank($rows_lower, $rows_upper)
{
    $table = "vp_rank";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_3032_DATABASE;
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
        print($e);
    }

    $hash_ref_rank_entry = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("vp_rank");
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
                                          'score_ui1',
                                          'score_ui2',
                                          'score_ui3',
                                          'score_ui4',
                                          'score_ui5',
                                          'score_ui6',
                                          'score_bi1',
                                          'last_play_tenpo_id',
                                          'tenpo_name',
                                          'pref_id',
                                          'pref',
                                          'area_id',
                                          'area',
                                          'guild_id',
                                          'guild_name',
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
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui1', $hash_ref_rank_entry['score_ui1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui2', $hash_ref_rank_entry['score_ui2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui3', $hash_ref_rank_entry['score_ui3']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui4', $hash_ref_rank_entry['score_ui4']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui5', $hash_ref_rank_entry['score_ui5']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui6', $hash_ref_rank_entry['score_ui6']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_bi1', $hash_ref_rank_entry['score_bi1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('last_play_tenpo_id', $hash_ref_rank_entry['last_play_tenpo_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('tenpo_name', $hash_ref_rank_entry['tenpo_name']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref_id', $hash_ref_rank_entry['pref_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref', $hash_ref_rank_entry['pref']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area_id', $hash_ref_rank_entry['area_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area', $hash_ref_rank_entry['area']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_id', $hash_ref_rank_entry['guild_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_name', $hash_ref_rank_entry['guild_name']));
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

function get_m_vp_rank($rows_lower, $rows_upper)
{
    $table = "m_vp_rank";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_3032_DATABASE;
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
        print($e);
    }

    $hash_ref_rank_entry = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("m_vp_rank");
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
                                          'score_ui1',
                                          'score_ui2',
                                          'score_ui3',
                                          'score_ui4',
                                          'score_ui5',
                                          'score_ui6',
                                          'score_bi1',
                                          'last_play_tenpo_id',
                                          'tenpo_name',
                                          'pref_id',
                                          'pref',
                                          'area_id',
                                          'area',
                                          'guild_id',
                                          'guild_name',
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
            print($e);
        }

        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank', $hash_ref_rank_entry['rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank2', $hash_ref_rank_entry['rank2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('card_id', $hash_ref_rank_entry['card_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_i1', $hash_ref_rank_entry['score_i1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui1', $hash_ref_rank_entry['score_ui1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui2', $hash_ref_rank_entry['score_ui2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui3', $hash_ref_rank_entry['score_ui3']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui4', $hash_ref_rank_entry['score_ui4']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui5', $hash_ref_rank_entry['score_ui5']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui6', $hash_ref_rank_entry['score_ui6']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_bi1', $hash_ref_rank_entry['score_bi1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('last_play_tenpo_id', $hash_ref_rank_entry['last_play_tenpo_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('tenpo_name', $hash_ref_rank_entry['tenpo_name']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref_id', $hash_ref_rank_entry['pref_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref', $hash_ref_rank_entry['pref']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area_id', $hash_ref_rank_entry['area_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area', $hash_ref_rank_entry['area']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_id', $hash_ref_rank_entry['guild_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_name', $hash_ref_rank_entry['guild_name']));
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

function get_g_pcol1_score_rank($rows_lower, $rows_upper)
{
    $table = "g_pcol1_score_rank";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_3032_DATABASE;
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
        print($e);
    }

    $hash_ref_rank_entry = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("g_pcol1_score_rank");
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
                                          'score_ui1',
                                          'score_ui2',
                                          'score_ui3',
                                          'score_ui4',
                                          'score_ui5',
                                          'score_ui6',
                                          'score_bi1',
                                          'last_play_tenpo_id',
                                          'tenpo_name',
                                          'pref_id',
                                          'pref',
                                          'area_id',
                                          'area',
                                          'guild_id',
                                          'guild_name',
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
            print($e);
        }

        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank', $hash_ref_rank_entry['rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank2', $hash_ref_rank_entry['rank2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('card_id', $hash_ref_rank_entry['card_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_i1', $hash_ref_rank_entry['score_i1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui1', $hash_ref_rank_entry['score_ui1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui2', $hash_ref_rank_entry['score_ui2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui3', $hash_ref_rank_entry['score_ui3']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui4', $hash_ref_rank_entry['score_ui4']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui5', $hash_ref_rank_entry['score_ui5']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui6', $hash_ref_rank_entry['score_ui6']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_bi1', $hash_ref_rank_entry['score_bi1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('last_play_tenpo_id', $hash_ref_rank_entry['last_play_tenpo_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('tenpo_name', $hash_ref_rank_entry['tenpo_name']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref_id', $hash_ref_rank_entry['pref_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref', $hash_ref_rank_entry['pref']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area_id', $hash_ref_rank_entry['area_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area', $hash_ref_rank_entry['area']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_id', $hash_ref_rank_entry['guild_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_name', $hash_ref_rank_entry['guild_name']));
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

function get_m_g_pcol1_score_rank($rows_lower, $rows_upper)
{
    $table = "m_g_pcol1_score_rank";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_3032_DATABASE;
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
        print($e);
    }

    $hash_ref_rank_entry = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("m_g_pcol1_score_rank");
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
                                          'score_ui1',
                                          'score_ui2',
                                          'score_ui3',
                                          'score_ui4',
                                          'score_ui5',
                                          'score_ui6',
                                          'score_bi1',
                                          'last_play_tenpo_id',
                                          'tenpo_name',
                                          'pref_id',
                                          'pref',
                                          'area_id',
                                          'area',
                                          'guild_id',
                                          'guild_name',
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
            print($e);
        }

        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank', $hash_ref_rank_entry['rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank2', $hash_ref_rank_entry['rank2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('card_id', $hash_ref_rank_entry['card_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_i1', $hash_ref_rank_entry['score_i1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui1', $hash_ref_rank_entry['score_ui1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui2', $hash_ref_rank_entry['score_ui2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui3', $hash_ref_rank_entry['score_ui3']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui4', $hash_ref_rank_entry['score_ui4']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui5', $hash_ref_rank_entry['score_ui5']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui6', $hash_ref_rank_entry['score_ui6']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_bi1', $hash_ref_rank_entry['score_bi1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('last_play_tenpo_id', $hash_ref_rank_entry['last_play_tenpo_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('tenpo_name', $hash_ref_rank_entry['tenpo_name']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref_id', $hash_ref_rank_entry['pref_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref', $hash_ref_rank_entry['pref']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area_id', $hash_ref_rank_entry['area_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area', $hash_ref_rank_entry['area']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_id', $hash_ref_rank_entry['guild_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_name', $hash_ref_rank_entry['guild_name']));
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

function get_pcol1_score_rank($rows_lower, $rows_upper)
{
    $table = "pcol1_score_rank";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_3032_DATABASE;
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
        print($e);
    }

    $hash_ref_rank_entry = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("pcol1_score_rank");
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
                                          'score_ui1',
                                          'score_ui2',
                                          'score_ui3',
                                          'score_ui4',
                                          'score_ui5',
                                          'score_ui6',
                                          'score_bi1',
                                          'last_play_tenpo_id',
                                          'tenpo_name',
                                          'pref_id',
                                          'pref',
                                          'area_id',
                                          'area',
                                          'guild_id',
                                          'guild_name',
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
            print($e);
        }

        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank', $hash_ref_rank_entry['rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank2', $hash_ref_rank_entry['rank2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('card_id', $hash_ref_rank_entry['card_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_i1', $hash_ref_rank_entry['score_i1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui1', $hash_ref_rank_entry['score_ui1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui2', $hash_ref_rank_entry['score_ui2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui3', $hash_ref_rank_entry['score_ui3']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui4', $hash_ref_rank_entry['score_ui4']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui5', $hash_ref_rank_entry['score_ui5']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui6', $hash_ref_rank_entry['score_ui6']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_bi1', $hash_ref_rank_entry['score_bi1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('last_play_tenpo_id', $hash_ref_rank_entry['last_play_tenpo_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('tenpo_name', $hash_ref_rank_entry['tenpo_name']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref_id', $hash_ref_rank_entry['pref_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref', $hash_ref_rank_entry['pref']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area_id', $hash_ref_rank_entry['area_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area', $hash_ref_rank_entry['area']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_id', $hash_ref_rank_entry['guild_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_name', $hash_ref_rank_entry['guild_name']));
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

function get_m_pcol1_score_rank($rows_lower, $rows_upper)
{
    $table = "m_pcol1_score_rank";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_3032_DATABASE;
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
        print($e);
    }

    $hash_ref_rank_entry = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("m_pcol1_score_rank");
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
                                          'score_ui1',
                                          'score_ui2',
                                          'score_ui3',
                                          'score_ui4',
                                          'score_ui5',
                                          'score_ui6',
                                          'score_bi1',
                                          'last_play_tenpo_id',
                                          'tenpo_name',
                                          'pref_id',
                                          'pref',
                                          'area_id',
                                          'area',
                                          'guild_id',
                                          'guild_name',
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
            print($e);
        }

        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank', $hash_ref_rank_entry['rank']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('rank2', $hash_ref_rank_entry['rank2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('card_id', $hash_ref_rank_entry['card_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_i1', $hash_ref_rank_entry['score_i1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui1', $hash_ref_rank_entry['score_ui1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui2', $hash_ref_rank_entry['score_ui2']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui3', $hash_ref_rank_entry['score_ui3']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui4', $hash_ref_rank_entry['score_ui4']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui5', $hash_ref_rank_entry['score_ui5']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_ui6', $hash_ref_rank_entry['score_ui6']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('score_bi1', $hash_ref_rank_entry['score_bi1']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('last_play_tenpo_id', $hash_ref_rank_entry['last_play_tenpo_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('tenpo_name', $hash_ref_rank_entry['tenpo_name']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref_id', $hash_ref_rank_entry['pref_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('pref', $hash_ref_rank_entry['pref']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area_id', $hash_ref_rank_entry['area_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('area', $hash_ref_rank_entry['area']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_id', $hash_ref_rank_entry['guild_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('guild_name', $hash_ref_rank_entry['guild_name']));
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


