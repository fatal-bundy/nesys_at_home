<?php

/**
 * sec_functions.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 10:14 AM
 */

function checkipaddr($ip)
{
    return (preg_match('/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/', $ip) == 0);
}

function checkmac($mac)
{
    return (preg_match('/([A-F0-9]{2}){6}/', $mac) == 0);
}

function checkMD5($hash)
{
    return (preg_match('/[A-F0-9]{31}/', $hash) == 1);
}