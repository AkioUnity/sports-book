<?php
if ($valid == false)
    header('Location: login.php');
$gameID = addslashes($_GET['gameID']);
if ($gameID != '') {
    $gq = mysql_query("select gameID from " . DB_POKER . " where gameID = '" . $gameID . "' ");
    if (mysql_num_rows($gq) == 1) {
        $result = mysql_query("update " . DB_PLAYERS . " set vID = '" . $gameID . "' where username = '" . $plyrname . "' ");
        header('Location: poker.php');
    }
}
?>