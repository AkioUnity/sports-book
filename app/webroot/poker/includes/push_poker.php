<?php
require('sec_inc.php');
$BUTTON_START    = BUTTON_START;
$BUTTON_FOLD     = BUTTON_FOLD;
$BUTTON_CHECK    = BUTTON_CHECK;
$BUTTON_CALL     = BUTTON_CALL;
$BUTTON_BET      = BUTTON_BET;
$BUTTON_ALLIN    = BUTTON_ALLIN;
$GAME_PLAYER_POT = GAME_PLAYER_POT;
$time            = time();
$testing         = 0;
$tpq             = mysql_query("select lastmove, hand,tablestyle, move, tabletype from " . DB_POKER . " where gameID = '" . $gameID . "' ");
$tpr             = mysql_fetch_array($tpq);
$lmove           = $tpr['lastmove'];
$tomove          = $tpr['move'];
$tabletype       = $tpr['tabletype'];
$tablestyle      = $tpr['tablestyle'];
if ($tablestyle == '')
    $tablestyle = 'default';
$hand   = $tpr['hand'];
$unixts = addslashes($_GET['ts']);
$hnd    = addslashes($_GET['h']);
$tmv    = addslashes($_GET['m']);
$force  = addslashes($_GET['f']);
if ($force == 1) {
    $result = mysql_query("update " . DB_POKER . " set lastmove = '" . ($time + 1) . "'  where gameID = '" . $gameID . "' ");
}
if (($lmove == $unixts) && ($lmove > 0) && ($tmv == $tomove) && ($hnd == $hand) && ($force == 0))
    die();
$userBetscheme = mysql_query("select balance from " . DB_USERS . " where username = '" . $plyrname . "' ");
$userBetscheme = mysql_fetch_array($userBetscheme);
$statsq     = mysql_query("select winpot from " . DB_STATS . " where player = '" . $plyrname . "' ");
$statsr     = mysql_fetch_array($statsq);
$winnings   = floatval($userBetscheme['balance'])*1000;
$tpq        = mysql_query("select * from " . DB_POKER . " where gameID = '" . $gameID . "' ");
$tpr        = mysql_fetch_array($tpq);
$player     = getplayerid($plyrname);
$tomove     = $tpr['move'];
$lastmove   = $tpr['lastmove'];
$dealer     = $tpr['dealer'];
$hand       = $tpr['hand'];
$tablepot   = $tpr['pot'];
$tablebet   = $tpr['bet'];
$numleft    = get_num_left();
$numplayers = get_num_players();
$playerpot  = get_pot($player);
$playerbet  = get_bet_math($player);
$tablelimit = $tpr['tablelimit'];
if (($playerbet > 0) && ($playerpot == 0))
    $all_in = true;
$checkround = false;
if (($player == last_bet()) && (check_bets() == true) && (($hand == 5) || ($hand == 7) || ($hand == 9) || ($hand == 11)))
    $checkround = true;
$tablecards = array(
    decrypt_card($tpr['card1']),
    decrypt_card($tpr['card2']),
    decrypt_card($tpr['card3']),
    decrypt_card($tpr['card4']),
    decrypt_card($tpr['card5'])
);
$names      = array(
    $tpr['p1name'],
    $tpr['p2name'],
    $tpr['p3name'],
    $tpr['p4name'],
    $tpr['p5name'],
    $tpr['p6name'],
    $tpr['p7name'],
    $tpr['p8name'],
    $tpr['p9name'],
    $tpr['p10name']
);
$avatars    = array(
    get_ava($names[0]),
    get_ava($names[1]),
    get_ava($names[2]),
    get_ava($names[3]),
    get_ava($names[4]),
    get_ava($names[5]),
    get_ava($names[6]),
    get_ava($names[7]),
    get_ava($names[8]),
    get_ava($names[9])
);
$pots       = array(
    money($tpr['p1pot']),
    money($tpr['p2pot']),
    money($tpr['p3pot']),
    money($tpr['p4pot']),
    money($tpr['p5pot']),
    money($tpr['p6pot']),
    money($tpr['p7pot']),
    money($tpr['p8pot']),
    money($tpr['p9pot']),
    money($tpr['p10pot'])
);
$make_cards = array(
    '',
    '',
    '',
    '',
    '',
    ''
);
$bets       = array(
    money_small($tpr['p1bet']),
    money_small($tpr['p2bet']),
    money_small($tpr['p3bet']),
    money_small($tpr['p4bet']),
    money_small($tpr['p5bet']),
    money_small($tpr['p6bet']),
    money_small($tpr['p7bet']),
    money_small($tpr['p8bet']),
    money_small($tpr['p9bet']),
    money_small($tpr['p10bet'])
);
$i          = 0;
while ($i < 10) {
    $pbet  = $tpr['p' . ($i + 1) . 'bet'];
    $pbetf = substr($pbet, 0, 1);
    if ($pbetf == 'F')
        $bets[$i] = money_small(substr($pbet, 1, 10));
    if ((($pbet > 0) || (($pbetf == 'F') && ($bets[$i] > 0))) && ($hand > 2)) {
?>
var bet<?php
        echo ($i + 1);
?> = '<';
bet<?php
        echo ($i + 1);
?> += 'table border="0" cellspacing="0" cellpadding="0"><tr><td><';
<?php
        if (($dealer == ($i + 1)) && (($i + 1) > 4))
            echo 'bet' . ($i + 1) . ' += \'img src="images/' . $tablestyle . '/d.jpg"></td><td></td></tr><tr><td><\';';
?>
bet<?php
        echo ($i + 1);
?> += 'img src="images/<?php
        echo $tablestyle;
?>/bet.jpg" width="37" height="22"></td><td nowrap class="tblbet"><?php
        echo $bets[$i];
?></td></tr><';
<?php
        if (($dealer == ($i + 1)) && (($i + 1) < 5)) {
            echo 'bet' . ($i + 1) . ' += \'tr><td><\';';
            echo 'bet' . ($i + 1) . ' += \'img src="images/' . $tablestyle . '/d.jpg"></td><td></td></tr><\';';
        }
?>
bet<?php
        echo ($i + 1);
?> += '/table>';  
<?php
    } else {
?>
var bet<?php
        echo ($i + 1);
?> = '<';
bet<?php
        echo ($i + 1);
?> += 'table border="0" cellspacing="0" cellpadding="0"><tr><td><';
<?php
        if ($dealer == ($i + 1)) {
?>
bet<?php
            echo ($i + 1);
?> += 'img src="images/<?php
            echo $tablestyle;
?>/d.jpg"></td><td><';
<?php
        }
?>
bet<?php
        echo ($i + 1);
?> += 'img src="images/spacer.gif" width="1" height="22"></td></tr></table>';  
<?php
    }
    $i++;
}
$make_cards = array(
    '',
    '',
    '',
    '',
    '',
    ''
);
$i          = 1;
while ($i < 11) {
    $testname = $tpr['p' . $i . 'name'];
    if (($testname == $plyrname) && ($testname != '')) {
        if ((get_bet($i) != 'FOLD') && ($hand > 4) && ($hand < 15) && ((get_bet($i) > 0) || (get_pot($i) > 0))) {
            $make_cards[$i] = decrypt_card($tpr['p' . $i . 'card1']) . '.jpg|' . decrypt_card($tpr['p' . $i . 'card2']) . '.jpg';
        } else {
            $make_cards[$i] = '';
        }
    } elseif ($tpr['p' . $i . 'name'] != '') {
        if ((get_bet($i) != 'FOLD') && ($hand == 14) && (get_bet($i) > 0) && ($numleft > 1)) {
            $make_cards[$i] = decrypt_card($tpr['p' . $i . 'card1']) . '.jpg|' . decrypt_card($tpr['p' . $i . 'card2']) . '.jpg';
        } elseif ((get_bet($i) != 'FOLD') && ($hand > 4) && ($hand < 14)) {
            $make_cards[$i] = 'facedown.gif';
        } else {
            $make_cards[$i] = '';
        }
    } else {
        $make_cards[$i] = '';
    }
    $i++;
}
$msg = $tpr['msg'];
?>
var dtxt = '<font color="#FFCC33"><?php
echo $msg;
?></font>';
<?php
$sfx = '';
if ((strstr($msg, GAME_MSG_DEAL_CARDS)) && ($numleft == 2))
    $sfx = 'deal2.swf';
if ((strstr($msg, GAME_MSG_DEAL_CARDS)) && ($numleft == 3))
    $sfx = 'deal3.swf';
if ((strstr($msg, GAME_MSG_DEAL_CARDS)) && ($numleft == 4))
    $sfx = 'deal4.swf';
if ((strstr($msg, GAME_MSG_DEAL_CARDS)) && ($numleft == 5))
    $sfx = 'deal5.swf';
if ((strstr($msg, GAME_MSG_DEAL_CARDS)) && ($numleft > 5))
    $sfx = 'deal6.swf';
if (strstr($msg, " " . GAME_MSG_BIG_BLIND))
    $sfx = 'chips.swf';
if (strstr($msg, " " . GAME_MSG_SMALL_BLIND))
    $sfx = 'chips.swf';
if (strstr($msg, " " . GAME_PLAYER_CALLS))
    $sfx = 'chips.swf';
if (strstr($msg, " " . GAME_PLAYER_RAISES))
    $sfx = 'chips.swf';
if (strstr($msg, " " . GAME_PLAYER_GOES_ALLIN))
    $sfx = 'chips.swf';
if ($hand == 2)
    $sfx = 'shuffle.swf';
if ($hand == 14)
    $sfx = $sfx = 'chips.swf';
if (strstr($msg, GAME_MSG_DEAL_FLOP))
    $sfx = 'flop.swf';
if (strstr($msg, GAME_MSG_DEAL_TURN))
    $sfx = 'card.swf';
if (strstr($msg, GAME_MSG_DEAL_RIVER))
    $sfx = 'card.swf';
if (strstr($msg, " " . GAME_PLAYER_CHECKS))
    $sfx = 'check.swf';
if (strstr($msg, " " . GAME_PLAYER_FOLDS))
    $sfx = 'fold.swf';
?>


<?php
$i = 1;
while ($i < 11) {
    $cards = explode('|', $make_cards[$i]);
    if ($cards[1] != '') {
?>
var cards<?php
        echo $i;
?> = '<';
cards<?php
        echo $i;
?> += 'table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="center"><';
cards<?php
        echo $i;
?> += 'img src="images/cards/<?php
        echo $cards[0];
?>" border="0"></td><td align="center"><';
cards<?php
        echo $i;
?> += 'img src="images/cards/<?php
        echo $cards[1];
?>" border="0"></td></tr></table>';

<?php
    } elseif ($cards[0] != '') {
?>
var cards<?php
        echo $i;
?> = '<';
cards<?php
        echo $i;
?> += 'img src="images/cards/<?php
        echo $cards[0];
?>" border="0">';
<?php
    } else {
?>
var cards<?php
        echo $i;
?> = '';
<?php
    }
    $i++;
}
?>


<?php
$i = 0;
while ($i < 10) {
?>

<?php
    if ($names[$i] != '') {
?>
var info<?php
        echo $i;
?> = '<table width="100%" border="0" cellspacing="0" cellpadding="3"  class="plyrinfo"';
info<?php
        echo $i;
?> += '><tr><td class="tablesmll" align="center"><b';
info<?php
        echo $i;
?> += '><?php
        echo $names[$i];
?></b><br><font color="#666666"';
info<?php
        echo $i;
?> += '><?php
        echo addslashes($GAME_PLAYER_POT);
?><b';
info<?php
        echo $i;
?> += '><?php
        echo $pots[$i];
?></b></font></td></tr></table>';
<?php
        if (($i + 1) == $tomove) {
?>
var ava<?php
            echo $i;
?> = '<';
ava<?php
            echo $i;
?> += 'table width="51" border="1" bordercolor="#FFCC33"><tr><td><';
ava<?php
            echo $i;
?> += 'img width="50" height="50" src="images/avatars/<?php
            echo $avatars[$i];
?>" border="0"></td></tr></table>';
<?php
        } elseif (get_bet($i + 1) == 'FOLD') {
?>
var ava<?php
            echo $i;
?> = '<';
ava<?php
            echo $i;
?> += 'table width="51" border="1" bordercolor="#666666"><tr><td><';
ava<?php
            echo $i;
?> += 'img width="50" height="50" src="images/avatars/<?php
            echo $avatars[$i];
?>" border="0"></td></tr></table>';
<?php
        } else {
?>
var ava<?php
            echo $i;
?> = '<';
ava<?php
            echo $i;
?> += 'table width="51" border="1"><tr><td><';
ava<?php
            echo $i;
?> += 'img width="50" height="50" src="images/avatars/<?php
            echo $avatars[$i];
?>" border="0"></td></tr></table>';
<?php
        }
?>
<?php
    } else {
?>
<?php
        if (($tabletype == 't') && ($hand != '')) {
?>
var info<?php
            echo $i;
?> = "";
var ava<?php
            echo $i;
?> = "";
<?php
        } else {
?>
var info<?php
            echo $i;
?> = "";

var ava<?php
            echo $i;
?> = '<';

ava<?php
            echo $i;
?> += 'input type="image" border="0" name="imageField" src="images/<?php
            echo $tablestyle;
?>/sit.gif" width="51" height="51" onclick="sit_down(\'<?php
            echo ($i + 1);
?>\');">';
<?php
        }
?>
<?php
    }
?>
<?php
    $i++;
}
?>


<?php
if (((($hand > 5) && ($hand < 12)) || ($hand == 13) || ($hand == 14)) && ($numleft > 1)) {
?>
var ccard1 = '<';
ccard1 += 'img src="images/cards/<?php
    echo $tablecards[0];
?>.jpg" border="0">';
<?php
} else {
    echo 'ccard1 = "";';
}
?>

<?php
if (((($hand > 5) && ($hand < 12)) || ($hand == 13) || ($hand == 14)) && ($numleft > 1)) {
?>
var ccard2 = '<';
ccard2+= 'img src="images/cards/<?php
    echo $tablecards[1];
?>.jpg" border="0">';
<?php
} else {
    echo 'ccard2 = "";';
}
?>

<?php
if (((($hand > 5) && ($hand < 12)) || ($hand == 13) || ($hand == 14)) && ($numleft > 1)) {
?>
var ccard3 = '<';
ccard3 += 'img src="images/cards/<?php
    echo $tablecards[2];
?>.jpg" border="0">';
<?php
} else {
    echo 'ccard3 = "";';
}
?>

<?php
if (((($hand > 7) && ($hand < 12)) || ($hand == 13) || ($hand == 14)) && ($numleft > 1)) {
?>
var ccard4 = '<';
ccard4 += 'img src="images/cards/<?php
    echo $tablecards[3];
?>.jpg" border="0">';
<?php
} else {
    echo 'ccard4 = "";';
}
?>

<?php
if (((($hand > 9) && ($hand < 12)) || ($hand == 13) || ($hand == 14)) && ($numleft > 1)) {
?>
var ccard5 = '<';
ccard5 += 'img src="images/cards/<?php
    echo $tablecards[4];
?>.jpg" border="0">';
<?php
} else {
    echo 'ccard5 = "";';
}
?>

var dealertxt = '<?php
echo $msg;
?>';
var tablepot = '<b><font color = "#FFFFFF"><?php
echo money($tablepot);
?></font></b>';

<?php
if (($hand == '') && ($numplayers > 1)) {
?>
var betbuttons = '<';
 betbuttons += 'input type="button" name="Button" value="<?php
 
    echo addslashes($BUTTON_START);
?>" class="betbuttons" onclick="push_action(\'start\');">';
      <?php
} elseif (($hand > 4) && ($hand < 12) && ($player == $tomove) && ($numplayers > 1) && ($all_in == false) && ($numleft > 1) && ($checkround == false)) {
    $button_limit   = array(
        '50',
        '100',
        '150',
        '250',
        '500',
        '15000',
        '25000',
        '50000',
        '100000',
        '250000',
        '500000',
        '1000000',
        '2500000',
        '5000000'
    );
    $button_array   = array(
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
        '500000'
    );
    $button_display = array();
    $winpot         = get_pot($player);
    $i              = 13;
    $x              = 4;
    while (($i >= 0) && ($x >= 0)) {
        if (($winpot > $button_limit[$i]) && ($winpot >= (($tablebet - $playerbet) + $button_array[$i]))) {
            $button_display[$x] = $button_array[$i];
            $x--;
        }
        $i--;
    }
?>
var betbuttons = '<';
betbuttons += 'table border="0" cellspacing="0" cellpadding="0"><';
betbuttons += 'tr><';
betbuttons += 'td align="center"><';
<?php
    if (($tablebet > $playerbet) && ($winpot >= ($tablebet - $playerbet))) {
        echo 'betbuttons += \'input type="button" name="Button" value="' . addslashes($BUTTON_CALL) . ' ' . money_small($tablebet - $playerbet) . '" class="betbuttons" onclick="push_action(\\\'call\\\')">&nbsp;<\';';
    } elseif ($winpot >= ($tablebet - $playerbet)) {
        echo 'betbuttons += \'input type="button" name="Button" value="' . addslashes($BUTTON_CHECK) . '" class="betbuttons" onclick="push_action(\\\'check\\\')">&nbsp;<\';';
    }
    $i = 0;
    while ($button_display[$i] != '') {
        echo 'betbuttons += \'input type="button" name="Button" value="' . addslashes($BUTTON_BET) . ' ' . money_small($button_display[$i]) . '" class="betbuttons" onclick="push_action(\\\'' . $button_display[$i] . '\\\')">&nbsp;<\';';
        $i++;
    }
    echo 'betbuttons += \'input type="button" name="Button" value="' . addslashes($BUTTON_ALLIN) . '" class="betbuttons" onclick="push_action(\\\'allin\\\')">&nbsp;<\';';
    if ($tablebet > $playerbet) {
        echo 'betbuttons += \'input type="button" name="Button" value="' . addslashes($BUTTON_FOLD) . '" class="betbuttons" onclick="push_action(\\\'fold\\\')">&nbsp;<\';';
    }
?>

betbuttons += '/td><';
betbuttons += '/tr><';
betbuttons += '/table>';

<?php
} else {
?>
var betbuttons = '';
<?php
}
?>


document.getElementById('dealertxt').innerHTML = dealertxt;
document.getElementById('tablepot').innerHTML = tablepot;
// document.getElementById('playerup').innerHTML = playerup;


document.getElementById('pinfo1').innerHTML = info0;
document.getElementById('pinfo2').innerHTML = info1;
document.getElementById('pinfo3').innerHTML = info2;
document.getElementById('pinfo4').innerHTML = info3;
document.getElementById('pinfo5').innerHTML = info4;
document.getElementById('pinfo6').innerHTML = info5;
document.getElementById('pinfo7').innerHTML = info6;
document.getElementById('pinfo8').innerHTML = info7;
document.getElementById('pinfo9').innerHTML = info8;
document.getElementById('pinfo10').innerHTML = info9;

document.getElementById('pbet1').innerHTML = bet1;
document.getElementById('pbet2').innerHTML = bet2;
document.getElementById('pbet3').innerHTML = bet3;
document.getElementById('pbet4').innerHTML = bet4;
document.getElementById('pbet5').innerHTML = bet5;
document.getElementById('pbet6').innerHTML = bet6;
document.getElementById('pbet7').innerHTML = bet7;
document.getElementById('pbet8').innerHTML = bet8;
document.getElementById('pbet9').innerHTML = bet9;
document.getElementById('pbet10').innerHTML = bet10;

<?php
if (($hand == '') || ($hand == 15)) {
?>
document.getElementById('pava1').innerHTML = ava0;
document.getElementById('pava2').innerHTML = ava1;
document.getElementById('pava3').innerHTML = ava2;
document.getElementById('pava4').innerHTML = ava3;
document.getElementById('pava5').innerHTML = ava4;
document.getElementById('pava6').innerHTML = ava5;
document.getElementById('pava7').innerHTML = ava6;
document.getElementById('pava8').innerHTML = ava7;
document.getElementById('pava9').innerHTML = ava8;
document.getElementById('pava10').innerHTML = ava9;
<?php
}
?>
document.getElementById('card1').innerHTML = ccard1;
document.getElementById('card2').innerHTML = ccard2;
document.getElementById('card3').innerHTML = ccard3;
document.getElementById('card4').innerHTML = ccard4;
document.getElementById('card5').innerHTML = ccard5;



document.getElementById('pos1hand').innerHTML = cards1;
document.getElementById('pos2hand').innerHTML = cards2;
document.getElementById('pos3hand').innerHTML = cards3;
document.getElementById('pos4hand').innerHTML = cards4;
document.getElementById('pos5hand').innerHTML = cards5;
document.getElementById('pos6hand').innerHTML = cards6;
document.getElementById('pos7hand').innerHTML = cards7;
document.getElementById('pos8hand').innerHTML = cards8;
document.getElementById('pos9hand').innerHTML = cards9;
document.getElementById('pos10hand').innerHTML = cards10;

var flashHtml = '<';
flashHtml += 'OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"';
flashHtml += 'codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"'; 
flashHtml += ' WIDTH="1" HEIGHT="1" id="mian" ALIGN="">'; 
flashHtml += '<PARAM NAME=movie VALUE="sounds/<?php
echo $sfx;
?>"><PARAM NAME=quality VALUE=high>'; 
flashHtml += '<EMBED src="sounds/<?php
echo $sfx;
?>" quality=high bgcolor=#FFFFFF WIDTH="1" HEIGHT="1" NAME="main" '; 
flashHtml += 'ALIGN="" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>'; 
flashHtml += '</OBJECT>';
 document.getElementById('flashObject').innerHTML = flashHtml;

document.getElementById('buttons').innerHTML = betbuttons;

document.getElementById('dealertxt').innerHTML = dtxt;

document.forms['checkmov']['lastmove'].value = '<?php
echo $lastmove;
?>';
document.forms['checkmov']['hand'].value = '<?php
echo $hand;
?>';
document.forms['checkmov']['tomove'].value = '<?php
echo $tomove;
?>';