<?php
header('Location: lobby.php');
die();
session_start();
session_unset();
session_destroy();
header ("Location: index.php");
?>