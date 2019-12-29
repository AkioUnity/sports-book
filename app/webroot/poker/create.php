<?php
header('Location: lobby.php');
die();
require('includes/gen_inc.php'); 
if($valid == true) header('Location: index.php');
$time = time();

$action = addslashes($_POST['action']);
$usr = addslashes($_POST['user']);
$pwd = addslashes($_POST['password']);
$pwd2 = addslashes($_POST['password2']);
$avatar = addslashes($_POST['av']);
$avatar = ((($avatar > 0) && ($avatar < 17))? 'avatar'.addslashes($_POST['av']).'.jpg' : '');

if($action == 'createplayer'){
    $ip = $_SERVER['REMOTE_ADDR']; 
    $email = addslashes($_POST['email']);
$Semail = (($email == '')? 99 : $email);
$banq = mysql_query("select ipaddress from ".DB_PLAYERS." where (ipaddress = '".$ip."' or email = '".$Semail."' ) and banned = '1' ");
$ws = substr_count($usr, 'w');
$ms = substr_count($usr, 'm');
$Ws = substr_count($usr, 'M');
$Ms = substr_count($usr, 'W');
$longchars = $ws+$ms+$Ws+$Ms;
$error = false;
$sessname = addslashes($_SESSION[SESSNAME]);

$userq = mysql_query("select username from ".DB_PLAYERS." where username = '".$usr."' ");
$sessq = mysql_query("select username from ".DB_PLAYERS." where sessname = '".$sessname."' ");

if((mysql_num_rows($banq) > 0) && ($ADMIN == false)){
$error = true;
      $message = CREATE_MSG_IP_BANNED;
}elseif((($pwd == '') || ($pwd2 == '') || ($usr=='') || (($email == '') && (EMAILMOD == 1))) && (MEMMOD != 1)){
$error = true;
      $message = CREATE_MSG_MISSING_DATA;
 }elseif ((strlen($sessname) < 2) && (MEMMOD == 1)){
      $error = true;
      $message = CREATE_MSG_AUTHENTICATION_ERROR;
}elseif((mysql_num_rows($sessq) > 0) && (MEMMOD == 1)){
      $error = true;
      $message = CREATE_MSG_ALREADY_CREATED;
}else{

if  ( ((!preg_match("(^[-\w\.]+@([-a-z0-9]+\.)+[a-z]{2,4}$)i", $email)) || (strlen($email) < 8)) && ((APPMOD == 1) || (EMAILMOD == 1))){
      $error = true;
      $message .= CREATE_MSG_INVALID_EMAIL.'<BR>';
} 

if(mysql_num_rows($userq) != 0){
$error = true;
      $message .= CREATE_MSG_USERNAME_TAKEN.'<BR>';
}

if(($longchars  > 4) && (strlen($usr) > 6)){ 
$error = true;
      $message .= CREATE_MSG_USERNAME_MWCHECK .'<BR>';
} 

if(eregi('[^a-zA-Z0-9_]', $usr)){
$error = true;
      $message .= CREATE_MSG_USERNAME_CHARS.'<BR>';
}

if((eregi('[^a-zA-Z0-9_]', $pwd)) && (MEMMOD != 1)){
$error = true;
$pwd = '';
$pwd2 = '';
      $message .= CREATE_MSG_PASSWORD_CHARS.'<BR>';
} 

if(($pwd != $pwd2) && (MEMMOD != 1)){
$error = true;
$pwd = '';
$pwd2 = '';
      $message .= CREATE_MSG_PASSWORD_CHECK.'<BR>';
} 

if ((strlen($usr) < 5) || (strlen($usr) > 10)) {
      $error = true;
      $message .= CREATE_MSG_USERNAME_LENGTH.'<BR>';
    }

if (((strlen($pwd) < 5) || (strlen($pwd) > 10))  && (MEMMOD != 1)){
      $error .= true;
$pwd = '';
$pwd2 = '';
      $message = CREATE_MSG_PASSWORD_LENGTH.'<BR>';
    }

}


if ($error == false){
$GUID = randomcode(32);
$pwd = ((MEMMOD == 1)? '' : encrypt_password($pwd));
$approve = ((APPMOD != 0)? 1 : 0);
$winpot = ((RENEW == 1)? 10000 : 0); 
 $result = mysql_query("insert into ".DB_PLAYERS." set banned = '0', username = '".$usr."', approve = '".$approve."', email = '".$email."', GUID = '".$GUID."', lastlogin = '".$time."' , datecreated = '".$time."' , password = '".$pwd."', sessname = '".$sessname."', avatar = 'avatar.jpg', ipaddress = '".$ip."' ");
 $result = mysql_query("insert into ".DB_STATS." set player = '".$usr."', winpot = '".$winpot."' ");

if(APPMOD == 1){ // REQUIRE EMAIL VERIFICATION
$appcode =  randomcode(16);
$result = mysql_query("update ".DB_PLAYERS." set code = '".$appcode."' where username = '".$usr."' ");
$mailfrom = $_SERVER['HTTP_HOST'];
$mailfrom = str_replace("www.","",$mailfrom);
$mailf = explode('/',$mailfrom);
$mail = 'support@'.$mailf[0];
$from_header = 'From: '.$mail. "\r\n" .
   'Reply-To: '.$mail . "\r\n" .
   'X-Mailer: PHP/' . phpversion();
$subject = 'Player Activation Email';
$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
$url = str_replace("lobby.php","approve.php?&approval=".$appcode,$url);
$contents = CREATE_APPROVAL_EMAIL_CONTENT .' ' .$url;
$to =  $email;
   mail($to, $subject, $contents, $from_header);
 $url = 'index.php';
 echo '<SCRIPT LANGUAGE="JavaScript">';
echo 'alert(\''.CREATE_APPROVAL_EMAIL_ALERT.'\'); ';
echo 'parent.document.location.href = "'.$url.'";'; 
echo '</SCRIPT>';
die(); 
}else{
$_SESSION['SGUID'] = $GUID;
$_SESSION['playername'] = $usr;     
header('Location: lobby.php');
die(); 
      }
}

}
?>

<style>
/* centered columns styles */
.row-centered {
    text-align:center;
}
.col-centered {
    display:inline-block;
    float:none;
    /* reset the text-align */
    text-align:left;
    /* inline-block space fix */
    margin-right:-4px;
}
.col-fixed {
    /* custom width */
    width:320px;
}
.col-min {
    /* custom min width */
    min-width:320px;
}
.col-max {
    /* custom max width */
    max-width:320px;
}
</style>

<?php include 'templates/header.php'; ?>

    <div class="container">

      <div class="row">  

        <div class="col-md-12" align="center">

            <?php if($message != ''){ ?>
			<div class="alert alert-warning"><?php echo $message; ?></div>
            <?php } if(MEMMOD == 0){ ?>
            
		<div class="row row-centered">
		  <div class="col-centered col-md-5">
			<legend>&nbsp;<?php echo BOX_CREATE_NEW_PLAYER; ?>&nbsp;</legend> 
                  <form name="createplyr" action="create.php" method="post">
                  <input placeholder="<?php echo CREATE_PLAYER_NAME; ?>" type="text" size="12" maxlength="10" name="user" class="form-control" value="<?php echo $usr; ?>" />
                  <br> 
                  <input placeholder="<?php echo CREATE_PLAYER_PWD; ?> <?php echo CREATE_PLAYER_CHAR_LIMIT; ?>" type="password" size="12" maxlength="10" name="password" class="form-control" />
                  <br>
                  <input placeholder="<?php echo CREATE_PLAYER_CONFIRM; ?> <?php echo CREATE_PLAYER_CHAR_LIMIT; ?>" type="password" size="12" maxlength="10" name="password2" class="form-control" />
                  <input type="hidden" name="av" id="av" value="">
				  <input type="hidden" name="action" value="createplayer">
				  <br>
				  
				  <?php if(EMAILMOD == 1){ ?> 
				  <input placeholder="<?php echo CREATE_PLAYER_EMAIL; ?>" type="text" size="37" maxlength="64" name="email" class="form-control" value="<?php echo $email; ?>" />
				  <?php } ?>
				  <br>
                  <input type="submit" name="Submit2" value="<?php echo BUTTON_SUBMIT; ?>" class="btn btn-success btn-block">
                  </form>
		  </div>
		</div>
		
            <?php }else{ ?>
            <table width="400" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#333333">
              <tr> 
                <td class="fieldsethead"> <fieldset  class="yellowborder"> <legend>&nbsp;<?php echo BOX_CREATE_NEW_PLAYER; ?>&nbsp;</legend> 
                  <form name="createplyr" action="create.php" method="post">
                    <table width="400" border="0" cellspacing="0" cellpadding="5" align="center">
                      <tr> 
                        <td colspan="2"><span class="fieldsetheadcontent"><br>
                          <?php echo CREATE_PLAYER_NAME; ?>
                          </span>
<input type="text" size="12" maxlength="10" name="user" class="fieldsetheadinputs" value="<?php echo $usr; ?>" />
                        </td>
                      </tr>
                      <tr> 
                        <td colspan="2" class="fieldsethead"> 
                          <p> 
                            <input type="hidden" name="av" id="av" value="">
                            <input type="hidden" name="action" value="createplayer">
                          </p>
                          <fieldset><legend>&nbsp;<?php echo BOX_CREATE_NEW_AVATAR; ?>&nbsp;</legend> 
                          <table border="0" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" align="center">
                            <tr align="center"> 
                              <td id="avatar1" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'" ><img src="images/avatars/avatar1.jpg" width="44" height="50" onClick="avatar('1')"></td>
                              <td id="avatar2" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar2.jpg" width="44" height="50" onClick="avatar('2')"></td>
                              <td id="avatar3" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar3.jpg" width="44" height="50" onClick="avatar('3')"></td>
                              <td id="avatar4" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar4.jpg" width="44" height="50" onClick="avatar('4')"></td>
                              <td id="avatar5" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar5.jpg" width="44" height="50" onClick="avatar('5')"></td>
                              <td id="avatar6" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar6.jpg" width="44" height="50" onClick="avatar('6')"></td>
                              <td id="avatar7" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar7.jpg" width="44" height="50" onClick="avatar('7')"></td>
                              <td id="avatar8" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar8.jpg" width="44" height="50" onClick="avatar('8')"></td>
                            </tr>
                            <tr align="center"> 
                              <td id="avatar9" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar9.jpg" width="44" height="50" onClick="avatar('9')"></td>
                              <td id="avatar10" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar10.jpg" width="44" height="50" onClick="avatar('10')"></td>
                              <td id="avatar11" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar11.jpg" width="44" height="50" onClick="avatar('11')"></td>
                              <td id="avatar12" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar12.jpg" width="44" height="50" onClick="avatar('12')"></td>
                              <td id="avatar13" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar13.jpg" width="44" height="50" onClick="avatar('13')"></td>
                              <td id="avatar14" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar14.jpg" width="44" height="50" onClick="avatar('14')"></td>
                              <td id="avatar15" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar15.jpg" width="44" height="50" onClick="avatar('15')"></td>
                              <td id="avatar16" onMouseOver="this.bgColor = '#FF0000'"  onMouseOut="this.bgColor = '#000000'"><img src="images/avatars/avatar16.jpg" width="44" height="50" onClick="avatar('16')"></td>
                            </tr>
                          </table>
                          </fieldset></td>
                      </tr>
                      <tr> 
                        <td colspan="2" align="right"> 
                          <input type="submit" name="Submit2" value="<?php echo BUTTON_SUBMIT; ?>" class="betbuttons">
                        </td>
                      </tr>
                    </table>
                  </form>
                  </fieldset></td>
              </tr>
            </table>
            <?php }?>

		</div>
                
        </div>
        
      </div>
      
    </div>

<?php include 'templates/footer.php'; ?>

</body>
</html>
