<?php
require('configure.php');
$host = DB_SERVER;
$ln   = DB_SERVER_USERNAME;
$pw   = DB_SERVER_PASSWORD;
$db   = DB_DATABASE;
mysql_connect("$host", "$ln", "$pw") or die("Unable to connect to database");
mysql_select_db("$db") or die("Unable to select database");
require('tables.php');
require('settings.php');
session_name("CAKEPHP");
session_start();
$plyrname = addslashes($_SESSION['playername']);
$SGUID    = addslashes($_SESSION['SGUID']);
if (($plyrname == '') || ($SGUID == ''))
    header('Location: login.php');

$valid  = false;
$gameID = '';
$gID    = '';
$idq    = mysql_query("select GUID, vID, gID,banned from " . DB_PLAYERS . " where username = '" . $plyrname . "' and GUID = '" . $SGUID . "' ");
$idr    = mysql_fetch_array($idq);
if ((mysql_num_rows($idq) == 1) && ($idr['banned'] != 1)) {
    $valid  = true;
    $gameID = $idr['vID'];
    $gID    = $idr['gID'];
}
if (($valid == false) || ($gameID == '')) {
    die();
}
require('poker_inc.php');
require('language.php');
?>