<?php
require('sec_inc.php');
if (($gID == '') || ($gID == 0))
    die();
$action           = addslashes($_GET['action']);
$actions_array    = array(
    'check',
    'call',
    '50',
    '100',
    '150',
    '250',
    '500',
    '1000',
    '2500',
    '5000',
    '10000',
    '25000',
    '50000',
    '100000',
    '250000',
    '500000',
    'allin',
    'fold',
    'start'
);
$betactions_array = array(
    'check',
    'call',
    '50',
    '100',
    '150',
    '250',
    '500',
    '1000',
    '2500',
    '5000',
    '10000',
    '25000',
    '50000',
    '100000',
    '250000',
    '500000',
    'allin',
    'fold'
);
if (!in_array($action, $actions_array)) {
    die();
}
$isbet = false;
if (in_array($action, $betactions_array))
    $isbet = true;
$time   = time();
$tpq    = mysql_query("select p1name, p2name, p3name, p4name, p5name, p6name, p7name, p8name, p9name, p10name, p1pot, p2pot, p3pot, p4pot, p5pot, p6pot, p7pot, p8pot, p9pot, p10pot, p1bet, p2bet, p3bet, p4bet, p5bet, p6bet, p7bet, p8bet, p9bet,p10bet, hand , move, pot, bet, tablelimit, lastbet, dealer, lastmove from " . DB_POKER . " where gameID = '" . $gameID . "' ");
$tpr    = mysql_fetch_array($tpq);
$player = getplayerid($plyrname);
if ($player == '')
    die();
$numplayers = get_num_players();
if (($hand == '') && ($numplayers > 1) && ($action == 'start')) {
    $msg    = GAME_STARTING;
    $result = mysql_query("update " . DB_POKER . " set  hand = '0', msg = '" . $msg . "' , move = '" . $player . "', dealer = '" . $player . "' where gameID = '" . $gameID . "' and hand = '' ");
    $result = mysql_query("update " . DB_PLAYERS . " set  lastmove = '" . ($time + 1) . "' where username = '" . $plyrname . "' ");
    die();
}
$tomove = $tpr['move'];
if ($tomove != $player) {
    die();
}
$lastmove   = $tpr['lastmove'];
$dealer     = $tpr['dealer'];
$hand       = $tpr['hand'];
$tablepot   = $tpr['pot'];
$tablebet   = $tpr['bet'];
$tablelimit = $tpr['tablelimit'];
$lastbet    = $tpr['lastbet'];
$numleft    = get_num_left();
$playerpot  = $tpr['p' . $player . 'pot'];
$playerbet  = $tpr['p' . $player . 'bet'];
if (substr($playerbet, 0, 1) == 'F') {
    die();
}
if ($playerpot == 0) {
    die();
}
if (($hand > 4) && ($hand < 12) && ($player == $tomove) && ($numplayers > 1) && ($isbet == true)) {
    $goallin = false;
    $nextup  = nextplayer($player);
    $newr    = '';
    if ($hand == 6)
        $newr = ", hand = '7' ";
    if ($hand == 8)
        $newr = ", hand = '9' ";
    if ($hand == 10)
        $newr = ", hand = '11' ";
    if ($action == 'allin') {
        $result  = mysql_query("update " . DB_STATS . " set allin = allin+1 where player  = '" . $plyrname . "' ");
        $goallin = true;
    } elseif ($action == 'fold') {
        if ($hand < 6) {
            $result = mysql_query("update " . DB_STATS . " set fold_pf = fold_pf+1 where player  = '" . $plyrname . "' ");
        } elseif ($hand < 8) {
            $result = mysql_query("update " . DB_STATS . " set fold_f = fold_f+1 where player  = '" . $plyrname . "' ");
        } elseif ($hand < 10) {
            $result = mysql_query("update " . DB_STATS . " set fold_t = fold_t+1 where player  = '" . $plyrname . "' ");
        } else {
            $result = mysql_query("update " . DB_STATS . " set fold_r = fold_r+1 where player  = '" . $plyrname . "' ");
        }
        $msg    = $plyrname . ' ' . GAME_PLAYER_FOLDS;
        $result = mysql_query("update " . DB_POKER . " set  msg = '" . $msg . "', p" . $player . "bet = 'F" . $playerbet . "', move = '" . $nextup . "' " . $newr . " , lastmove = '" . ($time + 1) . "'  where gameID = '" . $gameID . "' ");
        $result = mysql_query("update " . DB_PLAYERS . " set  lastmove = '" . ($time + 1) . "' where username = '" . $plyrname . "' ");
    } elseif ($action == 'check') {
        $msg    = $plyrname . ' ' . GAME_PLAYER_CHECKS;
        $result = mysql_query("update " . DB_STATS . " set checked = checked+1 where player  = '" . $plyrname . "' ");
        $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', move = '" . $nextup . "' " . $newr . " , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' ");
        $result = mysql_query("update " . DB_PLAYERS . " set  lastmove = '" . ($time + 1) . "' where username = '" . $plyrname . "' ");
    } elseif ($action == 'call') {
        $result  = mysql_query("update " . DB_STATS . " set called = called+1 where player  = '" . $plyrname . "' ");
        $process = true;
        $msg     = $plyrname . ' ' . GAME_PLAYER_CALLS;
        $callbet = $tablebet - $playerbet;
        if ($playerpot <= $callbet) {
            $goallin = true;
        } else {
            $potleft   = $playerpot - $callbet;
            $tablepot  = $tablepot + $callbet;
            $pbet      = $tablebet;
            $tablebet2 = $tablebet;
        }
    } elseif ($action >= $playerpot) {
        $result  = mysql_query("update " . DB_STATS . " set allin = allin+1 where player  = '" . $plyrname . "' ");
        $goallin = true;
    } else {
        $result   = mysql_query("update " . DB_STATS . " set bet = bet+1 where player  = '" . $plyrname . "' ");
        $msg      = $plyrname . ' ' . GAME_PLAYER_RAISES . ' ' . money_small($action);
        $diff     = ($tablebet - $playerbet);
        $checkbet = ($diff + $action);
        if ($checkbet >= $playerpot) {
            $goallin = true;
        } else {
            $process   = true;
            $pbet      = $tablebet + $action;
            $tablepot  = $tablepot + $checkbet;
            $potleft   = $playerpot - $checkbet;
            $tablebet2 = $tablebet + $action;
        }
    }
    if ($goallin == true) {
        $process   = true;
        $msg       = $plyrname . ' ' . GAME_PLAYER_GOES_ALLIN;
        $diff      = ($tablebet - $playerbet);
        $raise     = $playerpot - $diff;
        $tablepot  = $tablepot + $playerpot;
        $tablebet2 = (($raise > 0) ? ($tablebet + $raise) : $tablebet);
        $pbet      = $playerbet + $playerpot;
        $potleft   = 0;
    }
    if ($process == true) {
        $lastbet = ((($tablebet2 > $tablebet) || ($lastbet == 0)) ? $player . '|' . $tablebet : $lastbet);
        $result  = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', pot = '" . $tablepot . "', bet = '" . $tablebet2 . "', lastbet = '" . $lastbet . "', p" . $player . "bet = '" . $pbet . "', move = '" . $nextup . "', lastmove = '" . ($time + 1) . "' , p" . $player . "pot = '" . $potleft . "' " . $newr . "where gameID = '" . $gameID . "' ");
        $result  = mysql_query("update " . DB_PLAYERS . " set  lastmove = '" . $time . "' where username = '" . $plyrname . "' ");
    }
}
?>