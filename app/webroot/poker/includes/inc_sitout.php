<?php
$time     = time();
$tq       = mysql_query("select waitimer from " . DB_PLAYERS . " where username = '" . $plyrname . "' ");
$tr       = mysql_fetch_array($tq);
$waitimer = $tr['waitimer'];
if (($waitimer - 1) <= $time) {
    header('Location: lobby.php');
    die();
}
$start = $waitimer - $time;
?>