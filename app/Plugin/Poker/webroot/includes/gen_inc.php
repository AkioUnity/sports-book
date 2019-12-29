<?php 
ini_set('display_errors', false);
error_reporting(0);

require('configure.php');
$host = DB_SERVER;
$ln = DB_SERVER_USERNAME;
$pw = DB_SERVER_PASSWORD;
$db = DB_DATABASE;
mysql_connect("$host", "$ln", "$pw") or die("Unable to connect to database - <a href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]install/'>Click here to install OPS V2</a>");
mysql_select_db("$db") or die("Unable to select database");
require('tables.php');
require('settings.php');

session_name("CAKEPHP");
session_start();
if ($_SESSION['Auth']['User']['username'] == null) {
    unset ($_SESSION["SGUID"]);
    unset ($_SESSION['playername']);
    $url = 'http://'.$_SERVER['SERVER_NAME'].'/eng/users/login';
    header('Location: '.$url);
    die();
}

require('poker_inc.php');
if (!isset($_SESSION['SGUID'])) {
    $time = time();
    $ip = $_SERVER['REMOTE_ADDR'];
    $_SESSION['SGUID'] = randomcode(32);
    //check if user exists. if not, create one
    $userq = mysql_query("select username from ".DB_PLAYERS." where username = '".$_SESSION['Auth']['User']['username']."' ");
    if(mysql_num_rows($userq) != 0){
        $result = mysql_query("update ".DB_PLAYERS." set ipaddress = '".$ip."', lastlogin = '".$time."' , GUID = '".$_SESSION['SGUID']."' where username = '".$_SESSION['Auth']['User']['username']."' ");
    } else {
        $result = mysql_query("insert into ".DB_PLAYERS." set banned = '0', username = '".$_SESSION['Auth']['User']['username']."', approve = '0', email = '', GUID = '".$_SESSION['SGUID']."', lastlogin = '".$time."' , datecreated = '".$time."' , password = '', sessname = '', avatar = 'avatar.jpg', ipaddress = '".$ip."' ");
        $result = mysql_query("insert into ".DB_STATS." set player = '".$_SESSION['Auth']['User']['username']."', winpot = '0' ");
    }
}

$_SESSION['playername'] = $_SESSION['Auth']['User']['username'];
$plyrname = addslashes($_SESSION['playername']);
$SGUID = addslashes($_SESSION['SGUID']);
$valid = false;
$ADMIN = false;
$gID = '';

if (($plyrname != '') && ($SGUID != '')) {
    $idq = mysql_query("select GUID, banned, gID, vID from " . DB_PLAYERS . " where username = '" . $plyrname . "' and GUID = '" . $SGUID . "' ");
    $idr = mysql_fetch_array($idq);
    $gID = $idr['gID'];
    $gameID = $idr['vID'];
    if ((mysql_num_rows($idq) == 1) && ($idr['banned'] != 1)) $valid = true;
    $siteadmin = ADMIN_USERS;
    if ($plyrname != '') {
        $time = time();
        $admins = array();
        $adminraw = explode(',', $siteadmin);
        $i = 0;
        while ($adminraw[$i] != '') {
            $admins[$i] = $adminraw[$i];
            $i++;
        } 
        if (in_array($plyrname, $admins)) $ADMIN = true;
    } 
}
require('language.php');
if (($_SESSION[SESSNAME] != '') && (MEMMOD == 1) && ($plyrname == '')) {
    $time = time();
    $sessname = addslashes($_SESSION[SESSNAME]);
    $usrq = mysql_query("select username from " . DB_PLAYERS . " where sessname = '" . $sessname . "' ");
    if (mysql_num_rows($usrq) == 1) {
        $usrr = mysql_fetch_array($usrq);
        $usr = $usrr['username'];
        $GUID = randomcode(32);
        $_SESSION['playername'] = $usr;
        $_SESSION['SGUID'] = $GUID;
        $ip = $_SERVER['REMOTE_ADDR'];
        $result = mysql_query("update " . DB_PLAYERS . " set ipaddress = '" . $ip . "', lastlogin = '" . $time . "' , GUID = '" . $GUID . "' where username = '" . $usr . "' ");
        $valid = true;
    } 
}
$time = time();
$tq = mysql_query("select waitimer from " . DB_PLAYERS . " where username = '" . $plyrname . "' ");
$tr = mysql_fetch_array($tq);
$waitimer = $tr['waitimer'];
//if ($waitimer > $time) header('Location sitout.php');
?>