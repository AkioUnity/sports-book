<?php require('includes/gen_inc.php'); 
require('includes/inc_poker.php'); 

if($_SESSION['pokerscreen'] != 'narrow') $_SESSION['pokerscreen'] = 'wide';
if($_GET['view'] == 'wide') $_SESSION['pokerscreen'] = 'wide';
if($_GET['view'] == 'narrow') $_SESSION['pokerscreen'] = 'narrow';

//$tablestyle = "pokertour";

if($_SESSION['pokerscreen'] == 'wide'){
$twidth = 1000;
$lview = '<td width="98" background="images/'.$tablestyle.'/poker_r1_c1.jpg" height="550">&nbsp;</td>';
$rview = '<td width="97" height="550" background="images/'.$tablestyle.'/poker_r1_c22.jpg">&nbsp;</td>';
}else{
$twidth = 805;
$lview = '';
$rview = '';
}

?><!DOCTYPE HTML PUBLIC "-//W3C//Dtd HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo TITLE;?></title>
<meta http-equiv="Content-Type" content='text/html; charset="ISO-8859-1"' />
<script language="JavaScript" type="text/JavaScript" src="js/poker.php"></script>
<link rel="stylesheet" href="images/<?php echo $tablestyle; ?>/css/poker.css" type="text/css">
</script>
</head>
<body onLoad="push_poker();" leftmargin="0">
<table border="0" cellpadding="0" cellspacing="0" width="<?php echo $twidth; ?>" align="center">
  <tr> 
   <?php echo $lview;?>

    <td width="805"> 
      <table border="0" cellpadding="0" cellspacing="0" width="805">
        <tr> 
          <td>
            <table border="0" cellpadding="0" cellspacing="0" width="805">
              <tr> 
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="181">
                    <tr> 
                      <td width="181" height="22" background="images/<?php echo $tablestyle; ?>/poker_r1_c2.jpg" class="gameinfo"> 
                        &nbsp; 
                        <?php $lim = (($tablelimit < 10000)? 'No Limit' : money($tablelimit)); 
echo stripslashes($tablename); ?>
                      </td>
                    </tr>
                    <tr> 
                      <td>
                        <table border="0" cellpadding="0" cellspacing="0" width="181">
                          <tr> 
                            <td width="30" height="145" background="images/<?php echo $tablestyle; ?>/poker_r3_c2.jpg">&nbsp;</td>
                            <td width="93"> 
                              <table border="0" cellpadding="0" cellspacing="0" width="93" height="145">
                                <tr> 
                                  <td width="93" height="56" background="images/<?php echo $tablestyle; ?>/poker_r3_c3.jpg" align="center" valign="bottom">        
            <div id="ptimer1" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div>
<div id="pinfo1"></div></td>
                                </tr>
                                <tr> 
                                  <td width="93" height="89" background="images/<?php echo $tablestyle; ?>/poker_r5_c3.jpg" align="center" valign="middle" class="loadingtxt"><div id="pava1"></div></td>
                                </tr>
                              </table>
                            </td>
                            <td width="58" height="145" background="images/<?php echo $tablestyle; ?>/poker_r3_c5.jpg">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="441">
                    <tr> 
                      <td>
                        <table border="0" cellpadding="0" cellspacing="0" width="441">
                          <tr> 
                            <td>
                              <table border="0" cellpadding="0" cellspacing="0" width="94">
                                <tr> 
                                  <td width="94" height="56" background="images/<?php echo $tablestyle; ?>/poker_r1_c7.jpg" valign="bottom" align="center">
            <div id="ptimer2" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div><div id="pinfo2"></div></td>
                                </tr>
                                <tr> 
                                  <td width="94" height="89" background="images/<?php echo $tablestyle; ?>/poker_r4_c7.jpg" align="center" valign="middle" class="loadingtxt"> 
                                    <div id="pava2"></div></td>
                                </tr>
                              </table>
                            </td>
                            <td width="22" height="145" background="images/<?php echo $tablestyle; ?>/poker_r1_c9.jpg">&nbsp;</td>
                            <td>
                              <table border="0" cellpadding="0" cellspacing="0" width="211">
                                <tr> 
                                  <td height="7" width="211" background="images/<?php echo $tablestyle; ?>/poker_r1_c11.jpg"><img src="images/spacer.gif" width="1" height="1"></td>
                                </tr>
                                <tr> 
                                  <td width="211" height="71" background="images/<?php echo $tablestyle; ?>/poker_r2_c11.jpg" align="left" valign="top">
                                    <?php $cq = mysql_query("select * from ".DB_LIVECHAT." where gameID = '".$gameID."' ");
$cr = mysql_fetch_array($cq);
$i = 1;
while($i < 6){
$chat .= $cr['c'.$i];
$i++;
}
?>
                                    <div id="chatbox" class="chattxt" > 
                                      <div id="chatdiv" style="border : solid 0px   padding : 1px; width : 100%; height : 70px; overflow : auto;" class="chattxt"> 
                                        <?php echo stripslashes($chat); ?>
                                      </div>
            </div>                                </td>
                                </tr>
                                <tr> 
                                  <td width="211" height="9" background="images/<?php echo $tablestyle; ?>/poker_r5_c11.jpg"><img src="images/spacer.gif" width="1" height="1"></td>
                                </tr>
                                <tr> 
                                  <td width="211" height="58" background="images/<?php echo $tablestyle; ?>/poker_r6_c11.jpg" align="center" valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr align="center"> 
                                        <td colspan="2" class="dealerinfo"> 
                                          <?php echo DEALER_INFO; ?>
                                        </td>
                                      </tr>
                                      <tr> 
                                        <td colspan="2" class="dealertxt"> 
                                          <div id="dealertxt"></div>
                                        </td>
                                      </tr>
                                      <tr> 
                                        <td width="34%" class="chattxt"> 
                                          <?php echo TABLEPOT; ?>
                                        </td>
                                        <td width="66%" class="tablePot"> 
                                          <div id="tablepot"></div>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td width="20" height="145" background="images/<?php echo $tablestyle; ?>/poker_r1_c14.jpg">&nbsp;</td>
                            <td>
                              <table border="0" cellpadding="0" cellspacing="0" width="94">
                                <tr> 
                                  <td width="94" height="56" background="images/<?php echo $tablestyle; ?>/poker_r1_c15.jpg" align="center" valign="bottom">
            <div id="ptimer3" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div>
<div id="pinfo3"></div></td>
                                </tr>
                                <tr> 
                                  <td width="94" height="89" background="images/<?php echo $tablestyle; ?>/poker_r4_c15.jpg" align="center" valign="middle">  
                                    <div id="pava3" class="loadingtxt"></div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr> 
                      <td width="441" height="22" background="images/<?php echo $tablestyle; ?>/poker_r7_c7.jpg" align="center">
                        <form name="talk" method="post" action="">
                          <input type="text" name="talk" id="talk" class="chatinput" size="23" maxlength="80" onKeyPress=" return checkEnter(event)">
                          <input type="button" name="Submit" value="<?php echo BUTTON_SEND; ?>" class="betbuttons" onClick="push_talk();">
                        </form>
                      </td>
                    </tr>
                  </table>
                </td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="183">
                    <tr> 
                      <td width="183" height="22" background="images/<?php echo $tablestyle; ?>/poker_r1_c17.jpg" align="right" class="gameinfo"> 
                        <?php $ttype = (($tabletype != 't')? SITNGO : TOURNAMENT); 
$buyin = (($tabletype == 't')? money_small($tablelimit) : money_small($min).'/'.money_small($tablelimit)); 
echo $ttype.' - '.$buyin; ?>
                        &nbsp; </td>
                    </tr>
                    <tr> 
                      <td>
                        <table border="0" cellpadding="0" cellspacing="0" width="183">
                          <tr> 
                            <td width="60" height="145" background="images/<?php echo $tablestyle; ?>/poker_r3_c17.jpg">&nbsp;</td>
                            <td>
                              <table border="0" cellpadding="0" cellspacing="0" width="93" height="145">
                                <tr> 
                                  <td width="93" height="56" background="images/<?php echo $tablestyle; ?>/poker_r3_c19.jpg" align="center" valign="bottom">
            <div id="ptimer4" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div>
<div id="pinfo4"></div></td>
                                </tr>
                                <tr> 
                                  <td width="93" height="89" background="images/<?php echo $tablestyle; ?>/poker_r5_c19.jpg" align="center" valign="middle">  
                                    <div id="pava4" class="loadingtxt"></div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td width="30" height="145" background="images/<?php echo $tablestyle; ?>/poker_r3_c21.jpg">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td>
            <table border="0" cellpadding="0" cellspacing="0" width="805">
              <tr> 
                <td width="30" height="212" background="images/<?php echo $tablestyle; ?>/poker_r8_c2.jpg">&nbsp;</td>
                <td width="46" height="212" background="images/<?php echo $tablestyle; ?>/poker_r8_c3.jpg">&nbsp;</td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="94">
                    <tr> 
                      <td width="94" height="106" background="images/<?php echo $tablestyle; ?>/poker_r8_c4.jpg" align="center" valign="middle">
            <div id="pos1hand"></div>
            <div id="pbet1" class="tblbet"></div>
</td>
                    </tr>
                    <tr> 
                      <td width="94" height="106" background="images/<?php echo $tablestyle; ?>/poker_r9_c4.jpg" align="center" valign="middle">
            <div id="pbet10" class="tblbet"></div>
            <div id="pos10hand"></div>
</td>
                    </tr>
                  </table>
                </td>
                <td width="11" height="212" background="images/<?php echo $tablestyle; ?>/poker_r8_c6.jpg">&nbsp;</td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="94">
                    <tr> 
                      <td width="94" height="106" background="images/<?php echo $tablestyle; ?>/poker_r8_c7.jpg" align="center" valign="top">
            <div id="pos2hand"></div>
            <div id="pbet2" class="tblbet"></div>
</td>
                    </tr>
                    <tr> 
                      <td width="94" height="106" background="images/<?php echo $tablestyle; ?>/poker_r9_c7.jpg" align="center" valign="bottom">
            <div id="pbet9" class="tblbet"></div>          
  <div id="pos9hand"></div>
</td>
                    </tr>
                  </table>
                </td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="253">
                    <tr> 
                      <td width="253" height="106" background="images/<?php echo $tablestyle; ?>/poker_r8_c9.jpg" align="center" valign="bottom"><table width="250" border="0" cellspacing="0" cellpadding="0">
              <tr align="center">
                <td width="50" height="62">
                  <div id="card1"></div>
                </td>
                <td width="50" height="62">
                  <div id="card2"></div>
                </td>
                <td width="50" height="62">
                  <div id="card3"></div>
                </td>
                <td width="50" height="62">
                  <div id="card4"></div>
                </td>
                <td width="50" height="62">
                  <div id="card5"></div>
                </td>
              </tr>
            </table></td>
                    </tr>
                    <tr> 
                      <td>
                        <table border="0" cellpadding="0" cellspacing="0" width="253">
                          <tr> 
                            <td width="18" height="106" background="images/<?php echo $tablestyle; ?>/poker_r9_c9.jpg">&nbsp;</td>
                            <td width="94" height="106" background="images/<?php echo $tablestyle; ?>/poker_r9_c10.jpg" align="center" valign="bottom">
            <div id="pbet8" class="tblbet"></div>          
  <div id="pos8hand"></div>
</td>
                            <td width="27" height="106" background="images/<?php echo $tablestyle; ?>/poker_r9_c12.jpg"> 
                            </td>
                            <td width="94" height="106" background="images/<?php echo $tablestyle; ?>/poker_r9_c13.jpg" align="center" valign="bottom">
            <div id="pbet7" class="tblbet"></div>          
  <div id="pos7hand"></div>
</td>
                            <td width="20" height="106" background="images/<?php echo $tablestyle; ?>/poker_r9_c14.jpg">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="94">
                    <tr> 
                      <td width="94" height="106" background="images/<?php echo $tablestyle; ?>/poker_r8_c15.jpg" align="center" valign="top">
            <div id="pos3hand"></div>
            <div id="pbet3" class="tblbet"></div>
</td>
                    </tr>
                    <tr> 
                      <td background="images/<?php echo $tablestyle; ?>/poker_r9_c15.jpg" width="94" height="106" align="center" valign="bottom">            
<div id="pbet6" class="tblbet"></div>          
  <div id="pos6hand"></div></td>
                    </tr>
                  </table>
                </td>
                <td width="10" height="212" background="images/<?php echo $tablestyle; ?>/poker_r8_c17.jpg">&nbsp;</td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="95">
                    <tr> 
                      <td width="94" height="106" background="images/<?php echo $tablestyle; ?>/poker_r8_c18.jpg" align="center" valign="middle">
            <div id="pos4hand"></div>
            <div id="pbet4" class="tblbet"></div>
</td>
                    </tr>
                    <tr> 
                      <td width="94" height="106" background="images/<?php echo $tablestyle; ?>/poker_r9_c18.jpg" align="center" valign="middle">
            <div id="pbet5" class="tblbet"></div>          
  <div id="pos5hand"></div>
</td>
                    </tr>
                  </table>
                </td>
                <td width="48" height="212" background="images/<?php echo $tablestyle; ?>/poker_r8_c20.jpg">
                  <form name="checkmov" >
                    <input type="hidden" name="lastmove" id="lastmove">
                    <input type="hidden" name="tomove" id="tomove">
                    <input type="hidden" name="hand" id="hand">
                  </form>
                </td>
                <td width="30" height="212" background="images/<?php echo $tablestyle; ?>/poker_r8_c21.jpg">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td>
            <table border="0" cellpadding="0" cellspacing="0" width="805">
              <tr> 
                <td width="30" height="171" background="images/<?php echo $tablestyle; ?>/poker_r10_c2.jpg">&nbsp;</td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="93">
                    <tr> 
                      <td width="94" height="89" background="images/<?php echo $tablestyle; ?>/poker_r10_c3.jpg" align="center" valign="middle" class="loadingtxt"> 
                        <div id="pava10"></div>
                      </td>
                    </tr>
                    <tr> 
                      <td width="93" height="56" background="images/<?php echo $tablestyle; ?>/poker_r12_c3.jpg" valign="top" align="center">
            <div id="pinfo10"></div>
            <div id="ptimer10" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/<?php echo $tablestyle ?>/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div></td>
                    </tr>
                    <tr> 
                      <td width="93" height="26" background="images/<?php echo $tablestyle; ?>/poker_r14_c3.jpg" align="center"><a href="poker.php?action=leave" target="_self"> 
                        <?php echo BUTTON_LEAVE; ?>
                        </a></td>
                    </tr>
                  </table>
                </td>
                <td width="47" height="171" background="images/<?php echo $tablestyle; ?>/poker_r10_c5.jpg">&nbsp;</td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="462">
                    <tr> 
                      <td width="462" height="21" background="images/<?php echo $tablestyle; ?>/poker_r10_c6.jpg" align="center"> 
                        <div id="buttons"></div>
                      </td>
                    </tr>
                    <tr> 
                      <td>
                        <table border="0" cellpadding="0" cellspacing="0" width="462">
                          <tr> 
                            <td>
                              <table border="0" cellpadding="0" cellspacing="0" width="94">
                                <tr> 
                                  <td width="94" height="89" background="images/<?php echo $tablestyle; ?>/poker_r11_c6.jpg" align="center" valign="middle" class="loadingtxt"> 
                                    <div id="pava9"></div></td>
                                </tr>
                                <tr> 
                                  <td width="94" height="61" background="images/<?php echo $tablestyle; ?>/poker_r13_c6.jpg" align="center" valign="top">
            <div id="pinfo9"></div>
            <div id="ptimer9" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div></td>
                                </tr>
                              </table>
                            </td>
                            <td width="29" height="150" background="images/<?php echo $tablestyle; ?>/poker_r11_c8.jpg">&nbsp;</td>
                            <td>
                              <table border="0" cellpadding="0" cellspacing="0" width="94">
                                <tr> 
                                  <td width="94" height="89" background="images/<?php echo $tablestyle; ?>/poker_r11_c10.jpg" align="center" valign="middle" class="loadingtxt"> 
                                    <div id="pava8"></div></td>
                                </tr>
                                <tr> 
                                  <td width="94" height="61" background="images/<?php echo $tablestyle; ?>/poker_r13_c10.jpg" align="center" valign="top">
            <div id="pinfo8"></div>
            <div id="ptimer8" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div></td>
                                </tr>
                              </table>
                            </td>
                            <td width="27" height="150" background="images/<?php echo $tablestyle; ?>/poker_r11_c12.jpg"><div id="flashObject"></div></td>
                            <td>
                              <table border="0" cellpadding="0" cellspacing="0" width="94">
                                <tr> 
                                  <td width="94" height="89" background="images/<?php echo $tablestyle; ?>/poker_r11_c13.jpg" align="center" valign="middle" class="loadingtxt"> 
                                    <div id="pava7"></div></td>
                                </tr>
                                <tr> 
                                  <td width="94" height="61" background="images/<?php echo $tablestyle; ?>/poker_r13_c13.jpg" align="center" valign="top">            <div id="pinfo7"></div>
            <div id="ptimer7" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div>
</td>
                                </tr>
                              </table>
                            </td>
                            <td width="30" height="150" background="images/<?php echo $tablestyle; ?>/poker_r11_c14.jpg">&nbsp;</td>
                            <td>
                              <table border="0" cellpadding="0" cellspacing="0" width="94">
                                <tr> 
                                  <td width="94" height="89" background="images/<?php echo $tablestyle; ?>/poker_r11_c16.jpg" align="center" valign="middle" class="loadingtxt"> 
                                    <div id="pava6"></div></td>
                                </tr>
                                <tr> 
                                  <td width="94" height="61" background="images/<?php echo $tablestyle; ?>/poker_r13_c16.jpg" align="center" valign="top">
            <div id="pinfo6"></div>
            <div id="ptimer6" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div></td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <td width="50" height="171" background="images/<?php echo $tablestyle; ?>/poker_r10_c18.jpg">&nbsp;</td>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0" width="93">
                    <tr> 
                      <td width="93" height="89" background="images/<?php echo $tablestyle; ?>/poker_r10_c19.jpg" align="center" valign="middle">  
                        <div id="pava5" class="loadingtxt"></div>
                      </td>
                    </tr>
                    <tr> 
                      <td width="93" height="56" background="images/<?php echo $tablestyle; ?>/poker_r12_c19.jpg" align="center" valign="top">            
<div id="pinfo5"></div>
            <div id="ptimer5" align="left">
              <table width="1" height="5">
                <tr>
                  <td><img src="images/spacer.gif" width="1" height="5"></td>
                </tr>
              </table>
            </div></td>
                    </tr>
                    <tr> 
                      <td width="93" height="26" background="images/<?php echo $tablestyle; ?>/poker_r14_c19.jpg" align="center" class="gameinfo">
<?php if($_SESSION['pokerscreen'] == narrow){
echo '<a href="poker.php?view=wide" target="_self">'.BUTTON_WIDESCREEN.'</a>';
}else{
echo '<a href="poker.php?view=narrow" target="_self">'.BUTTON_SMALLSCREEN.'</a>';
}
?>
</td>
                    </tr>
                  </table>
                </td>
                <td width="30" height="171" background="images/<?php echo $tablestyle; ?>/poker_r10_c21.jpg">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  <?php echo $rview; ?>
  </tr>
</table>
</body></html>
