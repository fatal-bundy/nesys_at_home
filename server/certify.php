<?php

/**
 * certify.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 10:14 AM
 */


error_reporting(E_ALL);

require("../config/config.inc");

define("ERROR_001", 1); // database read error, could not get owner_if and/or ranking_addr
define("ERROR_002", 2); // hashing wrong
define("ERROR_003", 3); //
define("ERROR_004", 4); //
define("ERROR_005", 5); //
define("ERROR_006", 6); //
define("ERROR_007", 7); // Invalid mac address
define("ERROR_008", 8); // Invalid Hash
define("ERROR_009", 9); //
define("ERROR_010", 10); //
define("ERROR_011", 11); //
define("ERROR_012", 12); //
define("ERROR_013", 13); //
define("ERROR_014", 14); //
define("ERROR_015", 15); //
define("ERROR_016", 16); //
define("ERROR_017", 17); //
define("ERROR_020", 20); //
define("ERROR_021", 21); //
define("ERROR_022", 22); //


function ismacvalid($mac)
{
    return (preg_match('/([a-fA-F0-9]{2}){6}/', $mac) == 1);
}

function ismd5valid($hash)
{
    return (preg_match('/[a-fA-F0-9]{31}/', $hash) == 1);
}

$ip = $_SERVER["REMOTE_ADDR"];
$gid = isset($_GET["gid"]) ? intval($_GET["gid"]) : quit(ERROR_003);
$mac = isset($_GET["mac"]) ? ($_GET["mac"]) : quit(ERROR_004);
$r = isset($_GET["r"]) ? intval($_GET["r"]) : quit(ERROR_005); // random number from service
$md = isset($_GET["md"]) ? $_GET["md"] : quit(ERROR_006); // hash of mac, random number and a key

if (!ismacvalid($mac)) quit(ERROR_007);
if (!ismd5valid($md)) quit(ERROR_008);


$hash_to_compare = md5("20040826" . (string)$r . $mac);
/*if ($hash_to_compare != $md)
{
    quit(ERROR_002);
};*/

$username = MYSQL_USERNAME;
$password = MYSQL_PASSWORD;
$hostname = MYSQL_HOSTNAME;
$port = MYSQL_PORT;
$service_name = MYSQL_SERVICE_NAME;
$connection_string = CERT_DATABASE;
$dsn_cert = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . CERT_DATABASE;
$dsn_nesys = 'mysql:host=' . MYSQL_HOSTNAME . ';dbname=' . NESYS_DATABASE;

// cert database. Get the user_id and username which will translate to the tenpo(store) id and tenpo name

$dbh = NULL;
try
{
    // Connect
    $dbh = new PDO($dsn_cert, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 0); // AutoCommit OFF
    $dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
} catch (PDOException $e)
{
    // Exception
    quit(ERROR_009);
}

$hash_ref_cert = NULL;
try
{
    $columns = implode(',', array('owner_id', 'username'));
    $query = "SELECT $columns FROM Accounts WHERE mac_addr=:mac_addr";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':mac_addr', $mac, PDO::PARAM_INT);
    $sth->execute();
    $hash_ref_cert = $sth->fetch(PDO::FETCH_ASSOC);
    $sth = NULL;
} catch (PDOException $e)
{
    // Exception
    quit(ERROR_010);
}

// DB to game configs like ranking and new addresses

$dbh = NULL;
try
{
    // Connect
    $dbh = new PDO($dsn_nesys, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_AUTOCOMMIT, 0); // AutoCommit OFF
    $dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
} catch (PDOException $e)
{
    // Exception
    quit(ERROR_011);
}
$hash_ref_nesys = NULL;
try
{
    $columns = implode(',', array('ranking_addr', 'news_addr', 'host_array'));
    $query = "SELECT $columns FROM config_public WHERE game_id=:gid";
    $sth = $dbh->prepare($query);
    $sth->bindParam(':gid', $gid, PDO::PARAM_INT);
    $sth->execute();
    $hash_ref_nesys = $sth->fetch(PDO::FETCH_ASSOC);
    $sth = NULL;
} catch (PDOException $e)
{
    // Exception
    quit(ERROR_012);
}

// Put it all together to be sent back to the service.

if (isset($hash_ref_cert['owner_id']) && isset($hash_ref_nesys['ranking_addr']))
{
    $host = $hash_ref_nesys['host_array'];
    $id = $hash_ref_cert['owner_id'];
    $t_name = $hash_ref_cert['username'];
    $t_pref = "nesys";
    $t_city = "@home";
    $n_time = 15;
    $n_url = $hash_ref_nesys['news_addr'];
    $strRankServer = $hash_ref_nesys['ranking_addr'];
    $ticket = md5($gid);

    $ans = implode("\n", array(//sprintf("remote=%s", $host),
                               sprintf("host=%s", $host),
                               sprintf("no=%d", $id),
                               sprintf("name=%s", $t_name),
                               sprintf("pref=%s", $t_pref),
                               sprintf("addr=%s", $t_pref . $t_city),
                               sprintf("x-next-time=%d", $n_time),
                               sprintf("x-img=%s", $n_url),
                               sprintf("x-ranking=%s", $strRankServer),
                               sprintf("ticket=%s", $ticket),));
    certifyOutput($ans);
} else
{
    quit(ERROR_001);
}

exit;

function certifyOutput($strParam)
{
    header("Content-Type:text/plain; Charset=Shift_JIS");
    header(sprintf("Content-Length: %d", strlen($strParam)));
    print($strParam);
}

function certifyErrorOutput($strParam)
{
    header("Content-Type:text/plain; Charset=Shift_JIS");
    header(sprintf("Content-Length: %d", strlen($strParam)));
    print($strParam);
    exit;
}

function quit($error)
{
    certifyErrorOutput(sprintf("error=%d\n", $error));
    return TRUE;
}
