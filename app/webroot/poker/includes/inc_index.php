<?php
if (($action == 'logout') && ($plyrname != '')) {
    unset($_SESSION['playername']);
    unset($_SESSION['SGUID']);
    header('Location: index.php');
}
?>