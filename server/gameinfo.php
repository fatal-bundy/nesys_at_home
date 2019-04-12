<?php

/**
 * update.php of Nesys_at_Home.
 * User: Al_Bundy@breakingcades.info
 * Date: 8/24/2017
 * Time: 10:14 AM
 */


$ans = implode("\n", array(sprintf("0"),
                           sprintf("3"),
                           sprintf("301000,現在人気No1              <=ゲームの説明"),
                           sprintf("302000,大幅バージョンアップ"),
                           sprintf("303000,イベント開催中"),));

certifyOutput($ans);
function certifyOutput($strParam)
{
    header("Content-Type:text/plain; Charset=EUC-JP");
    header(sprintf("Content-Length: %d", strlen($strParam)));
    print($strParam);
}
