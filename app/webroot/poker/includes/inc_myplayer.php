<?php
if ($valid == false)
    header('Location: lobby.php');
$time     = time();
$action   = (($_GET['action'] != '') ? addslashes($_GET['action']) : addslashes($_POST['action']));
$usr      = (($plyrname != '') ? $plyrname : addslashes($_POST['user']));
$pwd      = (($plyrname != '') ? 1 : addslashes($_POST['password']));
$pwd2     = addslashes($_POST['password2']);
$avatar   = addslashes($_POST['av']);
$avatar   = ((($avatar > 0) && ($avatar < 17)) ? 'avatar' . addslashes($_POST['av']) . '.jpg' : '');
$updateav = addslashes($_GET['newavatar']);
if (($updateav > 0) && (is_numeric($updateav)) && ($updateav < 17) && ($plyrname != '')) {
    $newav  = 'avatar' . addslashes($_GET['newavatar']) . '.jpg';
    $result = mysql_query("update " . DB_PLAYERS . " set avatar = '" . $newav . "' where username = '" . $plyrname . "' ");
}
if (($_POST['update'] == 'image') && ($plyrname != '')) {
    $maxsize     = 300000;
    $target_path = "images/avatars/";
    $target_path = $target_path . basename($_FILES['uploadedfile']['name']);
    $remote_img  = $_FILES['uploadedfile']['name'];
    $tmp_img     = $_FILES['uploadedfile']['tmp_name'];
    $filesize    = filesize($_FILES['uploadedfile']['tmp_name']);
    $_FILES['uploadedfile']['tmp_name'];
    $bad_msg = false;
    list($width, $height, $type, $attr) = getimagesize($tmp_img);
    if (isset($_FILES['uploadedfile']['tmp_name']) && ($type != 2)) {
        $bad_msg  = true;
        $bad_msgs = STATS_MSG_FILE_FORMAT;
    }
    if ($filesize > $maxsize) {
        $bad_msg  = true;
        $bad_msgs = STATS_MSWG_FILE_SIZE;
    }
    if ($bad_msg == false) {
        $target_file = $plyrname . '.jpg';
        $target_path = $tmp_img;
        $result      = mysql_query("update " . DB_PLAYERS . " set avatar = '" . $target_file . "' where username = '" . $plyrname . "' ");
        $add         = $target_path;
        $tsrc        = 'images/avatars/';
        $im          = ImageCreateFromJPEG($add);
        $n_width     = 200;
        $n_height    = 200;
        $newimage    = imagecreatetruecolor($n_width, $n_height);
        imageCopyResized($newimage, $im, 0, 0, 0, 0, $n_width, $n_height, $width, $height);
        ImageJpeg($newimage, $tsrc . $target_file);
        if (file_exists($tmp_img))
            unlink($tmp_img);
    }
}
if (($action == 'updatepwd') && ($plyrname != '')) {
    $error = false;
    $old   = addslashes($_POST['old']);
    $pwd   = addslashes($_POST['pwd']);
    $pwd2  = addslashes($_POST['pwd2']);
    if (($pwd == '') || ($pwd2 == '') || ($old == '')) {
        $error   = true;
        $message = STATS_MSG_MISSING_DATA;
    } elseif (eregi('[^a-zA-Z0-9_]', $pwd)) {
        $error   = true;
        $message = STATS_MSG_PWD_CHARS;
        $pwd     = '';
        $pwd2    = '';
    } elseif ($pwd != $pwd2) {
        $error   = true;
        $message = STATS_MSG_PWD_CONFIRM;
        $pwd     = '';
        $pwd2    = '';
    } elseif ((strlen($pwd) < 5) || (strlen($pwd) > 10)) {
        $error   = true;
        $message = STATS_MSG_PWD_LENGTH;
        $pwd     = '';
        $pwd2    = '';
    }
    $pwdq = mysql_fetch_array(mysql_query("select password from " . DB_PLAYERS . " where username = '" . $usr . "' "));
    $orig = $pwdq['password'];
    if (validate_password($old, $orig) == false) {
        $error   = true;
        $message = STATS_MSG_PWD_INCORRECT;
    }
    if ($error == false) {
        $pwd    = encrypt_password($pwd);
        $result = mysql_query("update " . DB_PLAYERS . " set password = '" . $pwd . "' where username = '" . $plyrname . "' ");
    }
}
$userBetscheme = mysql_query("select balance from " . DB_USERS . " where username = '" . $plyrname . "' ");
$userBetscheme = mysql_fetch_array($userBetscheme);
$statsq            = mysql_query("select * from " . DB_STATS . " where player = '" . $plyrname . "' ");
$statsr            = mysql_fetch_array($statsq);
$gamesplayed       = $statsr['gamesplayed'];
$tournamentswon    = $statsr['tournamentswon'];
$tournamentsplayed = $statsr['tournamentsplayed'];
$handsplayed       = $statsr['handsplayed'];
$handswon          = $statsr['handswon'];
$winnings          = floatval($userBetscheme['balance'])*1000;
$rank              = $statsr['rank'];
$S_allin           = $statsr['allin'];
$S_raise           = $statsr['bet'];
$S_check           = $statsr['checked'];
$S_call            = $statsr['called'];
$S_fold_pf         = $statsr['fold_pf'];
$S_fold_f          = $statsr['fold_f'];
$S_fold_t          = $statsr['fold_t'];
$S_fold_r          = $statsr['fold_r'];
$allfolds          = ($S_fold_pf + $S_fold_f + $S_fold_t + $S_fold_r);
$allmoves          = ($allfolds + $S_call + $S_check + $S_raise + $S_allin);
$handsperc         = (($handsplayed > 0) ? number_format((($handswon / $handsplayed) * 100), 1) . '%' : '0%');
$tperc             = (($tournamentsplayed > 0) ? number_format((($tournamentswon / $tournamentsplayed) * 100), 1) . '%' : '0%');
$foldperc          = (($allmoves > 0) ? number_format((($allfolds / $allmoves) * 100), 1) . '%' : '0%');
$callperc          = (($allmoves > 0) ? number_format((($S_call / $allmoves) * 100), 1) . '%' : '0%');
$raiseperc         = (($allmoves > 0) ? number_format((($S_raise / $allmoves) * 100), 1) . '%' : '0%');
$checkperc         = (($allmoves > 0) ? number_format((($S_check / $allmoves) * 100), 1) . '%' : '0%');
$allinperc         = (($allmoves > 0) ? number_format((($S_allin / $allmoves) * 100), 1) . '%' : '0%');
$foldpfperc        = (($allfolds > 0) ? number_format((($S_fold_pf / $allfolds) * 100), 1) . '%' : '0%');
$foldfperc         = (($allfolds > 0) ? number_format((($S_fold_f / $allfolds) * 100), 1) . '%' : '0%');
$foldtperc         = (($allfolds > 0) ? number_format((($S_fold_t / $allfolds) * 100), 1) . '%' : '0%');
$foldrperc         = (($allfolds > 0) ? number_format((($S_fold_r / $allfolds) * 100), 1) . '%' : '0%');
$baseq             = mysql_query("select datecreated, lastlogin from " . DB_PLAYERS . " where username = '" . $plyrname . "' ");
$baser             = mysql_fetch_array($baseq);
$created           = date("m-d-Y", $baser['datecreated']);
$lastlogin         = date("m-d-Y", $baser['lastlogin']);
if ((RENEW == 1) && ($winnings == 0) && ($action == 'renew')) {
    $result = mysql_query("update " . DB_STATS . " set winpot = '10000' where player = '" . $plyrname . "' ");
    header('Location: myplayer.php');
}

function display_ava_profiles($usr)
{
    $time   = time();
    $usrq   = mysql_query("select avatar from " . DB_PLAYERS . " where username = '" . $usr . "' ");
    $usrr   = mysql_fetch_array($usrq);
    $avatar = '<img class="img-circle img-responsive" src="images/avatars/' . $usrr['avatar'] . '?x=' . $time . '" border="0">';
    return $avatar;
}
?>