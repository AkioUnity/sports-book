<?php
require('gen_inc.php');
if ($valid == false)
    die();
?>

      var gameslist = '<';
gameslist += 'table border="0" cellspacing="0" cellpadding="3" width="100%" class="table table-hover">';


<?php
$tableq = mysql_query("select gameID, p1name, p2name,p3name,p4name,p5name, p6name,p7name,p8name,p9name,p10name, p1pot, p2pot, p3pot, p4pot, p5pot, p6pot, p7pot, p8pot, p9pot, p10pot, tablename,tablelimit ,tabletype,hand,tablelow, pot from " . DB_POKER . " order by tablelimit asc ");
while ($tabler = mysql_fetch_array($tableq)) {
    $i        = 1;
    $x        = 0;
    $time     = time();
    $ktimer   = DISCONNECT;
    $timekick = $time - $ktimer;
    $gamID    = $tabler['gameID'];
    while ($i < 11) {
        if (strlen($tabler['p' . $i . 'name']) != '') {
            $usr = $tabler['p' . $i . 'name'];
            $pot = $tabler['p' . $i . 'pot'];
            $ttq = mysql_query("select gID, timetag from " . DB_PLAYERS . " where username = '" . $usr . "'  ");
            $ttr = mysql_fetch_array($ttq);
            if (($ttr['timetag'] < $timekick) || ($ttr['gID'] != $gamID)) {
                $result = mysql_query("update " . DB_POKER . " set p" . $i . "name = '', p" . $i . "bet = '', p" . $i . "pot = '' , lastmove = '" . ($time + 1) . "' where gameID = '" . $gamID . "' ");
                $moneyWrite = $pot == 0 ? 0 : $pot/1000;
                $result = mysql_query("update " . DB_USERS . " set balance = balance + " . ($moneyWrite) . " where username  = '" . $usr . "'  ");
                $result = mysql_query("update " . DB_PLAYERS . " set gID = '' where username = '" . $usr . "' ");
            }
            $x++;
        }
        $i++;
    }
    $tablename       = $tabler['tablename'];
    $min             = money_small($tabler['tablelow']);
    $tablelimit      = $tabler['tablelimit'];
    $max             = money_small($tablelimit);
    $gID             = $tabler['gameID'];
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
    if ($tabler['tabletype'] == 't') {
        $BB = money_small(50 * $tablemultiplier) . '-' . money_small(50 * $tablemultiplier * 9);
        $SB = money_small(25 * $tablemultiplier) . '-' . money_small(25 * $tablemultiplier * 9);
    } else {
        $BB = money_small(200 * $tablemultiplier);
        $SB = money_small(100 * $tablemultiplier);
    }
    $tableplayers = $x . '/10';
    $NEW_GAME     = addslashes(NEW_GAME);
    $PLAYING      = addslashes(PLAYING);
    $tablestatus  = (($tabler['hand'] == '') ? $NEW_GAME : $PLAYING);
    $TOURNAMENT   = addslashes(TOURNAMENT);
    $SITNGO       = addslashes(SITNGO);
    $tabletype    = (($tabler['tabletype'] == 't') ? $TOURNAMENT : $SITNGO);
    $buyin        = (($tabler['tabletype'] == 't') ? $max : $min . '/' . $max);
?>
        gameslist += '<tr style="cursor: pointer;" onClick="selectgame(\'lobby.php?gameID=<?php
    echo $gID;
?>\');" class="table">'; 

          gameslist += '<td class="smllfontwhite" width="120"><?php
    echo $tablename;
?></td><td width="50" align="center" class="smllfontwhite"><?php
    echo $tableplayers;
?></td><td class="smllfontwhite" width="80" align="center"><?php
    echo $tabletype;
?></td><td class="smllfontwhite" width="90" align="center"><?php
    echo $buyin;
?></td><td class="smllfontwhite" width="90" align="center"><?php
    echo $SB;
?></td><td class="smllfontwhite" width="90" align="center"><?php
    echo $BB;
?></td><td class="smllfontwhite" width="80" align="center"><?php
    echo $tablestatus;
?></td></tr>';
        <?php
}
?>
      gameslist += '</table>';
document.getElementById('gamelist').innerHTML = gameslist;
