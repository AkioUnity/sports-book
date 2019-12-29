<?php
require('sec_inc.php');
$ip = $_SERVER['REMOTE_ADDR'];
if (IPCHECK == 1) {
    $ipq = mysql_query("select ipaddress from " . DB_PLAYERS . " where ipaddress = '" . $ip . "' and gID = '" . $gameID . "' ");
    if (mysql_num_rows($ipq) > 0) {
        die();
    }
}
$time   = time();
$action = addslashes($_GET['action']);
if (($action > 0) && ($action < 11)) {
    $zq = mysql_query("select gameID from " . DB_POKER . " where gameID = '" . $gameID . "' and( p1name = '" . $plyrname . "' or p2name = '" . $plyrname . "' or p3name = '" . $plyrname . "' or p4name = '" . $plyrname . "' or p5name = '" . $plyrname . "' or p6name = '" . $plyrname . "' or p7name = '" . $plyrname . "' or p8name = '" . $plyrname . "' or p9name = '" . $plyrname . "' or p10name = '" . $plyrname . "' ) ");
    if (mysql_num_rows($zq) == 1)
        die();
    $cq = mysql_query("select p" . $action . "name, tablelimit, tablelow, tabletype, hand from " . DB_POKER . " where gameID = '" . $gameID . "' and p" . $action . "name = ''  ");
    if (mysql_num_rows($cq) == 1) {
        $cr         = mysql_fetch_array($cq);
        $statsq     = mysql_query("select balance from " . DB_USERS . " where username = '" . $plyrname . "' ");
        $statsr     = mysql_fetch_array($statsq);
        $winnings   = floatval($statsr['balance'])*1000;
        $tablelow   = $cr['tablelow'];
        $tablelimit = $cr['tablelimit'];
        $tabletype  = $cr['tabletype'];
        $hand       = $cr['hand'];
        if (($tabletype == 't') && ($hand != ''))
            die();
        $stake = (($winnings > $tablelimit) ? $tablelimit : $winnings);
        if ($tabletype == 't')
            $tablelow = $tablelimit;
        if (($stake >= $tablelow) && ($stake > 0)) {
            $result = mysql_query("update " . DB_POKER . " set p" . $action . "name = '" . $plyrname . "', p" . $action . "bet = 'F', p" . $action . "pot = '" . $stake . "' where gameID = '" . $gameID . "' ");
            $bank   = $winnings - $stake;
            if ($tabletype == 't') {
                $result = mysql_query("update " . DB_STATS . " set tournamentsplayed = tournamentsplayed + 1 where player  = '" . $plyrname . "' ");
                $result = mysql_query("update " . DB_USERS . " set balance = '" . ($bank/1000) . "' where username  = '" . $plyrname . "' ");
            } else {
                $result = mysql_query("update " . DB_STATS . " set gamesplayed = gamesplayed + 1 where player  = '" . $plyrname . "' ");
                $result = mysql_query("update " . DB_USERS . " set balance = '" . ($bank/1000) . "' where username  = '" . $plyrname . "' ");
            }
            $result = mysql_query("update " . DB_PLAYERS . " set gID = '" . $gameID . "', lastmove = '" . ($time + 3) . "', timetag = '" . ($time + 3) . "'  where username  = '" . $plyrname . "' ");
            sys_msg($plyrname . ' ' . GAME_PLAYER_BUYS_IN . ' ' . money($stake), $gameID);
?>
document.getElementById('pava<?php
            echo $action;
?>').innerHTML = "<?php
            echo GAME_LOADING;
?>";
<?php
        } else {
?>
<?php
            if ($tabletype == 't') {
?>
alert('<?php
                echo INSUFFICIENT_BANKROLL_TOURNAMENT;
?>');
<?php
            } else {
?>
alert('<?php
                echo INSUFFICIENT_BANKROLL_SITNGO;
?>');
<?php
            }
?>
<?php
        }
    }
}
$result = mysql_query("update " . DB_POKER . " set lastmove = '" . ($time + 2) . "'  where gameID = '" . $gameID . "' ");
?>