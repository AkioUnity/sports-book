<?php
require('sec_inc.php');
if (($gID == '') || ($gID == 0))
    die();
$chtq = mysql_query("select * from " . DB_LIVECHAT . " where gameID = '" . $gameID . "' ");
$chtr = mysql_fetch_array($chtq);
$time = time();
$time += 2;
$c2  = addslashes($chtr['c2']);
$c3  = addslashes($chtr['c3']);
$c4  = addslashes($chtr['c4']);
$c5  = addslashes($chtr['c5']);
$msg = strip_tags($_GET['msg']);
$msg = preg_replace('/([^\s]{16})(?=[^\s])/m', '$1 ', $msg);
$msg = substr($msg, 0, 100);
if (strlen($msg) > 0) {
    $msg  = '<b><font color="#CCFFFF">' . $plyrname . ':</font></b> ' . $msg . '<br>';
    $msg  = addslashes($msg);
    $chtq = mysql_query("select * from " . DB_LIVECHAT . " where gameID = '" . $gID . "' ");
    if (mysql_num_rows($chtq) > 0) {
        $result = mysql_query("update " . DB_LIVECHAT . " set updatescreen = '" . $time . "', c1 = '" . $c2 . "', c2 = '" . $c3 . "', c3 = '" . $c4 . "', c4 = '" . $c5 . "', c5  = '" . $msg . "' where gameID = '" . $gID . "' ");
    } else {
        $result = mysql_query("insert into " . DB_LIVECHAT . " set updatescreen = '" . $time . "', c5 = '" . $msg . "', gameID = '" . $gID . "' ");
    }
}
?>