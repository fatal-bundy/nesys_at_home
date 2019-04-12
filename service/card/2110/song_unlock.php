<?php
/**
 * 2110/song_unlock.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 9:06 PM
 */

function getSongUnlock()
{
    $table = "music_unlock";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_2110_CARD;
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

    $hash_ref_rank_entry_all = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("music");
    $rank_root->appendChild($rank_xml_rankscore);


    try
    {
        $columns = implode(',', array('music_id',
                                      'title',
                                      'artist',
                                      'release_date',
                                      'end_date',
                                      'new_flag',
                                      'use_flag'));
        $query = "SELECT $columns FROM $table";
        $sth = $dbh->prepare($query);
        //$sth->bindParam(':music_id', $i, PDO::PARAM_INT);
        $sth->execute();
        $hash_ref_rank_entry_all = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth = NULL;
    } catch (PDOException $e)
    {
        // Exception
        print($e);
    }

    //$rows_total = $hash_ref_rank_entry_all[0];
    //echo $rows_total;
    $i = 1;
    foreach ($hash_ref_rank_entry_all as $hash_ref_rank_entry)
    {
        $rank_xml_rank_record = $rank_xml->createElement('record');
        $rank_xml_rank_record->setAttribute('id', $i);
        $rank_xml_rankscore->appendChild($rank_xml_rank_record);


        $rank_xml_rank_record->appendChild($rank_xml->createElement('music_id', $hash_ref_rank_entry['music_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('title', $hash_ref_rank_entry['title']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('artist', $hash_ref_rank_entry['artist']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('release_date', $hash_ref_rank_entry['release_date']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('end_date', $hash_ref_rank_entry['end_date']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('new_flag', $hash_ref_rank_entry['new_flag']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('use_flag', $hash_ref_rank_entry['use_flag']));
        $i++;
    }

    return $rank_xml;
}

function getMusicExtra()
{
    $table = "music_extra";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_2110_CARD;
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

    $hash_ref_rank_entry_all = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("music_extra");
    $rank_root->appendChild($rank_xml_rankscore);


    try
    {
        $columns = implode(',', array('music_id',
                                      'release_date',
                                      'use_flag'));
        $query = "SELECT $columns FROM $table";
        $sth = $dbh->prepare($query);
        //$sth->bindParam(':music_id', $i, PDO::PARAM_INT);
        $sth->execute();
        $hash_ref_rank_entry_all = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth = NULL;
    } catch (PDOException $e)
    {
        // Exception
        print($e);
    }

    //$rows_total = $hash_ref_rank_entry_all[0];
    //echo $rows_total;
    $i = 1;
    foreach ($hash_ref_rank_entry_all as $hash_ref_rank_entry)
    {
        $rank_xml_rank_record = $rank_xml->createElement('record');
        $rank_xml_rank_record->setAttribute('id', $i);
        $rank_xml_rankscore->appendChild($rank_xml_rank_record);


        $rank_xml_rank_record->appendChild($rank_xml->createElement('music_id', $hash_ref_rank_entry['music_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('release_date', $hash_ref_rank_entry['release_date']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('use_flag', $hash_ref_rank_entry['use_flag']));
        $i++;
    }

    return $rank_xml;
}

function getMusicAou()
{
    $table = "music_aou";
    $dsn_ranking = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . RANKING_2110_CARD;
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

    $hash_ref_rank_entry_all = NULL;
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("music_aou");
    $rank_root->appendChild($rank_xml_rankscore);


    try
    {
        $columns = implode(',', array('music_id',
                                      'release_date',
                                      'use_flag'));
        $query = "SELECT $columns FROM $table";
        $sth = $dbh->prepare($query);
        //$sth->bindParam(':music_id', $i, PDO::PARAM_INT);
        $sth->execute();
        $hash_ref_rank_entry_all = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth = NULL;
    } catch (PDOException $e)
    {
        // Exception
        print($e);
    }

    //$rows_total = $hash_ref_rank_entry_all[0];
    //echo $rows_total;
    $i = 1;
    foreach ($hash_ref_rank_entry_all as $hash_ref_rank_entry)
    {
        $rank_xml_rank_record = $rank_xml->createElement('record');
        $rank_xml_rank_record->setAttribute('id', $i);
        $rank_xml_rankscore->appendChild($rank_xml_rank_record);


        $rank_xml_rank_record->appendChild($rank_xml->createElement('music_id', $hash_ref_rank_entry['music_id']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('release_date', $hash_ref_rank_entry['release_date']));
        $rank_xml_rank_record->appendChild($rank_xml->createElement('use_flag', $hash_ref_rank_entry['use_flag']));
        $i++;
    }

    return $rank_xml;
}

function getSession()
{
    $rank_xml = new DOMDocument('1.0', 'UTF-8');
    $rank_xml->formatOutput = true;
    $rank_root = $rank_xml->createElement("root");
    $rank_xml->appendChild($rank_root);
    $rank_xml_rankscore = $rank_xml->createElement("session");
    $rank_root->appendChild($rank_xml_rankscore);
    return $rank_xml;
}