<?php
require('sec_inc.php');
date_default_timezone_set('Europe/Vilnius');
$time   = time();
$result = mysql_query("update " . DB_PLAYERS . " set timetag = '" . ($time + 1) . "' where username = '" . $plyrname . "' and gID = '" . $gameID . "' ");
$cq     = mysql_query("select * from " . DB_LIVECHAT . " where gameID = '" . $gameID . "' ");
$cr     = mysql_fetch_array($cq);
if ($cr['updatescreen'] > $time) {
    $i    = 1;
    $chat = '<div id="chatdiv" style="border : solid 0px   padding : 1px; width : 100%; height : 70px; overflow : auto;">';
    while ($i < 6) {
        $chat .= $cr['c' . $i];
        $i++;
    }
    $chat .= '</div>';
?>
var chatxt = '<?php
    echo $chat;
?>';
document.getElementById('chatbox').innerHTML = chatxt;
<?php
}
$tpq        = mysql_query("select p1name, p2name, p3name, p4name, p5name, p6name, p7name, p8name, p9name, p10name, p1pot, p2pot, p3pot, p4pot, p5pot, p6pot, p7pot, p8pot, p9pot, p10pot, p1bet, p2bet, p3bet, p4bet, p5bet, p6bet, p7bet, p8bet, p9bet,p10bet, hand , move, tabletype, pot, bet, tablelimit, lastmove, lastbet, dealer  from " . DB_POKER . " where gameID = '" . $gameID . "' ");
$tpr        = mysql_fetch_array($tpq);
$hand       = $tpr['hand'];
$autoplayer = $tpr['move'];
$autotimer  = 0;
$movetimer  = MOVETIMER;
$lmovetimer = KICKTIMER;
$distimer   = DISCONNECT;
$kick       = $time - ($lmovetimer * 60);
$timekick   = $time - $distimer;
$lastmove   = $tpr['lastmove'];
$diff       = $time - $lastmove;
$dealer     = $tpr['dealer'];
$tablepot   = $tpr['pot'];
$tabletype  = $tpr['tabletype'];
$tablelimit = $tpr['tablelimit'];
$tablebet   = $tpr['bet'];
$lastbet    = $tpr['lastbet'];
if ($autoplayer != '') {
    $nextup = nextplayer($autoplayer);
    if ($nextup == $autoplayer)
        $end = true;
    $autoname   = $tpr['p' . $autoplayer . 'name'];
    $autopot    = $tpr['p' . $autoplayer . 'pot'];
    $autobet    = $tpr['p' . $autoplayer . 'bet'];
    $autofold   = substr($autobet, 0, 1);
    $autostatus = 'live';
    if (($autopot == 0) && ($autobet > 0) && (($hand > 4) && ($hand < 12)))
        $autostatus = 'allin';
    if (($autofold == 'F') && (($hand > 4) && ($hand < 12)))
        $autostatus = 'fold';
    if (($autopot == 0) && (($autobet == 0) || ($autostatus == 'fold')))
        $autostatus = 'bust';
    $i  = 1;
    $np = 0;
    $ai = 0;
    $fo = 0;
    $bu = 0;
    while ($i < 11) {
        $usr   = $tpr['p' . $i . 'name'];
        $upot  = $tpr['p' . $i . 'pot'];
        $ubet  = $tpr['p' . $i . 'bet'];
        $ufold = substr($ubet, 0, 1);
        if ($usr != '') {
            $np++;
            if (($upot == 0) && ($ubet > 0) && ($ufold != 'F') && (($hand > 4) && ($hand < 15)))
                $ai++;
            if (($ufold == 'F') && ($upot > 0) && (($hand > 4) && ($hand < 15)))
                $fo++;
            if ((($ubet == 0) || ($ufold == 'F')) && ($upot == 0))
                $bu++;
            $ttq   = mysql_query("select gID, timetag, lastmove, banned from " . DB_PLAYERS . " where username = '" . $usr . "'  ");
            $tkick = '';
            $ttr   = mysql_fetch_array($ttq);
            if (($ttr['timetag'] < $timekick) || (($ttr['lastmove'] < $kick) && ($hand > 5)) || ($ttr['banned'] == 1) || ($ttr['gID'] != $gameID))
                $tkick = true;
            if ((($upot == 0) && (($ubet == 0) || ($ufold == 'F'))) || ($tkick == true)) {
                $result = mysql_query("update " . DB_POKER . " set p" . $i . "name = '', p" . $i . "bet = '0', p" . $i . "pot = '0' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' ");
                $moneyWrite = $upot == 0 ? 0 : $upot/1000;
                $result = mysql_query("update " . DB_USERS . " set balance = balance + " . ($moneyWrite) . " where username  = '" . $usr . "'  ");
                $result = mysql_query("update " . DB_PLAYERS . " set gID = '' where username = '" . $usr . "' ");
                if ($tkick == true) {
                    sys_msg($usr . ' ' . GAME_MSG_LOST_CONNECTION, $gameID);
                    if ($_SESSION['playername'] == $usr) {
                        $wait    = WAITIMER;
                        $setwait = $time + $wait;
                        $result  = mysql_query("update " . DB_PLAYERS . " set waitimer = '" . $setwait . "' where username = '" . $plyrname . "' ");
                        $url     = 'sitout.php';
                        echo 'parent.document.location.href = "' . $url . '"';
                    }
                } else {
                    sys_msg($usr . ' ' . GAME_MSG_PLAYER_BUSTED, $gameID);
                }
            }
        }
        $i++;
    }
    $checkbets = check_bets();
    $showsort  = false;
    $lastman   = '';
    $allpl     = $np - $bu;
    if ($allpl == 1)
        $lastman = last_player();
    $nfpl       = $allpl - $fo;
    $lipl       = $nfpl - $ai;
    $gamestatus = 'live';
    if (($hand > 4) && ($hand < 12)) {
        if (($nfpl == 1) && ($allpl > 1))
            $gamestatus = 'allfold';
        if (($lipl < 2) && ($allpl > 1) && ($checkbets == true) && ($ai > 0))
            $gamestatus = 'showdown';
    } else {
        if (($nfpl == 1) && ($allpl > 1))
            $showshort = true;
    }
    if (($allpl == 1) && (($hand != '') || ($move != ''))) {
        $winpot = $tpr['p' . $lastman . 'pot'] + $tablepot;
        if (($tabletype == 't') && ($allpl == 1)) {
            if ($autoname != '') {
                $msg = $autoname . ' ' . GAME_MSG_WON_TOURNAMENT;
            } else {
                $msg = GAME_MSG_PLAYERS_JOINING;
            }
            $result = mysql_query("update " . DB_STATS . " set tournamentswon = tournamentswon + 1 where player  = '" . $autoname . "' ");
            $result = mysql_query("INSERT INTO " . DB_LOGS . " (user_id, message, created) VALUES (".$_SESSION['Auth']['User']['id'].", 'Player won a tournament.', '".date("Y-m-d  H:i:s")."')");
        } else {
            $msg = GAME_MSG_PLAYERS_JOINING;
        }
        $result = mysql_query("update " . DB_POKER . " set p" . $lastman . "bet = '0', p" . $lastman . "pot = '" . $winpot . "', move = '', lastmove = '" . ($time + 1) . "', dealer = '', msg = '" . $msg . "', hand = '' , bet = '0', pot = '0' where gameID = '" . $gameID . "' ");
        die();
    }
    if (($autoname == '') || ($autostatus == 'bust') && ($allpl > 1)) {
        $result = mysql_query("update " . DB_POKER . " set move = '" . $nextup . "' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' ");
        die();
    }
}
if (($allpl == 0) && (($hand != '') || ($move != ''))) {
    $msg    = GAME_MSG_PLAYERS_JOINING;
    $result = mysql_query("update " . DB_POKER . " set move = '', lastmove = '" . ($time + 1) . "', dealer = '', msg = '" . $msg . "', hand = '' , bet = '0', pot = '0', lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' ");
    $result = mysql_query("delete from " . DB_LIVECHAT . " where gameID = '" . $gameID . "' ");
    die();
}
if (($hand > 4) && ($hand < 12)) {
    $unixts  = time();
    $counter = (($lastmove + $movetimer) - $unixts);
    if ($counter < 0)
        $counter = '0';
    $pxlength = $counter * 3;
    if ($counter < 1) {
        $timer = '';
    } elseif ($counter < 8) {
        $timer = '<table width="' . $pxlength . '" height="5"><tr><td bgcolor="#FF0000"><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';
    } else {
        $timer = '<table width="' . $pxlength . '" height="5"><tr><td bgcolor="#FFCC33"><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';
    }
    $i = 1;
    while ($i < 11) {
        if ($i == $autoplayer) {
?>
document.getElementById('ptimer<?php
            echo $autoplayer;
?>').innerHTML = '<?php
            echo $timer;
?>';
<?php
        } else {
?>
document.getElementById('ptimer<?php
            echo $i;
?>').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';
<?php
        }
        $i++;
    }
}
if (($hand < 4) || ($hand > 11)) {
?>

document.getElementById('ptimer1').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';
document.getElementById('ptimer2').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';

document.getElementById('ptimer3').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';

document.getElementById('ptimer4').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';

document.getElementById('ptimer5').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';

document.getElementById('ptimer6').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';

document.getElementById('ptimer7').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';

document.getElementById('ptimer8').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';

document.getElementById('ptimer9').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';

document.getElementById('ptimer10').innerHTML = '<table width="1" height="5"><tr><td><img src="images/spacer.gif" width="1" height="5"></td></tr></table>';


<?php
}
if (($gID == '') || ($gID == 0))
    die();
if ($hand == '')
    die();
if (!($autoplayer > 0))
    die();
$showdowntimer = (($showshort == true) ? 1 : SHOWDOWN);
if (($hand == 0) && ($lastmove < $time)) {
    $nxtdeal = nextdealer($dealer);
    if ($nxtdeal == '')
        die();
    $msg    = get_name($nxtdeal) . ' ' . GAME_MSG_DEALER_BUTTON;
    $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', lastmove = '" . ($time + 1) . "' , dealer = '" . $nxtdeal . "', move = '" . $nxtdeal . "', bet = '0', lastbet = '0', pot = '0', p1bet = '0', p2bet = '0', p3bet = '0', p4bet = '0',p5bet = '0', p6bet = '0', p7bet = '0', p8bet = '0', p9bet = '0', p10bet = '0', hand = '1'  where gameID = '" . $gameID . "' and hand = '0' ");
    die();
}
$checkround = false;
if (($autoplayer == last_bet()) && ($checkbets == true) && ($gamestatus == 'live') && (($hand == 5) || ($hand == 7) || ($hand == 9) || ($hand == 11))) {
    $nextup = nextplayer($dealer);
    $lbet   = $nextup . '|' . $tablebet;
    if ($hand == 5) {
        $msg    = GAME_MSG_DEAL_FLOP;
        $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', lastbet = '" . $lbet . "', move = '" . $nextup . "', hand = '6' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' ");
    } elseif ($hand == 7) {
        $msg    = GAME_MSG_DEAL_TURN;
        $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', lastbet = '" . $lbet . "', move = '" . $nextup . "', hand = '8' , lastmove = '" . ($time + 1) . "'  where gameID = '" . $gameID . "' ");
    } elseif ($hand == 9) {
        $msg    = GAME_MSG_DEAL_RIVER;
        $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', lastbet = '" . $lbet . "', move = '" . $nextup . "', hand = '10' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' ");
    } elseif ($hand == 11) {
        $msg    = GAME_MSG_SHOWDOWN;
        $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', lastbet = '" . $lbet . "', move = '" . $nextup . "', hand = '13' , lastmove = '" . ($time + 1) . "'  where gameID = '" . $gameID . "' ");
    }
    die();
} else {
    if (($diff < $autotimer) && ($hand < 14) && ($hand != 0))
        die();
    if (($hand > 4) && ($hand < 12) && ($diff < $movetimer) && ($gamestatus == 'live') && ($autostatus == 'live'))
        die();
    if (($hand == 14) && ($diff < $showdowntimer))
        die();
}
if ($gamestatus == 'allfold') {
    $msg    = $autoname . ' ' . GAME_MSG_ALLFOLD;
    $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', hand = '14', lastmove = '" . ($time + 1) . "'  where gameID = '" . $gameID . "' and hand = '" . $hand . "'  ");
    sys_msg($autoname . ' ' . GAME_MSG_ALLFOLD, $gameID);
    die();
}
if (($gamestatus == 'showdown') && (($checkbets == true) || ($lipl == 0))) {
    $msg    = GAME_MSG_SHOWDOWN;
    $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', hand = '13' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' and hand = '" . $hand . "'  ");
    die();
}
if ($autostatus == 'allin') {
    $msg    = $autoplayer . ' ' . GAME_MSG_PLAYER_ALLIN;
    $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', move = '" . $nextup . "' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' ");
    die();
}
if (($hand == 1) && ($lastmove < $time)) {
    $i   = 1;
    $z   = 0;
    $err = true;
    while ($i < 11) {
        $pl    = $tpr['p' . $i . 'name'];
        $chips = $tpr['p' . $i . 'pot'];
        if ($pl != '') {
            if ($chips > $z) {
                $z          = $chips;
                $chipleader = $pl;
                $err        = false;
            } elseif ($chips == $z) {
                $err = true;
            }
            $result = mysql_query("update " . DB_STATS . " set handsplayed = handsplayed + 1 where player  = '" . $pl . "' ");
            $result = mysql_query("INSERT INTO " . DB_LOGS . " (user_id, message, created) VALUES (".$_SESSION['Auth']['User']['id'].", 'Player played a hand.', '".date("Y-m-d  H:i:s")."')");
        }
        $i++;
    }
    $msg    = (($err == true) ? GAME_MSG_LETS_GO : GAME_MSG_CHIP_LEADER . ' ' . $chipleader);
    $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', move = '" . $nextup . "', hand = '2' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' and hand = '1' ");
    die();
}
$blindmultiplier = (11 - $allpl);
if ($tabletype != 't')
    $blindmultiplier = 4;
$tablemultiplier = 1;
if ($tablelimit == 25000)
    $tablemultiplier = 2;
if ($tablelimit == 50000)
    $tablemultiplier = 4;
if ($tablelimit == 100000)
    $tablemultiplier = 8;
if ($tablelimit == 250000)
    $tablemultiplier = 20;
if ($tablelimit == 500000)
    $tablemultiplier = 40;
if ($tablelimit == 1000000)
    $tablemultiplier = 80;
$BB = 50 * $blindmultiplier * $tablemultiplier;
$SB = 25 * $blindmultiplier * $tablemultiplier;
if (($hand == 2) && ($lastmove < $time)) {
    if ($autopot > $SB) {
        $msg  = $autoname . ' ' . GAME_MSG_SMALL_BLIND . ' ' . money_small($SB);
        $npot = $autopot - $SB;
        $nbet = $SB;
    } else {
        $msg  = $autoname . ' ' . GAME_PLAYER_GOES_ALLIN;
        $npot = 0;
        $nbet = $autopot;
    }
    $result = mysql_query("update " . DB_POKER . " set pot = '" . $nbet . "', msg = '" . $msg . "', move = '" . $nextup . "', p" . $autoplayer . "pot = '" . $npot . "', p" . $autoplayer . "bet = '" . $nbet . "', hand = '3' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' and hand = '2'  ");
    die();
}
if (($hand == 3) && ($lastmove < $time)) {
    if ($autopot > $BB) {
        $msg   = $autoname . ' ' . GAME_MSG_BIG_BLIND . ' ' . money_small($BB);
        $npot  = $autopot - $BB;
        $nbet  = $BB;
        $lbet  = $autoplayer . '|' . $BB;
        $ntpot = $tablepot + $nbet;
    } else {
        $msg   = $autoname . ' ' . GAME_PLAYER_GOES_ALLIN;
        $npot  = 0;
        $nbet  = $BB;
        $lbet  = '';
        $ntpot = $tablepot + $nbet;
    }
    $result = mysql_query("update " . DB_POKER . " set pot = '" . $ntpot . "', bet = '" . $nbet . "', msg = '" . $msg . "', p" . $autoplayer . "pot = '" . $npot . "', p" . $autoplayer . "bet = '" . $nbet . "', hand = '4' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' and hand = '3'  ");
    die();
}
if (($hand == 4) && ($lastmove < ($time - $autotimer)) && ($dealer == getplayerid($plyrname))) {
    $msg = GAME_MSG_DEAL_CARDS;
    deal(10, $gameID);
    $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', move = '" . $nextup . "', hand = '5' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' and hand = '4'  ");
    die();
}
if (($hand > 4) && ($hand < 12) && ($lastmove < ($time - $autotimer))) {
    $newr = '';
    if ($hand == 6)
        $newr = ", hand = '7' ";
    if ($hand == 8)
        $newr = ", hand = '9' ";
    if ($hand == 10)
        $newr = ", hand = '11' ";
    if ($tablebet > $autobet) {
        if ($hand < 6) {
            $result = mysql_query("update " . DB_STATS . " set fold_pf = fold_pf+1 where player  = '" . $autoname . "' ");
            $result = mysql_query("INSERT INTO " . DB_LOGS . " (user_id, message, created) VALUES (".$_SESSION['Auth']['User']['id'].", 'Player folded pre-flop.', '".date("Y-m-d  H:i:s")."')");
        } elseif ($hand < 8) {
            $result = mysql_query("update " . DB_STATS . " set fold_f = fold_f+1 where player  = '" . $autoname . "' ");
            $result = mysql_query("INSERT INTO " . DB_LOGS . " (user_id, message, created) VALUES (".$_SESSION['Auth']['User']['id'].", 'Player folded on Flop.', '".date("Y-m-d  H:i:s")."')");
        } elseif ($hand < 10) {
            $result = mysql_query("update " . DB_STATS . " set fold_t = fold_t+1 where player  = '" . $autoname . "' ");
            $result = mysql_query("INSERT INTO " . DB_LOGS . " (user_id, message, created) VALUES (".$_SESSION['Auth']['User']['id'].", 'Player folded on Turn', '".date("Y-m-d  H:i:s")."')");
        } else {
            $result = mysql_query("update " . DB_STATS . " set fold_r = fold_r+1 where player  = '" . $autoname . "' ");
            $result = mysql_query("INSERT INTO " . DB_LOGS . " (user_id, message, created) VALUES (".$_SESSION['Auth']['User']['id'].", 'Player folded on River', '".date("Y-m-d  H:i:s")."')");
        }
        $msg    = $autoname . ' ' . GAME_PLAYER_FOLDS;
        $result = mysql_query("update " . DB_POKER . " set  msg = '" . $msg . "', p" . $autoplayer . "bet = 'F" . $autobet . "', move = '" . $nextup . "' " . $newr . " , lastmove = '" . ($time + 1) . "'  where gameID = '" . $gameID . "' ");
    } else {
        $msg    = $autoname . ' ' . GAME_PLAYER_CHECKS;
        $result = mysql_query("update " . DB_STATS . " set checked = checked+1 where player  = '" . $autoname . "' ");
        $result = mysql_query("INSERT INTO " . DB_LOGS . " (user_id, message, created) VALUES (".$_SESSION['Auth']['User']['id'].", 'Player checked.', '".date("Y-m-d  H:i:s")."')");
        $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', move = '" . $nextup . "' " . $newr . " , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' ");
    }
    die();
}
if ($hand == 13) {
    $cardq      = mysql_query("select card1, card2, card3, card4, card5, p1card1, p1card2, p2card1, p2card2, p3card1, p3card2, p4card1, p4card2, p5card1, p5card2, p6card1, p6card2, p7card1, p7card2, p8card1, p8card2, p9card1, p9card2, p10card1, p10card2 from " . DB_POKER . " where gameID = '" . $gameID . "' ");
    $cardr      = mysql_fetch_array($cardq);
    $tablecards = array(
        decrypt_card($cardr['card1']),
        decrypt_card($cardr['card2']),
        decrypt_card($cardr['card3']),
        decrypt_card($cardr['card4']),
        decrypt_card($cardr['card5'])
    );
    $multiwin   = find_winners();
    $winners    = (($multiwin[1] == '') ? 1 : 2);
    $thiswin    = evaluatewin($multiwin[0]);
    $thiswin    = addslashes($thiswin);
    if ($winners > 1) {
        $msg = GAME_MSG_SPLIT_POT . ' ' . $thiswin;
        sys_msg(GAME_MSG_SPLIT_POT_RESULT . ' ' . $thiswin, $gameID);
    } else {
        $msg = GAME_MSG_WINNING_HAND . ' ' . $thiswin;
        sys_msg(get_name($multiwin[0]) . ' wins the hand with a ' . $thiswin, $gameID);
    }
    $result = mysql_query("update " . DB_POKER . " set msg = '" . $msg . "', hand = '14', lastmove = '" . ($time + 1) . "'  where gameID = '" . $gameID . "' and hand = '13' ");
    die();
}
if ($hand == 14) {
    $proc   = GAME_MSG_PROCESSING;
    $result = mysql_query("update " . DB_POKER . " set msg = '" . $proc . "', move = '" . $nextup . "', hand = '15', lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' and hand = '14' ");
    die();
}
if (($hand == 15) && ($autoplayer == getplayerid($plyrname))) {
    $cardq      = mysql_query("select card1, card2, card3, card4, card5, p1card1, p1card2, p2card1, p2card2, p3card1, p3card2, p4card1, p4card2, p5card1, p5card2, p6card1, p6card2, p7card1, p7card2, p8card1, p8card2, p9card1, p9card2, p10card1, p10card2 from " . DB_POKER . " where gameID = '" . $gameID . "' ");
    $cardr      = mysql_fetch_array($cardq);
    $tablecards = array(
        decrypt_card($cardr['card1']),
        decrypt_card($cardr['card2']),
        decrypt_card($cardr['card3']),
        decrypt_card($cardr['card4']),
        decrypt_card($cardr['card5'])
    );
    $multiwin   = find_winners();
    $i          = 0;
    while ($multiwin[$i] != '') {
        $usr    = get_name($multiwin[$i]);
        $result = mysql_query("update " . DB_STATS . " set handswon = handswon+1 where player  = '" . $usr . "' ");
        $result = mysql_query("INSERT INTO " . DB_LOGS . " (user_id, message, created) VALUES (".$_SESSION['Auth']['User']['id'].", 'Player won a hand.', '".date("Y-m-d  H:i:s")."')");
        $i++;
    }
    distpot($multiwin);
    $result = mysql_query("update " . DB_POKER . " set hand = '0' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gameID . "' and hand = '15' ");
}
?>
