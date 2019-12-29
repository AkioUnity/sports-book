<?php
require('includes/gen_inc.php'); 
require('includes/inc_sitout.php'); 
?>

<script language="JavaScript" type="text/JavaScript" >
function countdown(passcount){
if(passcount > 0){
document.getElementById('sitout').innerHTML = ''+passcount+'';
passcount = (passcount-1);
var e = passcount;
setTimeout(function(){countdown(passcount)},1000);
}else{
parent.document.location.href = "lobby.php"; 
}
}

function startcountdown() {
	countdown('<?php echo $start; ?>');
}

window.onload = startcountdown;    
</script>

<?php include 'templates/header.php'; ?>
	
    <div class="container" onLoad="">

      <div class="row">
		
		<div class="col-md-12">

			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title"><?php echo SITOUT_TIMER; ?> <div class="pull-right">Please Wait: <span id="sitout"><?php echo SITOUT; ?></span></div></h3>
			  </div>
			</div>			

		</div>		
		
	  </div>
	
    </div>
				
<?php include 'templates/footer.php'; ?>