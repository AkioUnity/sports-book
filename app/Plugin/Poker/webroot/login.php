<?php
header('Location: lobby.php');
die();
require('includes/gen_inc.php');
$action = (($_GET['action'] != '')? addslashes($_GET['action']) : addslashes($_POST['action']));
$usr = addslashes($_POST['usr']);
$pwd = addslashes($_POST['pwd']);
$time = time();
$ip = $_SERVER['REMOTE_ADDR']; 

if(($action == 'process') && ($usr != '') && ($pwd != '')){
$GUID = randomcode(32);
$pwdq = mysql_fetch_array(mysql_query("select password, banned, approve from ".DB_PLAYERS." where username = '".$usr."' "));
$orig = $pwdq['password'];
$banned = $pwdq['banned'];
$approve = $pwdq['approve'];
if($approve == 1){
$msg = LOGIN_MSG_APPROVAL;
}elseif($banned == 1){
$msg = LOGIN_MSG_BANNED;
}elseif(validate_password($pwd,$orig) == true){
session_start();
$_SESSION['playername'] = $usr; 
$_SESSION['SGUID'] = $GUID;
 $result = mysql_query("update ".DB_PLAYERS." set ipaddress = '".$ip."', lastlogin = '".$time."' , GUID = '".$GUID."' where username = '".$usr."' ");
header('Location: lobby.php');
}else{
$msg = LOGIN_MSG_INVALID;
}
}

?>

<?php include 'templates/header.php'; ?>

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

    <div class="container">

      <div class="row">  

        <div class="col-md-12" align="center">
        
    <h3><?php echo LOGIN; ?></h3>
    
      <?php if($msg != ''){ ?>
      <div class="alert alert-warning"><?php echo $msg; ?></div>
      <?php } ?>
      
      <div class="row row-centered">
		  <div class="col-centered col-md-5">
            <form action="login.php" method="post" name="login">
				
				<input placeholder="<?php echo LOGIN_USER; ?>" type="text" size="12" maxlength="10" name="usr" class="form-control" /><br>
				<input type="hidden" name="action" value="process">
                <input placeholder="<?php echo LOGIN_PWD; ?>" type="password" size="12" maxlength="10" name="pwd" class="form-control" />
                <br>
                <input type="submit" name="Login" value="<?php echo BUTTON_LOGIN; ?>" class="btn btn-success btn-block">
            </form>
            
			<a href="create.php" target="_self" class="btn btn-default btn-block"><?php echo LOGIN_NEW_PLAYER; ?></a>
			<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#resetpw">Reset Password</button>
		  </div>
      </div>
      	
        </div>
        
      </div>
      
    </div>

<!-- Modal -->
<div class="modal fade" id="resetpw" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Reset your Password</h4>
      </div>
      <div class="modal-body">
        <?php

//this function will display error messages in alert boxes, used for login forms so if a field is invalid it will still keep the info
//use error('foobar');
function error($msg) {
    ?>
    <html>
    <head>
    <script language="JavaScript">
    <!--
        alert("<?=$msg?>");
        history.back();
    //-->
    </script>
    </head>
    <body>
    </body>
    </html>
    <?
    exit;
}

//This functions checks and makes sure the email address that is being added to database is valid in format. 
function check_email_address($email) {
  // First, we check that there's one @ symbol, and that the lengths are right
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
     if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
      return false;
    }
  }  
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}


if (isset($_POST['submit'])) {
	
	if ($_POST['forgotpassword']=='') {
		error('Please enter your email address.');
	}
	if(get_magic_quotes_gpc()) {
		$forgotpassword = htmlspecialchars(stripslashes($_POST['forgotpassword']));
	} 
	else {
		$forgotpassword = htmlspecialchars($_POST['forgotpassword']);
	}
	//Make sure it's a valid email address, last thing we want is some sort of exploit!
	if (!check_email_address($_POST['forgotpassword'])) {
  		error('Email Not Valid - Must be in format of name@domain.tld');
	}
    // Lets see if the email exists
    $sql = "SELECT COUNT(*) FROM players WHERE email = '$forgotpassword'";
    $result = mysql_query($sql)or die('Could not find member: ' . mysql_error());
    if (!mysql_result($result,0,0)>0) {
        error('Email Not Found!');
    }

	//Generate a RANDOM MD5 Hash for a password
	$random_password=md5(uniqid(rand()));
	
	//Take the first 8 digits and use them as the password we intend to email the user
	$emailpassword=substr($random_password, 0, 8);
	
	//Encrypt $emailpassword in MD5 format for the database
	$newpassword = encrypt_password($emailpassword);

        // Make a safe query
       	$query = sprintf("UPDATE `players` SET `password` = '%s' 
						  WHERE `email` = '$forgotpassword'",
                    mysql_real_escape_string($newpassword));
					
					mysql_query($query)or die('Could not update members: ' . mysql_error());

//Email out the infromation
$subject = "Your New Password"; 
$message = "Your new password is as follows:
---------------------------- 
Password: $emailpassword

---------------------------- 
Please change your password when you login via your account page.

This email was automatically generated."; 
                       
          if(!mail($forgotpassword, $subject, $message,  "FROM: OnlinePokerScript.com")){ 
             die ("Sending Email Failed, Please Contact Site Admin! ($site_email)"); 
          }else{ 
                error('New Password Sent!.');
         } 
		
	}
	
else {
?>

<form name="forgotpasswordform" action="" method="post">
<div class="row">
  <div class="col-xs-7">
	
         <input class="form-control" placeholder="Email Address" name="forgotpassword" type="text" value="" id="forgotpassword" />
  </div>
  <div class="col-xs-3">
    <input class="btn btn-warning" type="submit" name="submit" value="Submit" class="mainoption" />
  </div>
</div>
</form>  
      <?
}
?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
