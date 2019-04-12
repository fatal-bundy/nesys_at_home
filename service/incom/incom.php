<?php

/**
 * incom.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 10:14 AM
 */


$strBuffer = '1+1';
header("Content-Type: text/plain");
header(sprintf("Content-Length: %d", strlen($strBuffer)));
print($strBuffer);