<?php
header('Location: lobby.php');
die();
require('includes/gen_inc.php'); 
require('includes/inc_admin.php'); 
?>
<?php
include 'templates/header.php';
?>
<script language="JavaScript" type="text/javaScript" src="js/lobby.php"></script>

    <div class="container">

      <div class="row">
      
        <div class="col-md-3">
        	<?php include 'templates/sidebar.php'; ?>
        </div>      

        <div class="col-md-9">

		<?php if ($ADMIN == true){ ?>
	      <ul class="nav nav-pills">
	        <li><a href="admin.php"><?php echo ADMIN_MANAGE_TABLES; ?></a></li>
	        <li><a href="admin.php?admin=members"><?php echo ADMIN_MANAGE_MEMBERS; ?></a></li>
	        <li><a href="admin.php?admin=styles"><?php echo ADMIN_MANAGE_STYLES; ?></a></li>
	        <li><a href="admin.php?admin=settings"><?php echo ADMIN_MANAGE_SETTINGS; ?></a></li>
	      </ul>
		  <br>
        <?php } ?>

		<?php if($adminview == 'settings'){ ?>
                  
		<?php if($_GET['ud'] == 1){ ?>
		<div class="alert alert-success"><?php echo ADMIN_SETTINGS_UPDATED; ?></div>
		<?php } ?>
		
		<form name="form2" method="post" action="admin.php?admin=settings">

        <?php echo ADMIN_GENERAL; ?>

		<?php echo ADMIN_SETTINGS_TITLE; ?>
        <input type="text" name="title" class="form-control" size="60" maxlength="60" value="<?php echo TITLE; ?>">
        <span class="help-block"><?php echo ADMIN_SETTINGS_TITLE_HELP; ?></span>

		<?php echo ADMIN_SETTINGS_EMAIL; ?>
        <select name="emailmode" class="form-control">
			<option value="0" <?php if(EMAILMOD == 0) echo 'selected'; ?>>Off</option>
			<option value="1" <?php if(EMAILMOD == 1) echo 'selected'; ?>>On</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_EMAIL_HELP; ?></span>

		<?php echo ADMIN_SETTINGS_APPROVAL; ?>
		<select name="appmode" class="form-control">
			<option value="0" <?php if(APPMOD == 0) echo 'selected'; ?>>Automatic</option>
			<option value="1" <?php if(APPMOD == 1) echo 'selected'; ?>>Email Approval</option>
			<option value="2" <?php if(APPMOD == 2) echo 'selected'; ?>>Administrator Approval</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_APPROVAL_HELP; ?> <span class="label label-info">Note: If Email Approval is set, an Email Address is required.</span></span>

		<?php echo ADMIN_SETTINGS_IPCHECK; ?>
		<select name="ipcheck" class="form-control">
			<option value="0" <?php if(IPCHECK == 0) echo 'selected'; ?>>Off</option>
			<option value="1" <?php if(IPCHECK == 1) echo 'selected'; ?>>On</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_IPCHECK_HELP; ?></span>

		<?php echo ADMIN_SETTINGS_LOGIN; ?>
		<select name="memmode" class="form-control">
			<option value="0" <?php if(MEMMOD == 0) echo 'selected'; ?>>Off</option>
			<option value="1" <?php if(MEMMOD == 1) echo 'selected'; ?>>On</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_LOGIN_HELP; ?> <span class="label label-warning">Use with caution, this could lock you out of your system if used incorrectly.</span></span>
		
		<?php if(MEMMOD == 1) { ?>
		<?php echo ADMIN_SETTINGS_SESSNAME; ?>
		<input type="text" name="session" size="25" class="form-control" value="<?php echo SESSNAME; ?>">
		<span class="help-block"><?php if(SESSNAME == '') echo '<span class="label label-warning">You must enter a session variable!!</span>'; ?> <?php echo ADMIN_SETTINGS_SESSNAME_HELP; ?></span>
		<?php } ?>
        
		<?php echo ADMIN_SETTINGS_AUTODELETE; ?>
		<select name="delete" class="form-control">
			<option value="30" <?php if(DELETE == 30) echo 'selected'; ?>>After 30 days of inactivity</option>
			<option value="60" <?php if(DELETE == 60) echo 'selected'; ?>>After 60 days of inactivity</option>
			<option value="90" <?php if(DELETE == 90) echo 'selected'; ?>>After 90 days of inactivity</option>
			<option value="180" <?php if(DELETE == 180) echo 'selected'; ?>>After 180 days of inactivity</option>
			<option value="never" <?php if(DELETE == 'never') echo 'selected'; ?>>Never</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_AUTODELETE_HELP; ?></span>
		
		<?php echo ADMIN_SETTINGS_STAKESIZE; ?>
		<select name="stakesize" class="form-control">
			<option value="tiny" <?php if(STAKESIZE == 'tiny') echo 'selected'; ?>>Tiny Stakes [$10+]</option>
			<option value="low" <?php if(STAKESIZE == 'low') echo 'selected'; ?>>Low Stakes [$100+]</option>
			<option value="med" <?php if(STAKESIZE == 'med') echo 'selected'; ?>>Medium Stakes [$1000+]</option>
			<option value="high" <?php if(STAKESIZE == 'high') echo 'selected'; ?>>High Rollers [$10k+]</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_STAKESIZE_HELP; ?></span>

		<?php echo ADMIN_SETTINGS_BROKE_BUTTON; ?>
		<select name="renew" class="form-control">
			<option value="0" <?php if(RENEW == 0) echo 'selected'; ?>>Off</option>
			<option value="1" <?php if(RENEW == 1) echo 'selected'; ?>>On</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_BROKE_BUTTON_HELP; ?></span>

		<input type="submit" name="Submit" value="<?php echo BUTTON_SAVE_SETTINGS; ?>" class="btn btn-success">
		
		<p></p>

		<?php echo ADMIN_TIMER; ?>
		<input type="hidden" name="action" value="update">
		
		<?php echo ADMIN_SETTINGS_KICK; ?>
		<select name="kick" class="form-control">
			<option value="3" <?php if(KICKTIMER == 3) echo 'selected'; ?>>3 mins</option>
			<option value="5" <?php if(KICKTIMER == 5) echo 'selected'; ?>>5 mins</option>
			<option value="7" <?php if(KICKTIMER == 7) echo 'selected'; ?>>7 mins</option>
			<option value="10" <?php if(KICKTIMER == 10) echo 'selected'; ?>>10 mins</option><option value="15" <?php if(KICKTIMER == 15) echo 'selected'; ?>>15 mins</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_KICK_HELP; ?></span>

		<?php echo ADMIN_SETTINGS_MOVE; ?>
		<select name="move" class="form-control">
			<option value="10" <?php if(MOVETIMER == 10) echo 'selected'; ?>>Turbo</option>
			<option value="15" <?php if(MOVETIMER == 15) echo 'selected'; ?>>Fast</option>
			<option value="20" <?php if(MOVETIMER == 20) echo 'selected'; ?>>Normal</option>
			<option value="27" <?php if(MOVETIMER == 27) echo 'selected'; ?>>Slow</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_MOVE_HELP; ?></span>

		<?php echo ADMIN_SETTINGS_SHOWDOWN; ?>
		<select name="showdown" class="form-control">
			<option value="3" <?php if(SHOWDOWN == 3) echo 'selected'; ?>>3 secs</option>
			<option value="5" <?php if(SHOWDOWN == 5) echo 'selected'; ?>>5 secs</option>
			<option value="7" <?php if(SHOWDOWN == 7) echo 'selected'; ?>>7 secs</option>
			<option value="10" <?php if(SHOWDOWN == 10) echo 'selected'; ?>>10 secs</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_SHOWDOWN_HELP; ?></span>
		
		<?php echo ADMIN_SETTINGS_SITOUT; ?>
		<select name="wait" class="form-control">
			<option value="0" <?php if(WAITIMER == 0) echo 'selected'; ?>>None</option>
			<option value="10" <?php if(WAITIMER == 10) echo 'selected'; ?>>10 secs</option>
			<option value="15" <?php if(WAITIMER == 15) echo 'selected'; ?>>15 secs</option>
			<option value="20" <?php if(WAITIMER == 20) echo 'selected'; ?>>20 secs</option>
			<option value="25" <?php if(WAITIMER == 25) echo 'selected'; ?>>25 secs</option>
		</select>
		<span class="help-block"><?php echo ADMIN_SETTINGS_SITOUT_HELP; ?></span>
		
		<?php echo ADMIN_SETTINGS_DISCONNECT; ?>
		<select name="disconnect" class="form-control">
			<option value="15" <?php if(DISCONNECT == 15) echo 'selected'; ?>>15 secs</option>
			<option value="30" <?php if(DISCONNECT == 30) echo 'selected'; ?>>30 secs</option>
			<option value="60" <?php if(DISCONNECT == 60) echo 'selected'; ?>>60 secs</option>
			<option value="90" <?php if(DISCONNECT == 90) echo 'selected'; ?>>90 secs</option>
			<option value="120" <?php if(DISCONNECT == 120) echo 'selected'; ?>>120 secs</option>
		</select>
		<?php echo ADMIN_SETTINGS_DISCONNECT_HELP; ?>
		<br>
		<input type="submit" name="Submit" value="<?php echo BUTTON_SAVE_SETTINGS; ?>" class="btn btn-success">
      
		</form>       
		  
		<?php }elseif($adminview == 'members'){ ?>
                  <table border="0" cellspacing="0" cellpadding="2">
                    <tr> 
                      <td> 
                        <?php $dir = (($_GET['dir'] != '')? addslashes($_GET['dir']) : 'asc');
$col = (($_GET['col'] != '')? addslashes($_GET['col']) : DB_PLAYERS.'.username'); 
$oppdir = (($dir == 'asc')? 'desc' : 'asc');
$hrefarray = array();
$colarray = array('".DB_PLAYERS.".username','".DB_STATS.".rank','".DB_PLAYERS.".datecreated','".DB_PLAYERS.".ipaddress','".DB_PLAYERS.".approve','".DB_PLAYERS.".banned');
$i =0;
while($colarray[$i] != ''){
if($col == $colarray[$i]){
$hrefarray[$colarray[$i]] = 'admin.php?col='.$colarray[$i].'&dir='.$oppdir.'&admin=members';
}else{
$hrefarray[$colarray[$i]] = 'admin.php?col='.$colarray[$i].'&dir=asc&admin=members';
}
$i++;
}

?>
                        <?php if(EMAILMOD == 0){ ?>
                        <table border="0" cellspacing="0" cellpadding="1">
                          <tr> 
                            <td width="130" nowrap class="fieldsetheadcontent"><b><a href="<?php echo $hrefarray[DB_PLAYERS.'.username']; ?>"><font color="#000000"> 
                              <?php echo ADMIN_MEMBERS_NAME; if(($col == DB_PLAYERS.'.username') && ($dir == 'asc')){ echo '<img src="images/down.gif" border="0" width="10" height="10">'; }elseif(($col == DB_PLAYERS.'.username') && ($dir == 'desc')){ echo ''; } ?>
                              </font> </a></b></td>
                            <td width="50" nowrap align="center"><b><a href="<?php echo $hrefarray[DB_STATS.'.rank']; ?>"><font color="#000000"> 
                              <?php echo ADMIN_MEMBERS_RANK; if(($col == DB_STATS.'.rank') && ($dir == 'asc')){ echo '<img src="images/down.gif" border="0" width="10" height="10">'; }elseif(($col == DB_STATS.'.rank') && ($dir == 'desc')){ echo ''; } ?>
                              </font> </a></b></td>
                            <td width="80" nowrap align="center"><b><a href="<?php echo $hrefarray[DB_PLAYERS.'.datecreated']; ?>"><font color="#000000"> 
                              <?php echo ADMIN_MEMBERS_CREATED;  if(($col == DB_PLAYERS.'.datecreated') && ($dir == 'asc')){ echo '<img src="images/down.gif" border="0" width="10" height="10">'; }elseif(($col == DB_PLAYERS.'.datecreated') && ($dir == 'desc')){ echo '<img src="images/up.gif" border="0" width="10" height="10">'; } ?>
                              </font> </a></b></td>
                            <td nowrap align="center" width="100"><b><a href="<?php echo $hrefarray[DB_PLAYERS.'.ipaddress']; ?>"><font color="#000000"> 
                              <?php echo ADMIN_MEMBERS_IPADDRESS; if(($col == DB_PLAYERS.'.ipaddress') && ($dir == 'asc')){ echo '<img src="images/down.gif" border="0" width="10" height="10">'; }elseif(($col == DB_PLAYERS.'.ipaddress') && ($dir == 'desc')){ echo '<img src="images/up.gif" border="0" width="10" height="10">'; } ?>
                              </font></a> </b>
                            <td width="70" nowrap align="center"><b><a href="<?php echo $hrefarray[DB_PLAYERS.'.approve']; ?>"><font color="#000000"> 
                              <?php echo ADMIN_MEMBERS_APPROVE; if(($col == DB_PLAYERS.'.approve') && ($dir == 'asc')){ echo '<img src="images/down.gif" border="0" width="10" height="10">'; }elseif(($col == DB_PLAYERS.'.banned') && ($dir == 'desc')){ echo '<img src="images/up.gif" border="0" width="10" height="10">'; } ?>
                              </font> </a></b></td>
                            <td width="50" nowrap align="center"><b><a href="<?php echo $hrefarray[DB_PLAYERS.'.banned']; ?>"><font color="#000000"> 
                              <?php echo ADMIN_MEMBERS_BAN; if(($col == DB_PLAYERS.'.banned') && ($dir == 'asc')){ echo '<img src="images/down.gif" border="0" width="10" height="10">'; }elseif(($col == DB_PLAYERS.'.username') && ($dir == 'desc')){ echo '<img src="images/up.gif" border="0" width="10" height="10">'; } ?>
                              </font> </a></b></td>
                            <td width="50" nowrap align="center"><b><font color="#000000"> 
                              <?php echo ADMIN_MEMBERS_DELETE; ?>
                              </font></b></td>
                            <td width="50" nowrap align="center"><b><font color="#000000"> 
                              <?php echo ADMIN_MEMBERS_RESET_STATS; ?>
                              </font></b></td>
                          </tr>
                        </table>
                        <div> 
                          <table border="0" cellspacing="0" cellpadding="1">
                            <?php 
$plq = mysql_query("select ".DB_PLAYERS.".username, ".DB_PLAYERS.".datecreated, ".DB_PLAYERS.".banned, ".DB_PLAYERS.".ipaddress, ".DB_PLAYERS.".approve, ".DB_STATS.".rank from ".DB_PLAYERS.", ".DB_STATS." where ".DB_PLAYERS.".username = ".DB_STATS.".player order by ".$col." ".$dir);
while($plr = mysql_fetch_array($plq)){
$pname = $plr['username'];
$pban = $plr['banned'];
$pdate = date("m-d-Y",$plr['datecreated']);
$pip = $plr['ipaddress'];
$prank = $plr['rank'];
$papprove = $plr['approve'];
?>
                            <tr> 
                              <td width="130" align="left"> 
                                <?php echo $pname; ?>
                              </td>
                              <td width="50" align="center" nowrap> 
                                <?php echo $prank; ?>
                              </td>
                              <td width="80" align="center" nowrap> 
                                <?php echo $pdate; ?>
                              </td>
                              <td width="100" align="center" nowrap> 
                                <?php echo $pip; ?>
                              </td>
                              <td width="70" align="center" nowrap> 
                                <?php if($papprove == 1){ ?>
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="approve">
                                  <input type="hidden" name="player" value="<?php echo $pname; ?>">
                                  </font> 
                                  <input type="button" name="Button" value="<?php echo BUTTON_APPROVE; ?>"  onClick="changeview('admin.php?delete=<?php echo $gameID; ?>')" class="betbuttons" >
                                </form>
                                <?php } ?>
                              </td>
                              <td width="50" align="center" nowrap> 
                                <?php if($plyrname != $pname){ ?>
                                <?php if($pban == 0){ ?>
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="ban">
                                  <input type="hidden" name="player" value="<?php echo $pname; ?>">
                                  </font> 
                                  <input type="button" name="Button" value="<?php echo BUTTON_BAN; ?>"  onClick="changeview('admin.php?delete=<?php echo $gameID; ?>')" class="betbuttons" >
                                </form>
                                <?php }else{ ?>
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="unban">
                                  <input type="hidden" name="player" value="<?php echo $pname; ?>">
                                  </font> 
                                  <input type="button" name="Button" value="<?php echo BUTTON_UNBAN; ?>"  onClick="changeview('admin.php?delete=<?php echo $gameID; ?>')" class="betbuttons" >
                                </form>
                                <?php } ?>
                                <?php } ?>
                              </td>
                              <td width="50" align="center" nowrap> 
                                <?php if($plyrname != $pname){ ?>
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="delete">
                                  <input type="hidden" name="player" value="<?php echo $pname; ?>">
                                  </font> 
                                  <input type="button" name="Button" value="<?php echo BUTTON_DELETE; ?>"  onClick="changeview('admin.php?delete=<?php echo $gameID; ?>')" class="betbuttons" >
                                </form>
                                <?php } ?>
                              </td>
                              <td width="50" align="center" nowrap> 
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="reset">
                                  <input type="hidden" name="player" value="reset">
                                  </font> 
                                  <input type="button" name="Button" value="<?php echo BUTTON_RESET; ?>">
                                </form>
                              </td>
                            </tr>
                            <?php } ?>
                          </table>
                          <?php }else{ ?>
                          <table border="0" cellspacing="0" cellpadding="1">
                            <tr> 
                              <td width="100"><b><?php echo ADMIN_MEMBERS_NAME; ?></b></td>
                              <td width="120" align="center"><b><?php echo ADMIN_MEMBERS_RANK; ?></b></td>
                              <td width="130" align="center"><b><?php echo ADMIN_MEMBERS_EMAIL; ?></b></td>
                              <td width="140" align="center"><b><?php echo ADMIN_MEMBERS_IPADDRESS; ?></b></td>
                              <td width="100" align="center"><b><?php echo ADMIN_MEMBERS_APPROVE; ?></b></td>

                            </tr>
                          </table>
                          <table class="table table-hover" border="0" cellspacing="0" cellpadding="1" class="smllfontwhite" align="center" bgcolor="#FFFFFF">
                            <?php 
$plq = mysql_query("select ".DB_PLAYERS.".username, ".DB_PLAYERS.".datecreated, ".DB_PLAYERS.".banned, ".DB_PLAYERS.".ipaddress, ".DB_PLAYERS.".approve, ".DB_PLAYERS.".email , ".DB_STATS.".rank from ".DB_PLAYERS.", ".DB_STATS." where ".DB_PLAYERS.".username = ".DB_STATS.".player order by ".$col." ".$dir);
while($plr = mysql_fetch_array($plq)){
$pname = $plr['username'];
$pban = $plr['banned'];
$pdate = date("m-d-Y",$plr['datecreated']);
$pip = $plr['ipaddress'];
$prank = $plr['rank'];
$papprove = $plr['approve'];
$pemail = $plr['email'];

?>
                            <tr> 
                              <td width="100" align="left"> 
                                <?php echo $pname; ?>
                              </td>
                              <td width="40" align="center" nowrap> 
                                <?php echo $prank; ?>
                              </td>
                              <td width="150" align="center" nowrap> 
                                <?php if(strlen($pemail) > 25){
 echo substr($pemail,0,20).'...<img src="images/info.gif" border="0" alt="'.$pemail.'" width="10! height="10">'; 
}else{
 echo $pemail; 
}?>
                              </td>
                              <td width="90" align="center" nowrap> 
                                <?php echo $pip; ?>
                              </td>
                              <td width="60" align="center" nowrap> 
                                <?php if($papprove == 1){ ?>
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="approve">
                                  <input type="hidden" name="player" value="<?php echo $pname; ?>">
                                  </font> 
                                  <input class="btn btn-success" type="submit" name="Submit" value="<?php echo BUTTON_APPROVE; ?>">
                                </form>
                                <?php } ?>
                              </td>
                              <td width="40" align="center" nowrap> 
                                <?php if($plyrname != $pname){ ?>
                                <?php if($pban == 0){ ?>
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="ban">
                                  <input type="hidden" name="player" value="<?php echo $pname; ?>">
                                  </font> 
                                  <input class="btn btn-warning" type="submit" name="Submit" value="<?php echo BUTTON_BAN; ?>">
                                </form>
                                <?php }else{ ?>
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="unban">
                                  <input type="hidden" name="player" value="<?php echo $pname; ?>">
                                  </font> 
                                  <input class="btn btn-warning" type="submit" name="Submit" value="<?php echo BUTTON_UNBAN; ?>">
                                </form>
                                <?php } ?>
                                <?php } ?>
                              </td>
                              <td width="50" align="center" nowrap>
                                <?php if($plyrname != $pname){ ?>
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="delete">
                                  <input type="hidden" name="player" value="<?php echo $pname; ?>">
                                  </font> 
                                  <input class="btn btn-danger" type="submit" name="Submit" value="<?php echo BUTTON_DELETE; ?>">
                                </form>
                                <?php } ?>
                              </td>
                              <td width="40" align="center" nowrap> 
                                <form name="form1" method="post" action="admin.php?admin=members">
                                  <font color="#000000"> 
                                  <input type="hidden" name="action" value="reset">
                                  <input type="hidden" name="player" value="<?php echo $pname; ?>">
                                  </font> 
                                  <input class="btn btn-default" type="submit" name="Submit" value="<?php echo BUTTON_RESET; ?>">

                                </form>
                              </td>
                            </tr>
                            <?php } ?>
                          </table>
                          <?php }  ?>
                        </div>
                      </td>
                    </tr>
                  </table>
                  
        <?php }elseif($adminview == 'styles'){ ?>
        
                  <form name="form3" method="post" action="">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3" class="smllfontwhite">
                      <tr> 
                        <td width="26%"><b><font color="#000000"> 
                          <?php echo ADMIN_STYLES_INSTALLED; ?>
                          </font></b></td>
                        <td width="45%"><b><font color="#000000"> 
                          <?php echo ADMIN_STYLES_PREVIEW; ?>
                          </font></b></td>
                        <td width="29%">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td width="26%" valign="middle">default style pack</td>
                        <td width="45%" valign="middle"><img src="images/default/preview.jpg" width="112" height="80"></td>
                        <td width="29%">&nbsp;</td>
                      </tr>
                      <?php $styq = mysql_query("select style_name from styles");
while($styr = mysql_fetch_array($styq)){
$name = $styr['style_name'];
$sname = $styr['style_name'].' style pack';
$spreview = '<img src="images/'.$name.'/preview.jpg" border="0" width="112" height="80">';
 ?>
                      <tr> 
                        <td width="26%" valign="middle"> 
                          <?php echo $sname; ?>
                        </td>
                        <td width="45%" valign="middle"> 
                          <?php echo $spreview; ?>
                        </td>
                        <td width="29%">&nbsp;</td>
                      </tr>
                      <?php } ?>
                      <tr> 
                        <td width="26%">&nbsp;</td>
                        <td width="45%" align="center"><font color="#FF0000"> 
                          <?php echo $msg; ?>
                          </font></td>
                        <td width="29%"></td>
                      </tr>
                      <tr> 
                        <td width="26%"><font color="#000000"> 
                          <?php echo ADMIN_STYLES_NEW_NAME; ?>
                          </font></td>
                        <td width="45%"><font color="#000000"> 
                          <?php echo ADMIN_STYLES_CODE; ?>
                          </font></td>
                        <td width="29%"> 
                          <input type="hidden" name="action" value="install">
                        </td>
                      </tr>
                      <tr> 
                        <td width="26%"> 
                          <input type="text" name="name" size="25" maxlength="25" class="form-control">
                        </td>
                        <td width="45%"> 
                          <input type="text" name="lic" size="40" maxlength="40" class="form-control">
                        </td>
                        <td width="29%"> 
                          <input type="button" name="Button" value="<?php echo BUTTON_INSTALL; ?>"  onClick="changeview('admin.php?delete=<?php echo $gameID; ?>')" class="betbuttons" >
                        </td>
                      </tr>
                    </table>
                  </form>
                  
                  <?php }else{ ?>
                  
                  <form name="createtable" method="post" action="admin.php">
                    <table class="table table-hover" border="0" cellspacing="0" cellpadding="3" class="smllfontwhite">
                      <tr> 
                        <td nowrap><b><font color="#000000"> 
                          <?php echo ADMIN_TABLES_NAME; ?>
                          </font></b></td>
                        <td align="center" nowrap><b><font color="#000000"> 
                          <?php echo ADMIN_TABLES_TYPE; ?>
                          </font></b></td>
                        <td align="center" nowrap><b><font color="#000000"> 
                          <?php echo ADMIN_TABLES_MIN; ?>
                          </font></b></td>
                        <td align="center" nowrap><b><font color="#000000"> 
                          <?php echo ADMIN_TABLES_MAX; ?>
                          </font></b></td>
                        <td align="center" nowrap><b><font color="#000000"> 
                          <?php echo ADMIN_TABLES_STYLE; ?>
                          </font></b></td>
                        <td align="center" nowrap><b><font color="#000000"> 
                          <?php echo ADMIN_TABLES_DELETE; ?>
                          </font></b></td>
                      </tr>
                      <?
$tableq = mysql_query("select gameID,tablename,tablelimit ,tabletype, tablelow, tablestyle from ".DB_POKER." order by tablelimit asc ");
while($tabler = mysql_fetch_array($tableq)){ 

$tablename =  stripslashes($tabler['tablename']);
$min = (($tabler['tabletype'] != 't')? money_small($tabler['tablelow']) : 'N/A');
$tablelimit = $tabler['tablelimit'];
$max = money_small($tablelimit);
$gameID = $tabler['gameID'];
$tabletype = (($tabler['tabletype'] == 't')? 'Tournament' : 'Sit \'n Go');
$tablestyle = (($tabler['tablestyle'] == '')? 'default' : $tabler['tablestyle']);

?>
                      <tr> 
                        <td> 
                          <?php echo $tablename; ?>
                        </td>
                        <td align="center"> 
                          <?php echo $tabletype; ?>
                        </td>
                        <td align="center"> 
                          <?php echo $min; ?>
                        </td>
                        <td align="center"> 
                          <?php echo $max; ?>
                        </td>
                        <td align="center"> 
                          <?php echo $tablestyle; ?>
                        </td>
                        <td align="center"> 
                          <input type="button" name="Button" class="btn btn-danger" value="<?php echo BUTTON_DELETE; ?>"  onClick="changeview('admin.php?delete=<?php echo $gameID; ?>')" class="betbuttons" >
                        </td>
                      </tr>
                      <?php } ?>
                      <tr> 
                        <td> 
                          <input type="text" name="tname" class="form-control" maxlength="25">
                        </td>
                        <td align="center"> 
                          <select name="ttype" class="form-control">
                            <option value="s"> 
                            <?php echo SITNGO; ?>
                            </option>
                            <option value="t"> 
                            <?php echo TOURNAMENT; ?>
                            </option>
                          </select>
                        </td>
                        <td align="center"> 
                          <select name="tmin" class="form-control">
                            <option value="0" selected>
                            <?php echo money(0); ?>
                            </option>
                            <option value="1000">
                            <?php echo money(1000); ?>
                            </option>
                            <option value="2500">
                            <?php echo money(2500); ?>
                            </option>
                            <option value="5000">
                            <?php echo money(5000); ?>
                            </option>
                            <option value="10000">
                            <?php echo money(10000); ?>
                            </option>
                            <option value="25000">
                            <?php echo money(25000); ?>
                            </option>
                            <option value="50000">
                            <?php echo money(50000); ?>
                            </option>
                            <option value="100000">
                            <?php echo money(100000); ?>
                            </option>
                            <option value="250000">
                            <?php echo money(250000); ?>
                            </option>
                            <option value="500000">
                            <?php echo money(500000); ?>
                            </option>
                          </select>
                        </td>
                        <td align="center"> 
                          <select name="tmax" class="form-control">
                            <option value="10000" selected>
                            <?php echo money(10000); ?>
                            </option>
                            <option value="25000">
                            <?php echo money(25000); ?>
                            </option>
                            <option value="50000">
                            <?php echo money(50000); ?>
                            </option>
                            <option value="100000">
                            <?php echo money(100000); ?>
                            </option>
                            <option value="250000">
                            <?php echo money(250000); ?>
                            </option>
                            <option value="500000">
                            <?php echo money(500000); ?>
                            </option>
                            <option value="1000000">
                            <?php echo money(1000000); ?>
                            </option>
                          </select>
                          <input type="hidden" name="action" value="createtable">
                        </td>
                        <td align="center"> 
                          <select name="tstyle" class="form-control">
                            <option selected>default</option>
                            <?php $stq = mysql_query("select style_name from styles");
while($str = mysql_fetch_array($stq)){ ?>
                            <option value="<?php echo $str['style_name']; ?>">
                            <?php echo $str['style_name']; ?>
                            </option>
                            <?php } ?>
                          </select>
                        </td>
                        <td align="center"> 
                          <input type="submit" name="Submit" class="btn btn-success" value="<?php echo BUTTON_CREATE_TABLE ?>"  class="betbuttons" >
                        </td>
                      </tr>
                      <tr> 
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                  </form>
                  <?php }  ?>        
        
        </div>
        
      </div>
      
    </div>

<?php include 'templates/footer.php'; ?>
