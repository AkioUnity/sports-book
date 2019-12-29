<?php 
require('includes/gen_inc.php'); 
?>

<?php include 'templates/header.php'; ?>

    <div class="container">

      <div class="row">
      
        <div class="col-md-3 hidden-xs">
        	<?php include 'templates/sidebar.php'; ?>
        </div>      

        <div class="col-md-9">
        
        	  <h3><? echo FAQ; ?></h3> 
        
              <b>How do I change my avatar?</b>
              <p>In the main lobby screen click the edit character link to change your avatar or upload a custom avatar. 
        	  Custom avatars must be in jpg image format and less than 250kb in size.</p>
        	  
              <b>What is the Move Timer?</b>
        	  <p>The move timer ensures that smooth table play continues by auto folding or dealing hands if a player does not 	  take their turn within the time limit set by the site administrator. 
        	  If a player repeatedly fails to take their turn they will be kicked off the table and any money left in their 	  pot will be added back on to their total bankroll.</p>
              
              <b>Why did I get kicked from a game?</b>
              <p>Players are automatically kicked if they repeadedly fail to take their turn and the game has to auto move 	  them or if they lose connection from the table for more than the allowed time. The lengths of time are 		  variable as they are set by the site administrator.</p>
        
        	  <b>My player is broke, what now?</b>
              <p>If the site administrator has enabled the option, you can renew your initial game credits by clicking the 	  renew button on your &quot;My Player&quot; page.</p>
        
        	 </p>

        </div>
        <div class="col-md-3 hidden-lg hidden-md hidden-sm">
          <?php include 'templates/sidebar.php'; ?>
        </div> 
        
      </div>
      
    </div>

<?php include 'templates/footer.php'; ?>