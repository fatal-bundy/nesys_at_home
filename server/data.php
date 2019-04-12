<?php
$strParam = implode("\n", array(
    sprintf("count=1"),
    sprintf("nexttime=0\n"),
));
header("Content-Type:text/plain; Charset=EUC-JP");
header(sprintf("Content-Length: %d", strlen($strParam)));
print($strParam);