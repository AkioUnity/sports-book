<?php
function transfer_from($val)
{
    global $smallbetfunc;
    if ($smallbetfunc == 1) {
        $val = $val / 1000;
    } elseif ($smallbetfunc == 2) {
        $val = $val / 100;
    } elseif ($smallbetfunc == 3) {
        $val = $val / 10;
    }
    return $val;
}
function transfer_to($val)
{
    global $smallbetfunc;
    if ($smallbetfunc == 1) {
        $val = $val * 1000;
    } elseif ($smallbetfunc == 2) {
        $val = $val * 100;
    } elseif ($smallbetfunc == 3) {
        $val = $val * 10;
    }
    return $val;
}
function money($val)
{
    global $smallbetfunc;
    if (is_numeric($val)) {
        if ($smallbetfunc == 1)
            $val = ($val / 1000);
        if ($smallbetfunc == 2)
            $val = ($val / 100);
        if ($smallbetfunc == 3)
            $val = ($val / 10);
        if ($val > 1000000000) {
            $money = '$' . number_format(($val / 1000000000), 0) . ' B';
        } elseif ($val > 100000000) {
            $money = '$' . number_format(($val / 1000000), 0) . ' M';
        } elseif ($val > 1000000) {
            $money = '$' . number_format(($val / 1000000), 1) . ' M';
        } elseif ($smallbetfunc == 1) {
            $money = '$' . number_format($val, 2);
        } else {
            $money = '$' . number_format($val, 0);
        }
    } elseif ($val == 'FOLD') {
        $money = $val;
    } else {
        $money = '$0';
    }
    return $money;
}
function money_small($val)
{
    global $smallbetfunc;
    $val = str_replace('F', '', $val);
    if ($smallbetfunc == 1)
        $val = ($val / 1000);
    if ($smallbetfunc == 2)
        $val = ($val / 100);
    if ($smallbetfunc == 3)
        $val = ($val / 10);
    if ($val > 999999999) {
        $money = '$' . number_format(($val / 1000000000), 1) . 'B';
    } elseif ($val > 99999999) {
        $money = '$' . number_format(($val / 1000000), 0) . 'M';
    } elseif ($val > 999999) {
        $money = '$' . number_format(($val / 1000000), 1) . 'M';
    } elseif ($val > 999) {
        if (($val % 1000) == 0) {
            $money = '$' . number_format(($val / 1000), 0) . 'K';
        } else {
            $money = '$' . number_format(($val / 1000), 1) . 'K';
        }
    } elseif ($smallbetfunc == 1) {
        $money = '$' . number_format($val, 2);
    } else {
        $money = '$' . number_format($val, 0);
    }
    return $money;
}
function get_ava($usr)
{
    $usrq   = mysql_query("select avatar from " . DB_PLAYERS . " where username = '" . $usr . "' ");
    $usrr   = mysql_fetch_array($usrq);
    $avatar = $usrr['avatar'];
    return $avatar;
}
function display_ava($usr)
{
    $usrq   = mysql_query("select avatar from " . DB_PLAYERS . " where username = '" . $usr . "' ");
    $usrr   = mysql_fetch_array($usrq);
    $avatar = '<img class="responsive" src="images/avatars/' . $usrr['avatar'] . '" border="0">';
    return $avatar;
}
function display_ava_profile($usr)
{
    $time   = time();
    $usrq   = mysql_query("select avatar from " . DB_PLAYERS . " where username = '" . $usr . "' ");
    $usrr   = mysql_fetch_array($usrq);
    $avatar = '<img class="responsive" src="images/avatars/' . $usrr['avatar'] . '?x=' . $time . '" border="0">';
    return $avatar;
}
function sys_msg($msg, $gameID)
{
    $chtq = mysql_query("select * from " . DB_LIVECHAT . " where gameID = '" . $gameID . "' ");
    $chtr = mysql_fetch_array($chtq);
    $time = time();
    $time += 2;
    $c2   = addslashes($chtr['c2']);
    $c3   = addslashes($chtr['c3']);
    $c4   = addslashes($chtr['c4']);
    $c5   = addslashes($chtr['c5']);
    $msg  = '<font color="red"><b>Dealer: </b></font>' . $msg . '<br>';
    $chtq = mysql_query("select * from " . DB_LIVECHAT . " where gameID = '" . $gameID . "' ");
    if (mysql_num_rows($chtq) > 0) {
        $result = mysql_query("update " . DB_LIVECHAT . " set updatescreen = '" . $time . "', c1 = '" . $c2 . "', c2 = '" . $c3 . "', c3 = '" . $c4 . "', c4 = '" . $c5 . "', c5  = '" . $msg . "' where gameID = '" . $gameID . "' ");
    } else {
        $result = mysql_query("insert into " . DB_LIVECHAT . " set updatescreen = '" . $time . "', c1 = '" . $c2 . "', c2 = '" . $c3 . "', c3 = '" . $c4 . "', c4 = '" . $c5 . "', c5 = '" . $msg . "', gameID = '" . $gameID . "' ");
    }
    return;
}
function get_ip($usr)
{
    $ipq = mysql_query("select ipaddress from " . DB_PLAYERS . " where username = '" . $usr . "' ");
    $ipr = mysql_fetch_array($ipq);
    return $ipr['ipaddress'];
}
function getplayerid($plyrname)
{
    global $tpr;
    $i = 1;
    while ($i < 11) {
        if ($plyrname == $tpr['p' . $i . 'name']) {
            return $i;
        }
        $i++;
    }
    return;
}
function get_num_players()
{
    $i = 1;
    $x = 0;
    while ($i < 11) {
        if ((get_name($i) != '') && ((get_pot($i) > 0) || (get_bet($i) > 0)) && (get_pot($i) != 'BUSTED'))
            $x++;
        $i++;
    }
    return $x;
}
function get_all_players()
{
    $i = 1;
    $x = 0;
    while ($i < 11) {
        if (get_name($i) != '')
            $x++;
        $i++;
    }
    return $x;
}
function last_player()
{
    $i = 1;
    while ($i < 11) {
        if ((get_name($i) != '') && ((get_pot($i) > 0) || (get_bet($i) > 0)))
            return $i;
        $i++;
    }
    return;
}
function in_game($i)
{
    $check = false;
    if ((get_name($i) != '') && ((get_bet($i) > 0) || (get_pot($i) > 0)) && (get_bet($i) != 'FOLD'))
        $check = true;
    return $check;
}
function in_gametot($i)
{
    $check = false;
    if (get_name($i) != '')
        $check = true;
    return $check;
}
function get_num_allin()
{
    $i = 1;
    $x = 0;
    while ($i < 11) {
        if ((get_name($i) != '') && (get_pot($i) == 0) && (get_bet($i) > 0) && (get_bet($i) != 'FOLD'))
            $x++;
        $i++;
    }
    return $x;
}
function get_num_left()
{
    $i = 1;
    $x = 0;
    while ($i < 11) {
        if ((get_name($i) != '') && ((get_bet($i) > 0) || (get_pot($i) > 0)) && (get_bet($i) != 'FOLD'))
            $x++;
        $i++;
    }
    return $x;
}
function check_bets()
{
    global $tablebet;
    $i     = 1;
    $check = true;
    while ($i < 11) {
        if ((get_name($i) != '') && (get_pot($i) > 0) && (get_bet($i) != 'FOLD')) {
            if (get_bet($i) < $tablebet)
                $check = false;
        }
        $i++;
    }
    return $check;
}
function roundpot($pot)
{
    $diff = $pot - floor($pot);
    if ($diff > 0.5) {
        $pot = floor($pot) + 1;
    } else {
        $pot = floor($pot);
    }
    return $pot;
}
function find_winners()
{
    $i        = 1;
    $x        = 0;
    $pts      = 0;
    $multiwin = array();
    while ($i < 11) {
        $winpts = 0;
        if (in_game($i) == true) {
            $winpts = evaluatehand($i);
            if ($winpts > $pts) {
                $x           = 0;
                $multiwin[0] = $i;
                $multiwin[1] = '';
                $multiwin[2] = '';
                $multiwin[3] = '';
                $multiwin[4] = '';
                $pts         = $winpts;
            } elseif (($winpts == $pts) && ($winpts > 0)) {
                $x++;
                $multiwin[$x] = $i;
            }
        }
        $i++;
    }
    return array(
        $multiwin[0],
        $multiwin[1],
        $multiwin[2],
        $multiwin[3],
        $multiwin[4],
        $multiwin[5]
    );
}
function decrypt_card($encrypted)
{
    $cards = array(
        'AD',
        '2D',
        '3D',
        '4D',
        '5D',
        '6D',
        '7D',
        '8D',
        '9D',
        '10D',
        'JD',
        'QD',
        'KD',
        'AD',
        '2C',
        '3C',
        '4C',
        '5C',
        '6C',
        '7C',
        '8C',
        '9C',
        '10C',
        'JC',
        'QC',
        'KC',
        'AH',
        '2H',
        '3H',
        '4H',
        '5H',
        '6H',
        '7H',
        '8H',
        '9H',
        '10H',
        'JH',
        'QH',
        'KH',
        'AH',
        '2S',
        '3S',
        '4S',
        '5S',
        '6S',
        '7S',
        '8S',
        '9S',
        '10S',
        'JS',
        'QS',
        'KS'
    );
    $i     = 0;
    while ($cards[$i] != '') {
        $stack = explode(':', $encrypted);
        if ((md5($stack[1] . 'pokerpro' . $cards[$i]) == $stack[0]) && (sizeof($stack) == 2)) {
            return $cards[$i];
        }
        $i++;
    }
}
function encrypt_card($plain)
{
    $plain = 'pokerpro' . $plain;
    $card  = '';
    for ($i = 0; $i < 10; $i++) {
        $card .= mt_rand();
    }
    $salt = substr(md5($card), 0, 2);
    $card = md5($salt . $plain) . ':' . $salt;
    return $card;
}
function last_bet()
{
    global $tpr;
    $lb  = explode('|', $tpr['lastbet']);
    $lb1 = $lb[0];
    return $lb1;
}
function ots($num)
{
    if ($num == 11)
        $num = 1;
    if ($num == 0)
        $num = 10;
    return $num;
}
function nextplayer($player)
{
    global $tpr;
    $time = time();
    $i    = $player;
    $z    = 0;
    while ($z < 10) {
        $i++;
        $test = ots($i);
        if (($tpr['p' . $test . 'name'] != '') && ($tpr['p' . $test . 'pot'] != 'BUSTED') && (($tpr['p' . $test . 'pot'] > 0) || ($tpr['p' . $test . 'bet'] > 0)) && ($tpr['p' . $test . 'pot'] != '') && (get_bet($test) != 'FOLD')) {
            return $test;
        }
        $i = $test;
        $z++;
    }
}
function nextdealer($player)
{
    global $tpr;
    $time = time();
    $i    = $player;
    $z    = 0;
    while ($z < 10) {
        $i++;
        $test = ots($i);
        if (($tpr['p' . $test . 'name'] != '') && ($tpr['p' . $test . 'pot'] > 0)) {
            return $test;
        }
        $i = $test;
        $z++;
    }
}
function insert_cards($select, $pos, $gameID)
{
    $result = mysql_query("update " . DB_POKER . " set " . $pos . " = '" . encrypt_card($select) . "' where gameID = '" . $gameID . "' and hand = '4' ");
}
function deal($numplayers, $gameID)
{
    $cards    = array(
        'AD',
        '2D',
        '3D',
        '4D',
        '5D',
        '6D',
        '7D',
        '8D',
        '9D',
        '10D',
        'JD',
        'QD',
        'KD',
        'AD',
        '2C',
        '3C',
        '4C',
        '5C',
        '6C',
        '7C',
        '8C',
        '9C',
        '10C',
        'JC',
        'QC',
        'KC',
        'AH',
        '2H',
        '3H',
        '4H',
        '5H',
        '6H',
        '7H',
        '8H',
        '9H',
        '10H',
        'JH',
        'QH',
        'KH',
        'AH',
        '2S',
        '3S',
        '4S',
        '5S',
        '6S',
        '7S',
        '8S',
        '9S',
        '10S',
        'JS',
        'QS',
        'KS'
    );
    $cardpos  = array(
        'card1',
        'card2',
        'card3',
        'card4',
        'card5',
        'p1card1',
        'p1card2',
        'p2card1',
        'p2card2',
        'p3card1',
        'p3card2',
        'p4card1',
        'p4card2',
        'p5card1',
        'p5card2',
        'p6card1',
        'p6card2',
        'p7card1',
        'p7card2',
        'p8card1',
        'p8card2',
        'p9card1',
        'p9card2',
        'p10card1',
        'p10card2'
    );
    $numcards = ($numplayers * 2) + 5;
    $i        = 0;
    $pick     = array();
    while ($i < $numcards) {
        $select = $cards[mt_rand(0, 51)];
        if (!in_array($select, $pick)) {
            $pick[$i] = $select;
            insert_cards($select, $cardpos[$i], $gameID);
            $i++;
        }
    }
}
function get_bet_math($pnum)
{
    global $tpr;
    $pbet = $tpr['p' . $pnum . 'bet'];
    $bet  = str_replace('F', '', $pbet);
    return $bet;
}
function fetch_bet_math($pnum)
{
    global $tpr;
    $bet = $tpr['p' . $pnum . 'bet'];
    if (substr($bet, 0, 1) == 'F')
        $bet = 0;
    if ($bet == '')
        $bet = 0;
    return $bet;
}
function get_bet($pnum)
{
    global $tpr;
    $bet = $tpr['p' . $pnum . 'bet'];
    if (substr($bet, 0, 1) == 'F') {
        if (substr($bet, 1, 1) != '') {
            $bet = 'FOLD';
        } else {
            $bet = 0;
        }
    }
    if ($bet == '')
        $bet = 0;
    return $bet;
}
function get_pot($pnum)
{
    global $tpr;
    $pot = $tpr['p' . $pnum . 'pot'];
    if (!is_numeric($pot))
        $pot = 0;
    return $pot;
}
function get_name($pnum)
{
    global $tpr;
    $name = $tpr['p' . $pnum . 'name'];
    return $name;
}
function distpot()
{
    global $tpr;
    global $gameID;
    $origpots = array(
        '',
        get_pot(1),
        get_pot(2),
        get_pot(3),
        get_pot(4),
        get_pot(5),
        get_pot(6),
        get_pot(7),
        get_pot(8),
        get_pot(9),
        get_pot(10)
    );
    $multiwin = find_winners();
    $z        = 0;
    $winbets  = 0;
    while ($multiwin[$z] != '') {
        $winbets += get_bet_math($multiwin[$z]);
        $z++;
    }
    $z = 0;
    while ($multiwin[$z] != '') {
        $player = $multiwin[$z];
        $errmsg .= get_name($player) . '<br>';
        $i      = 1;
        $winbet = get_bet($player);
        $cut    = $winbet / $winbets;
        $origpots[$player] += $winbet;
        while ($i < 11) {
            $losepot = get_pot($i);
            $losebet = get_bet_math($i) * $cut;
            if (($player != $i) && ($losebet > 0) && (!in_array($i, $multiwin))) {
                $errmsg .= 'test against ' . get_name($i) . '<br>';
                if ($winbet >= $losebet) {
                    $errmsg .= 'whole win ' . $losebet . '<br>';
                    $origpots[$player] += $losebet;
                } else {
                    $origpots[$player] += $winbet;
                    $origpots[$i] += ($losebet - $winbet);
                    $errmsg .= 'partial win ' . $winbet . '<br>';
                    $errmsg .= 'opponent gets back ' . ($losebet - $winbet) . '<br>';
                }
            }
            $i++;
        }
        $z++;
    }
    $result = mysql_query("update " . DB_POKER . " set p1pot = '" . roundpot($origpots[1]) . "', p2pot = '" . roundpot($origpots[2]) . "', p3pot = '" . roundpot($origpots[3]) . "', p4pot = '" . roundpot($origpots[4]) . "', p5pot = '" . roundpot($origpots[5]) . "', p6pot = '" . roundpot($origpots[6]) . "' , p7pot = '" . roundpot($origpots[7]) . "' , p8pot = '" . roundpot($origpots[8]) . "' , p9pot = '" . roundpot($origpots[9]) . "' , p10pot = '" . roundpot($origpots[10]) . "'  where gameID = '" . $gameID . "' ");
}
function evaluatehand($player)
{
    global $cardr;
    global $tablecards;
    $points     = 0;
    $hand       = array(
        $tablecards[0],
        $tablecards[1],
        $tablecards[2],
        $tablecards[3],
        $tablecards[4],
        decrypt_card($cardr['p' . $player . 'card1']),
        decrypt_card($cardr['p' . $player . 'card2'])
    );
    $flush      = array();
    $values     = array();
    $sortvalues = array();
    $hcs        = array();
    $orig       = array(
        'J',
        'Q',
        'K',
        'A'
    );
    $change     = array(
        11,
        12,
        13,
        14
    );
    $i          = 0;
    while ($hand[$i] != '') {
        if (strlen($hand[$i]) == 2) {
            $flush[$i]      = substr($hand[$i], 1, 1);
            $values[$i]     = str_replace($orig, $change, substr($hand[$i], 0, 1));
            $sortvalues[$i] = $values[$i];
        } else {
            $flush[$i]      = substr($hand[$i], 2, 1);
            $values[$i]     = str_replace($orig, $change, substr($hand[$i], 0, 2));
            $sortvalues[$i] = $values[$i];
        }
        $i++;
    }
    sort($sortvalues);
    $pairmatch = '';
    $ispair    = array_count_values($values);
    $results   = array_count_values($ispair);
    $i         = 0;
    if ($results['2'] == 1)
        $res = '1pair';
    if ($results['2'] > 1)
        $res = '2pair';
    if ($results['3'] > 0)
        $res = '3s';
    if ($results['4'] > 0)
        $res = '4s';
    if ((($results['3'] > 0) && ($results['2'] > 0)) || ($results['3'] > 1))
        $res = 'FH';
    $i         = 2;
    $z         = 0;
    $y         = 0;
    $multipair = array();
    while ($i < 15) {
        if ($ispair[$i] == 2) {
            $multipair[$z] = $i;
            $highpair      = $i;
            $z++;
        }
        if ($ispair[$i] == 3) {
            $threepair[$y] = $i;
            $high3pair     = $i;
            $y++;
        }
        $i++;
    }
    $bw = 6;
    $n  = 0;
    while (($sortvalues[$bw] != '') && ($n < 5)) {
        if (!in_array($sortvalues[$bw], $multipair)) {
            $hcs[$n] = $sortvalues[$bw];
            $n++;
        }
        $bw--;
    }
    $h1    = $hcs[0];
    $h2    = $hcs[1] / 10;
    $h3    = $hcs[2] / 100;
    $h4    = $hcs[3] / 1000;
    $h5    = $hcs[4] / 10000;
    $high1 = $h1;
    $high2 = $h1 + $h2;
    $high3 = $h1 + $h2 + $h3;
    $high5 = $h1 + $h2 + $h3 + $h4 + $h5;
    if (($res == '1pair') || ($res == '2pair') || ($res == 'FH')) {
        if ($res == '1pair') {
            $points = (($highpair * 10) + ($high3));
        }
        if ($res == '2pair') {
            sort($multipair);
            $pairs = count($multipair);
            if ($pairs == 3) {
                $pr1 = $multipair[2];
                $pr2 = $multipair[1];
            } else {
                $pr1 = $multipair[1];
                $pr2 = $multipair[0];
            }
            $points = ((($pr1 * 100) + ($pr2 * 10)) + $high1);
        }
        if ($res == 'FH') {
            sort($multipair);
            sort($threepair);
            $pairs  = count($multipair);
            $threes = count($threepair);
            if ($pairs == 1) {
                $pr1 = $multipair[0];
            } else {
                $pr1 = $multipair[1];
            }
            if ($threes == 1) {
                $kry1 = $threepair[0];
            } else {
                $kry1 = $threepair[1];
                $kry2 = $threepair[0];
            }
            if ($kry2 > $pr1)
                $pr1 = $kry2;
            $points = (($kry1 * 1000000) + ($pr1 * 100000));
        }
    }
    if ($res == '3s') {
        $i = 2;
        while ($i < 15) {
            if ($ispair[$i] == 3) {
                $points = ($i * 1000) + $high2;
            }
            $i++;
        }
    }
    if ($res == '4s') {
        $i = 2;
        while ($i < 15) {
            if ($ispair[$i] == 4) {
                $points = $i * 10000000 + $high1;
            }
            $i++;
        }
    }
    $flushsuit = '';
    $isflush   = array_count_values($flush);
    if ($isflush['D'] > 4)
        $flushsuit = 'D';
    if ($isflush['C'] > 4)
        $flushsuit = 'C';
    if ($isflush['H'] > 4)
        $flushsuit = 'H';
    if ($isflush['S'] > 4)
        $flushsuit = 'S';
    if ($flushsuit != '') {
        $res        = $flushsuit . ' FLUSH DETECTED';
        $i          = 0;
        $x          = 0;
        $flusharray = array();
        while ($i < 7) {
            if ($flush[$i] == $flushsuit) {
                $flusharray[$x] = $values[$i];
                $x++;
            }
            $i++;
        }
        sort($flusharray);
        $basic    = 250000;
        $z        = count($flusharray) - 1;
        $c1       = $flusharray[$z] * 1000;
        $s1       = $flusharray[$z];
        $c2       = $flusharray[$z - 1] * 100;
        $s2       = $flusharray[$z - 1];
        $c3       = $flusharray[$z - 2] * 10;
        $s3       = $flusharray[$z - 2];
        $c4       = $flusharray[$z - 3];
        $s4       = $flusharray[$z - 3];
        $c5       = $flusharray[$z - 4] / 10;
        $s5       = $flusharray[$z - 4];
        $points   = $basic + $c1 + $c2 + $c3 + $c4 + $c5;
        $flushstr = false;
        $i        = 0;
        $x        = 0;
        while ($flusharray[$i] != '') {
            if ($flusharray[$i] == ($flusharray[$i + 1] - 1)) {
                $x++;
                $h = $flusharray[$i] + 1;
            }
            $i++;
        }
        if ($x > 3)
            $points = $h * 100000000;
        if (($x > 3) && ($h == 14))
            $points = $h * 1000000000;
    }
    if ($flushsuit == '') {
        $straight = false;
        $i        = 0;
        $count    = 0;
        if (($sortvalues[6] == 14) && ($sortvalues[0] == 2))
            $count = 1;
        while ($sortvalues[$i] != '') {
            if (($sortvalues[$i]) == ($sortvalues[$i + 1] - 1)) {
                $count++;
                if ($count > 3) {
                    $straight = true;
                    $res      = 'STRAIGHT';
                    $h        = $sortvalues[$i] + 1;
                    $points   = $h * 10000;
                }
            } elseif (($sortvalues[$i]) != ($sortvalues[$i + 1])) {
                $count = 0;
            }
            $i++;
        }
    }
    if ($res == '') {
        $points = $high5;
    }
    $tname = get_name($player);
    return $points;
}
function evaluatewin($player)
{
    global $tablecards;
    global $cardr;
    $points     = 0;
    $hand       = array(
        $tablecards[0],
        $tablecards[1],
        $tablecards[2],
        $tablecards[3],
        $tablecards[4],
        decrypt_card($cardr['p' . $player . 'card1']),
        decrypt_card($cardr['p' . $player . 'card2'])
    );
    $flush      = array();
    $values     = array();
    $sortvalues = array();
    $orig       = array(
        'J',
        'Q',
        'K',
        'A'
    );
    $change     = array(
        11,
        12,
        13,
        14
    );
    $i          = 0;
    while ($hand[$i] != '') {
        if (strlen($hand[$i]) == 2) {
            $flush[$i]      = substr($hand[$i], 1, 1);
            $values[$i]     = str_replace($orig, $change, substr($hand[$i], 0, 1));
            $sortvalues[$i] = $values[$i];
        } else {
            $flush[$i]      = substr($hand[$i], 2, 1);
            $values[$i]     = str_replace($orig, $change, substr($hand[$i], 0, 2));
            $sortvalues[$i] = $values[$i];
        }
        $i++;
    }
    sort($sortvalues);
    $pairmatch     = '';
    $ispair        = array_count_values($values);
    $results       = array_count_values($ispair);
    $i             = 0;
    $outputvalues  = array(
        '',
        '',
        '2\\\'s',
        '3\\\'s',
        '4\\\'s',
        '5\\\'s',
        '6\\\'s',
        '7\\\'s',
        '8\\\'s',
        '9\\\'s',
        '10\\\'s',
        'Jacks',
        'Queens',
        'Kings',
        'Aces'
    );
    $outputvalues2 = array(
        '',
        '',
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        'Jack',
        'Queen',
        'King',
        'Ace'
    );
    if ($results['2'] == 1)
        $res = '1pair';
    if ($results['2'] > 1)
        $res = '2pair';
    if ($results['3'] > 0)
        $res = '3s';
    if ($results['4'] > 0)
        $res = '4s';
    if ((($results['3'] > 0) && ($results['2'] > 0)) || ($results['3'] > 1))
        $res = 'FH';
    if (($res == '1pair') || ($res == '2pair') || ($res == 'FH')) {
        $i         = 2;
        $z         = 0;
        $y         = 0;
        $multipair = array();
        while ($i < 15) {
            if ($ispair[$i] == 2) {
                $multipair[$z] = $i;
                $z++;
            }
            if ($ispair[$i] == 3) {
                $threepair[$y] = $i;
                $y++;
            }
            $i++;
        }
        $HCS = array();
        if ($res == '1pair') {
            if ($multipair[0] == 2)
                $Xres = $outputvalues[2];
            if ($multipair[0] == 3)
                $Xres = $outputvalues[3];
            if ($multipair[0] == 4)
                $Xres = $outputvalues[4];
            if ($multipair[0] == 5)
                $Xres = $outputvalues[5];
            if ($multipair[0] == 6)
                $Xres = $outputvalues[6];
            if ($multipair[0] == 7)
                $Xres = $outputvalues[7];
            if ($multipair[0] == 8)
                $Xres = $outputvalues[8];
            if ($multipair[0] == 9)
                $Xres = $outputvalues[9];
            if ($multipair[0] == 10)
                $Xres = $outputvalues[10];
            if ($multipair[0] == 11)
                $Xres = $outputvalues[11];
            if ($multipair[0] == 12)
                $Xres = $outputvalues[12];
            if ($multipair[0] == 13)
                $Xres = $outputvalues[13];
            if ($multipair[0] == 14)
                $Xres = $outputvalues[14];
            $res = ' ' . WIN_PAIR . ' ' . $Xres;
        }
        if ($res == '2pair') {
            sort($multipair);
            $pairs = count($multipair);
            if ($pairs == 3) {
                $pr1 = $multipair[2];
                $pr2 = $multipair[1];
            } else {
                $pr1 = $multipair[1];
                $pr2 = $multipair[0];
            }
            if ($pr1 == 3)
                $Xres = $outputvalues[3];
            if ($pr1 == 4)
                $Xres = $outputvalues[4];
            if ($pr1 == 5)
                $Xres = $outputvalues[5];
            if ($pr1 == 6)
                $Xres = $outputvalues[6];
            if ($pr1 == 7)
                $Xres = $outputvalues[7];
            if ($pr1 == 8)
                $Xres = $outputvalues[8];
            if ($pr1 == 9)
                $Xres = $outputvalues[9];
            if ($pr1 == 10)
                $Xres = $outputvalues[10];
            if ($pr1 == 11)
                $Xres = $outputvalues[11];
            if ($pr1 == 12)
                $Xres = $outputvalues[12];
            if ($pr1 == 13)
                $Xres = $outputvalues[13];
            if ($pr1 == 14)
                $Xres = $outputvalues[14];
            if ($pr2 == 2)
                $Xres2 = $outputvalues[2];
            if ($pr2 == 3)
                $Xres2 = $outputvalues[3];
            if ($pr2 == 4)
                $Xres2 = $outputvalues[4];
            if ($pr2 == 5)
                $Xres2 = $outputvalues[5];
            if ($pr2 == 6)
                $Xres2 = $outputvalues[6];
            if ($pr2 == 7)
                $Xres2 = $outputvalues[7];
            if ($pr2 == 8)
                $Xres2 = $outputvalues[8];
            if ($pr2 == 9)
                $Xres2 = $outputvalues[9];
            if ($pr2 == 10)
                $Xres2 = $outputvalues[10];
            if ($pr2 == 11)
                $Xres2 = $outputvalues[11];
            if ($pr2 == 12)
                $Xres2 = $outputvalues[12];
            if ($pr2 == 13)
                $Xres2 = $outputvalues[13];
            $res = ' ' . WIN_2PAIR . ' ' . $Xres . ' & ' . $Xres2;
        }
        if ($res == 'FH') {
            $res = ' ' . WIN_FULLHOUSE;
        }
    }
    if ($res == '3s') {
        $i = 2;
        while ($i < 15) {
            if ($ispair[$i] == 3) {
                $res = ' ' . WIN_SETOF3 . ' ' . $outputvalues[$i];
            }
            $i++;
        }
    }
    if ($res == '4s') {
        while ($i < 15) {
            if ($ispair[$i] == 4) {
                $res = ' ' . WIN_SETOF4 . ' ' . $outputvalues[$i];
            }
            $i++;
        }
    }
    $flushsuit = '';
    $isflush   = array_count_values($flush);
    if ($isflush['D'] > 4)
        $flushsuit = 'D';
    if ($isflush['C'] > 4)
        $flushsuit = 'C';
    if ($isflush['H'] > 4)
        $flushsuit = 'H';
    if ($isflush['S'] > 4)
        $flushsuit = 'S';
    if ($flushsuit != '') {
        $i          = 0;
        $x          = 0;
        $flusharray = array();
        while ($i < 7) {
            if ($flush[$i] == $flushsuit) {
                $flusharray[$x] = $values[$i];
                $x++;
            }
            $i++;
        }
        sort($flusharray);
        $z        = count($flusharray) - 1;
        $res      = ' ' . $outputvalues2[$flusharray[$z]] . ' ' . WIN_FLUSH;
        $flushstr = false;
        $i        = 0;
        $x        = 0;
        while ($flusharray[$i] != '') {
            if ($flusharray[$i] == ($flusharray[$i + 1] - 1)) {
                $x++;
                $h = $flusharray[$i] + 1;
            }
            $i++;
        }
        if ($x > 3)
            $res = ' ' . $outputvalues2[$flusharray[$z]] . ' ' . WIN_STRAIGHT_FLUSH;
        if (($x > 3) && ($h == 14))
            $res = ' ' . WIN_ROYALFLUSH;
    }
    if ($flushsuit == '') {
        $lows     = false;
        $straight = false;
        $i        = 0;
        $count    = 0;
        if (($sortvalues[6] == 14) && ($sortvalues[0] == 2)) {
            $count = 1;
            $lows  = true;
        }
        while ($sortvalues[$i] != '') {
            if (($sortvalues[$i]) == ($sortvalues[$i + 1] - 1)) {
                $count++;
                if ($count > 3) {
                    $straight = true;
                    $h        = $sortvalues[$i] + 1;
                    $res      = ' ' . $outputvalues2[$h] . ' ' . WIN_STRAIGHT;
                    if (($lows == true) && ($h == 5) && ($count == 4))
                        $res = ' low straight';
                }
            } elseif (($sortvalues[$i]) != $sortvalues[$i + 1]) {
                $count = 0;
            }
            $i++;
        }
    }
    if ($res == '') {
        $hc1 = $sortvalues[6];
        $res = ' ' . $outputvalues2[$hc1] . ' ' . WIN_HIGHCARD;
    }
    return $res;
}
function find_rand($min = null, $max = null)
{
    static $seeded;
    if (!isset($seeded)) {
        mt_srand((double) microtime() * 1000000);
        $seeded = true;
    }
    if (isset($min) && isset($max)) {
        if ($min >= $max) {
            return $min;
        } else {
            return mt_rand($min, $max);
        }
    } else {
        return mt_rand();
    }
}
function validate_password($plain, $encrypted)
{
    if (($plain != '') && ($encrypted != '')) {
        $stack = explode(':', $encrypted);
        if (sizeof($stack) != 2)
            return false;
        if ((md5($stack[1] . 'pwd' . $plain) == $stack[0]) || (md5($stack[1] . $plain) == $stack[0])) {
            return true;
        }
    }
    return false;
}
function encrypt_password($plain)
{
    $password = '';
    for ($i = 0; $i < 10; $i++) {
        $password .= find_rand();
    }
    $salt     = substr(md5($password), 0, 2);
    $password = md5($salt . 'pwd' . $plain) . ':' . $salt;
    return $password;
}
function kry_officialstyle($plain, $encrypted)
{
    if (($plain != '') && ($encrypted != '')) {
        $stack = explode(':', $encrypted);
        if (sizeof($stack) != 2)
            return false;
        if (md5($stack[1] . 'officialstyle' . $plain) == $stack[0]) {
            return true;
        }
    }
    return false;
}
function randomcode($length, $type = 'mixed')
{
    if (($type != 'mixed') && ($type != 'chars') && ($type != 'digits'))
        return false;
    $rand_value = '';
    while (strlen($rand_value) < $length) {
        if ($type == 'digits') {
            $char = find_rand(0, 9);
        } else {
            $char = chr(find_rand(0, 255));
        }
        if ($type == 'mixed') {
            if (eregi('^[a-z0-9]$', $char))
                $rand_value .= $char;
        } elseif ($type == 'chars') {
            if (eregi('^[a-z]$', $char))
                $rand_value .= $char;
        } elseif ($type == 'digits') {
            if (ereg('^[0-9]$', $char))
                $rand_value .= $char;
        }
    }
    return $rand_value;
}
?>