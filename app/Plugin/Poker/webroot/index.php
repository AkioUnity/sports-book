<?php
header('Location: lobby.php');
die();
require('includes/gen_inc.php'); 
require('includes/inc_index.php'); 
?>

<?php include 'templates/header.php'; ?>

    <div class="container">

      <div class="row">
      
        <div class="col-md-3">
        	<?php include 'templates/sidebar.php'; ?>
        </div>      

        <div class="col-md-9" align="center">
        <div class="jumbotron" style="background: url(images/header.jpg) 50%;">
		  <h2 style="color: #fff;">Welcome to <?php echo TITLE; ?></h2>
		  <p><a class="btn btn-primary btn-lg" href="lobby.php" role="button"><?php echo MENU_LOBBY; ?></a></p>
		</div>
        
        <?php require('includes/scores.php'); ?>
        
        </div>
        
      </div>
      
    </div>

<?php include 'templates/footer.php'; ?>