<?php 
require('includes/gen_inc.php'); 
require('includes/inc_lobby.php'); 
?>

<?php include 'templates/header.php'; ?>

<script>
var filesadded=""; var state1 = '#000000'; var state2 = '#FF0000'; function avatar(ava) { for(var i = 1; i < 17; i++) { var thiscell = document.getElementById('avatar'+i); if(ava == i) { createplyr.av.value = i; thiscell.style.backgroundColor = state2; }else{ thiscell.style.backgroundColor = state1 ;}}} function checkthis(msg) { var answer = confirm(msg); 	if (answer){ return true; }else{ return false;}} function games(){ var url = document.location.href; var xend = url.lastIndexOf("/") + 1; var base_url = url.substring(0, xend); thisurl = base_url + 'includes/live_games.php'; checkloadfile(thisurl, "js"); setTimeout("games()", 3000);} function selectgame(url) { window.location.href = url;} function changeview(url) { window.location.href = url;} function newavatar(av) { var url = 'myplayer.php?newavatar='+av; window.location.href = url;} function checkloadfile(filename, filetype){ if (filesadded.indexOf("["+filename+"]")==-1){ 
loadfile(filename, filetype); filesadded+="["+filename+"]"; }else{ replacefile(filename, filename, filetype);}} function loadfile(filename, filetype){ if (filetype=="js"){ var fileref=document.createElement('script'); fileref.setAttribute("type","text/javascript") ; fileref.setAttribute("src", filename) ; } else if (filetype=="css"){ var fileref=document.createElement ("link"); fileref.setAttribute("rel", "stylesheet") ; fileref.setAttribute("type", "text/css") ; fileref.setAttribute("href", filename);  } if (typeof fileref!="undefined") document.getElementsByTagName("head")[0].appendChild(fileref); } function createfile(filename, filetype){  if (filetype=="js"){ var fileref=document.createElement('script');  fileref.setAttribute("type","text/javascript") ; fileref.setAttribute("src", filename);  } return fileref ;} function replacefile(oldfilename, newfilename, filetype){ var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none"; var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none"; var allsuspects=document.getElementsByTagName(targetelement);  for (var i=allsuspects.length; i>=0; i--){  if (allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(oldfilename)!=-1){ var newelement=createfile(newfilename, filetype); allsuspects[i].parentNode.replaceChild(newelement, allsuspects[i]); } } }

function start() {
  games();
}
window.onload = start;
</script>

    <div class="container">

      <div class="row">
      
        <div class="col-md-3 hidden-xs">
        	<?php include 'templates/sidebar.php'; ?>
        </div>      

        <div class="col-md-9" align="center">
              <div style="width:100%; overflow:auto;">
                  <table border="0" cellspacing="0" cellpadding="1" class="table" >
                      
                        <tr class="fieldsetheadlink"> 
                          <td width="120"><b><?php echo TABLE_HEADING_NAME; ?></b></td>
                          <td align="center" width="50"><b><?php echo TABLE_HEADING_PLAYERS; ?></b></td>
                          <td align="center" width="80"><b><?php echo TABLE_HEADING_TYPE; ?></b></td>
                          <td align="center" width="90"><b><?php echo TABLE_HEADING_BUYIN; ?></b></td>
                          <td align="center" width="90"><b><?php echo TABLE_HEADING_SMALL_BLINDS; ?></b></td>
                          <td align="center" width="90"><b><?php echo TABLE_HEADING_BIG_BLINDS; ?></b></td>
                          <td align="center" width="80"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
                        </tr>
                    
                  </table>
              </div>
                  <div id="gamelist" style="border : solid 0px   padding : 1px; width : 100%; height : auto; overflow : auto; ">
             </div ><br><?php
            $tableq = mysql_query("select p1name, p2name,p3name,p4name,p5name,p6name,p7name,p8name,p9name,p10name,tablename,tablelimit,tabletype,hand,tablelow from ".DB_POKER." order by tablelimit asc ");
            while($tabler = mysql_fetch_array($tableq)){ 
            $i = 1;
            $x=0;
            while($i < 11){
            if($tabler['p'.$i.'name'] != '') $x++;
            $i++;
            }
            $tableplayers = $x.'/10';
            $tablestatus = (($tabler['hand'] == '')? 'New Game' : 'Playing');
            $tabletype = (($tabler['tabletype'] == 't')? 'Tournament' : 'Sit \'n Go');
            ?>
                    <?php } ?>
        </div>
        
        <div class="col-md-3 hidden-lg hidden-md hidden-sm">
          <?php include 'templates/sidebar.php'; ?>
        </div>
        
      </div>
      
    </div>

<?php include 'templates/footer.php'; ?>
