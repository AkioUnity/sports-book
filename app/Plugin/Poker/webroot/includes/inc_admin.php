<?php
if ($valid == false)
    header('Location: login.php');
if ($ADMIN == false)
    header('Location: index.php');
$usr    = addslashes($_POST['player']);
$tname  = addslashes($_POST['tname']);
$tmax   = addslashes($_POST['tmax']);
$tmin   = addslashes($_POST['tmin']);
$action = addslashes($_POST['action']);
if (($action == 'createtable') && ($tname != '') && ($tmin <= $tmax)) {
    $ttype  = addslashes($_POST['ttype']);
    $tstyle = addslashes($_POST['tstyle']);
    $tmax   = addslashes($_POST['tmax']);
    $tmin   = addslashes($_POST['tmin']);
    $result = mysql_query("insert into " . DB_POKER . " set tablename = '" . $tname . "',  tablelow = '" . $tmin . "',  tablelimit = '" . $tmax . "',  tabletype = '" . $ttype . "',  tablestyle = '" . $tstyle . "' ");
}
$delete = addslashes($_GET['delete']);
if ((is_numeric($delete)) && (isset($delete))) {
    $result = mysql_query("delete from  " . DB_POKER . " where gameID = '" . $delete . "' ");
    $result = mysql_query("delete from " . DB_LIVECHAT . " where gameID = '" . $delete . "' ");
    $result = mysql_query("update " . DB_PLAYERS . " set vID = '', gID = '' where vID = '" . $delete . "' ");
}
if ($action == 'install') {
    $nam = addslashes($_POST['name']);
    $lic = addslashes($_POST['lic']);
    $sq  = mysql_query("select style_name from styles where style_name = '" . $nam . "' ");
    if (mysql_num_rows($sq) > 0) {
        $msg = ADMIN_MSG_STYLE_INSTALLED;
    } elseif (($nam == '') || ($lic == '')) {
        $msg = ADMIN_MSG_MISSING_DATA;
    } elseif (kry_officialstyle($nam, $lic) == false) {
        $msg = ADMIN_MSG_INVALID_CODE;
    } else {
        $result = mysql_query("insert into " . DB_STYLES . " set style_name = '" . $nam . "', style_lic = '" . $lic . "' ");
        header('Location : admin.php?admin=styles');
    }
}
if ($usr != '') {
    $uq = mysql_query("select email from " . DB_PLAYERS . " where username = '" . $usr . "' ");
    $ur = mysql_fetch_array($uq);
    $em = $ur['email'];
    if ($action == 'ban') {
        if ($em != '')
            $result = mysql_query("update " . DB_PLAYERS . " set banned = '1' where email = '" . $em . "' ");
        $result = mysql_query("update " . DB_PLAYERS . " set banned = '1' where username = '" . $usr . "' ");
    } elseif ($action == 'unban') {
        if ($em != '')
            $result = mysql_query("update " . DB_PLAYERS . " set banned = '0' where email = '" . $em . "' ");
        $result = mysql_query("update " . DB_PLAYERS . " set banned = '0' where username = '" . $usr . "' ");
    } elseif ($action == 'reset') {
        $result = mysql_query("update " . DB_STATS . " set winpot = '0',rank = '',gamesplayed = '0', tournamentswon = '0', tournamentsplayed = '0', handsplayed = '0', handswon = '', bet = '0', checked = '0', called = '0', allin = '0', fold_pf = '0', fold_f = '0', fold_t = '0', fold_r = '0' where player = '" . $usr . "' ");
    } elseif ($action == 'approve') {
        $result = mysql_query("update " . DB_PLAYERS . " set approve = '0' where username = '" . $usr . "' ");
    } elseif ($action == 'delete') {
        $result = mysql_query("delete from " . DB_PLAYERS . " where username = '" . $usr . "' ");
        $result = mysql_query("delete from  " . DB_STATS . " where player = '" . $usr . "' ");
        if (file_exists('images/avatars/' . $usr . '.jpg'))
            unlink('images/avatars/' . $usr . '.jpg');
    }
}
if ($action == 'update') {
    $title = addslashes($_POST['title']);
    if ($title == '')
        $title = 'Texas Holdem Poker';
    $emailmode = addslashes($_POST['emailmode']);
    if ($emailmode != 1)
        $emailmode = 0;
    $ipcheck = addslashes($_POST['ipcheck']);
    if ($ipcheck != 0)
        $ipcheck = 1;
    $renewbutton = addslashes($_POST['renew']);
    if ($renewbutton != 0)
        $renewbutton = 1;
    $appmode = addslashes($_POST['appmode']);
    if (($appmode != 1) && ($appmode != 2))
        $appmode = 0;
    if ($appmode == 1)
        $emailmode = 1;
    $memmode   = addslashes($_POST['memmode']);
    $kickarray = array(
        3,
        5,
        7,
        10,
        15
    );
    $kick      = addslashes($_POST['kick']);
    if (!in_array($kick, $kickarray))
        $kick = 5;
    $movearray = array(
        10,
        15,
        20,
        27
    );
    $move      = addslashes($_POST['move']);
    if (!in_array($move, $movearray))
        $move = 20;
    $showdownarray = array(
        3,
        4,
        5,
        7,
        10
    );
    $showdown      = addslashes($_POST['showdown']);
    if (!in_array($showdown, $showdownarray))
        $showdown = 7;
    $deletearray = array(
        30,
        60,
        90,
        180,
        'never'
    );
    $delete      = addslashes($_POST['delete']);
    if (!in_array($delete, $deletearray))
        $delete = 90;
    $waitarray = array(
        0,
        10,
        15,
        20,
        25
    );
    $wait      = addslashes($_POST['wait']);
    if (!in_array($wait, $waitarray))
        $wait = 20;
    $disconarray = array(
        15,
        30,
        60,
        90,
        120
    );
    $discon      = addslashes($_POST['disconnect']);
    if (!in_array($discon, $disconarray))
        $discon = 60;
    $ssizearray = array(
        'tiny',
        'low',
        'med',
        'high'
    );
    $ssize      = addslashes($_POST['stakesize']);
    if (!in_array($ssize, $ssizearray))
        $ssize = med;
    $sess   = addslashes($_POST['session']);
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $title . "' where setting = 'title' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $appmode . "' where setting = 'appmod' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $emailmode . "' where setting = 'emailmod' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $ipcheck . "' where setting = 'ipcheck' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $memmode . "' where setting = 'memmod' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $kick . "' where setting = 'kicktimer' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $showdown . "' where setting = 'showtimer' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $move . "' where setting = 'movetimer' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $delete . "' where setting = 'deletetimer' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $wait . "' where setting = 'waitimer' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $sess . "' where setting = 'session' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $ssize . "' where setting = 'stakesize' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $discon . "' where setting = 'disconnect' ");
    $result = mysql_query(" update " . DB_SETTINGS . " set Xvalue = '" . $renewbutton . "' where setting = 'renew' ");
    header('Location: admin.php?admin=settings&ud=1');
}
$adminview = addslashes($_GET['admin']);
?>