<?php
if ($valid == false)
    header('Location: login.php');
$lim  = 99999999999999999999;
$posi = 1;
$staq = mysql_query("select " . DB_STATS . ".winpot, " . DB_PLAYERS . ".banned from " . DB_STATS . ", " . DB_PLAYERS . " where " . DB_PLAYERS . ".username = " . DB_STATS . ".player and " . DB_PLAYERS . ".banned = '0' order by " . DB_STATS . ".winpot desc");
while ($star = mysql_fetch_array($staq)) {
    if ($star['winpot'] < $lim) {
        $lastdig    = substr($posi, -1, 1);
        $prelastdig = ((strlen($posi) < 2) ? '' : substr($posi, -2, 1));
        $rank       = $posi . 'th';
        if (($lastdig == 1) && ($prelastdig != 1))
            $rank = $posi . 'st';
        if ($lastdig == 2)
            $rank = $posi . 'nd';
        if ($lastdig == 3)
            $rank = $posi . 'rd';
        if ($star['winpot'] < 10500)
            $rank = 'unranked';
        $lim    = $star['winpot'];
        $result = mysql_query("update " . DB_STATS . " set rank = '" . $rank . "' where winpot = '" . $star['winpot'] . "' ");
        $posi++;
    }
}

function display_ava_rankings($usr)
{
    $time   = time();
    $usrq   = mysql_query("select avatar from " . DB_PLAYERS . " where username = '" . $usr . "' ");
    $usrr   = mysql_fetch_array($usrq);
    $avatar = '<img class="img-circle img-responsive" src="images/avatars/' . $usrr['avatar'] . '?x=' . $time . '" border="0">';
    return $avatar;
}
?>