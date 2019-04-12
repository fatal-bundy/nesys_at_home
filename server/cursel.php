<?php

/**
 * update.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 10:14 AM
 */


strOutput("1\n");
function strOutput($strParam)
{
    header("Content-Type:text/plain; Charset=EUC-JP");
    header(sprintf("Content-Length: %d", strlen($strParam)));
    print($strParam);
}

;