<?php
header('Location: lobby.php');
die();
require('includes/configure.php');
$host=DB_SERVER;
$ln=DB_SERVER_USERNAME;
$pw=DB_SERVER_PASSWORD;
$db=DB_DATABASE;
		mysql_connect("$host","$ln","$pw") or die("Unable to connect to database");
		mysql_select_db("$db") or die("Unable to select database");

$code = addslashes($_GET['approval']);
if($code == '') die();
$result = mysql_query("update ".DB_PLAYERS." set approve = '0' where code = '".$code."' ");
 $url = 'login.php';
 echo '<SCRIPT LANGUAGE="JavaScript">';
echo 'parent.document.location.href = "'.$url.'"'; 
echo '</SCRIPT>';
die();
?>