<?php
/**
 * 2110/card.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 12/12/2018
 * Time: 8:21 PM
 */

function card($card_id)
{
    $table = "card_main";
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
        //quit(ERROR_009);
    }

    $hash_ref_rank_entry = NULL;
    $card_xml = new DOMDocument('1.0', 'UTF-8');
    $card_xml->formatOutput = true;
    $card_root = $card_xml->createElement("root");
    $card_xml->appendChild($card_root);
    $card_xml_main = $card_xml->createElement("card");
    $card_root->appendChild($card_xml_main);
    try
    {
        $columns = implode(',', array('card_id',
                                      'player_name',
                                      'score_i1',
                                      'fcol1',
                                      'fcol2',
                                      'fcol3',
                                      'achieve_status',
                                      'created',
                                      'updated',));
        $query = "SELECT $columns FROM $table WHERE card_id=:card_id";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':card_id', $card_id, PDO::PARAM_INT);
        $sth->execute();
        $hash_ref_rank_entry = $sth->fetch(PDO::FETCH_ASSOC);
        $sth = NULL;
    } catch (PDOException $e)
    {
        // Exception
        quit(ERROR_010);
    }
    $card_xml_main->appendChild($card_xml->createElement('card_id', $hash_ref_rank_entry['card_id']));
    $card_xml_main->appendChild($card_xml->createElement('player_name', $hash_ref_rank_entry['player_name']));
    $card_xml_main->appendChild($card_xml->createElement('score_i1', $hash_ref_rank_entry['score_i1']));
    $card_xml_main->appendChild($card_xml->createElement('achieve_status', $hash_ref_rank_entry['achieve_status']));
    $card_xml_main->appendChild($card_xml->createElement('fcol1', $hash_ref_rank_entry['fcol1']));
    $card_xml_main->appendChild($card_xml->createElement('fcol2', $hash_ref_rank_entry['fcol2']));
    $card_xml_main->appendChild($card_xml->createElement('fcol3', $hash_ref_rank_entry['fcol3']));
    $card_xml_main->appendChild($card_xml->createElement('created', $hash_ref_rank_entry['created']));
    $card_xml_main->appendChild($card_xml->createElement('updated', $hash_ref_rank_entry['updated']));


    return $card_xml;
}

function card_writer($card_id, $data)
{
    $xml = simplexml_load_string($data);
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);
    $player_name = $array["data"]["player_name"];
    $achieve_status =  $array["data"]["achieve_status"];
    $fcol1 =  $array["data"]["fcol1"];
    $fcol2 =  $array["data"]["fcol2"];
    $fcol3 =  $array["data"]["fcol3"];

    $table = "card_main";
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
        //quit(ERROR_009);
    }

    $hash_ref_rank_entry = NULL;
    $card_xml = new DOMDocument('1.0', 'UTF-8');
    $card_xml->formatOutput = true;
    $card_root = $card_xml->createElement("root");
    $card_xml->appendChild($card_root);
    $card_xml_main = $card_xml->createElement("card");
    $card_root->appendChild($card_xml_main);
    try
    {
        $columns = implode(',', array('card_id',
                                      'player_name',
                                      'score_i1',
                                      'fcol1',
                                      'fcol2',
                                      'fcol3',));
        $query = "INSERT INTO $table ($columns)
    VALUES (?,?,?,?,?,?)";
        $sth = $dbh->prepare($query);
        $sth->bindParam(':card_id', $card_id, PDO::PARAM_INT);
        $sth->bindParam(':player_name', $player_name, PDO::PARAM_STR);
        $sth->bindParam(':score_i1', $score_i1, PDO::PARAM_INT);
        $sth->bindParam(':fcol1', $fcol1, PDO::PARAM_INT);
        $sth->bindParam(':fcol2', $fcol2, PDO::PARAM_INT);
        $sth->bindParam(':fcol3', $fcol3, PDO::PARAM_INT);

        $sth->execute();
        $sth = NULL;
    } catch (PDOException $e)
    {
        // Exception
        echo "Error updating record: " . $sth->error;
    }
}