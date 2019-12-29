<?php 
require('includes/gen_inc.php'); 
require('includes/inc_myplayer.php'); ?>

<?php include 'templates/header.php'; ?>

    <div class="container">

      <div class="row">
      
        <div class="col-md-3">
        	<?php include 'templates/sidebar.php'; ?>
        </div>      

        <div class="col-md-9">
        
            <?php if($bad_msgs != ''){ ?>
            	<div class="alert alert-warning"><?php echo $bad_msgs; ?></div>
            <?php } ?>
            <?php if($message != ''){ ?>
	        	<div class="alert alert-warning"><?php echo $message; ?></div>
            <?php } ?>
        
		<div class="profile">
            <div class="col-sm-12">
                <div class="col-xs-12 col-sm-8">
                    <h2><?php echo $name; ?></h2>
                    <p><strong><?php echo STATS_PLAYER_LOGIN; ?> </strong> <?php echo $lastlogin; ?></p>
                    <p><strong><?php echo STATS_PLAYER_TOURNAMENTS_PLAYED; ?> </strong> <?php echo $tournamentsplayed; ?></p>
                    <p><strong><?php echo STATS_PLAYER_CREATED; ?> </strong> <?php echo $created; ?></p>
                    <p><strong><?php echo STATS_PLAYER_TOURNAMENTS_WON; ?></strong> <?php echo $tournamentswon; ?></p>
                </div>             
                <div class="col-xs-12 col-sm-4 text-center">
                    <figure>
                        <img class="img-circle img-responsive"><?php echo display_ava_profiles($usr); ?></img>
                    </figure>
                </div>
            </div>            
            <div class="col-xs-12 divider text-center">
                <div class="col-xs-12 col-sm-6 emphasis">
                    <h2><strong><?php echo money($winnings); ?></strong></h2>                    
                    <p><small class="label label-warning"><?php echo STATS_PLAYER_BANKROLL; ?></small></p>
                </div>
                <div class="col-xs-12 col-sm-6 emphasis">
                    <h2><strong><?php echo $gamesplayed; ?></strong></h2>                    
                    <p><small class="label label-info"><?php echo STATS_PLAYER_GAMES_PLAYED; ?></small></p>
				</div>
            </div>
    	 </div> 
        
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        
		<?php if((RENEW == 1) && ($winnings == 0) && (($gID == '') || ($gID == 0)) && ($action != 'credit')){ ?>
            <div class="alert alert-warning" align="center">
				<form name="form2" method="post" action="">
					<input type="hidden" name="action" value="renew">
					<b><?php echo PLAYER_IS_BROKE; ?></b><input type="submit" name="Submit" value="<?php echo BUTTON_STATS_PLAYER_CREDIT; ?>" class="btn btn-default">
				</form>
            </div>
		<?php } ?>
        
        <!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
		  <li role="presentation" class="active"><a href="#stats" role="tab" data-toggle="tab"><?php echo PLAYER_STATS; ?></a></li>
		  <li role="presentation"><a href="#avatar" role="tab" data-toggle="tab"><?php echo PLAYER_CHOOSE_AVATAR; ?></a></li>
		</ul>
		
		<!-- Tab panes -->
		<div class="tab-content">
		  <div role="tabpanel" class="tab-pane active" id="stats">
			  <!-- Start Player Statistics -->
			  <h3><?php echo PLAYER_STATS; ?></h3>
			  
				<div class="row">
				  <div class="col-sm-12">
				    <div class="row">
				      <div class="col-xs-8 col-sm-6">
				      <p><b><?php echo STATS_GAME; ?></b></p>
				        <div class="row">
				        
						  <div class="col-xs-6"><?php echo STATS_PLAYER_GAMES_PLAYED; ?></div>
						  <div class="col-xs-6"><?php echo $gamesplayed; ?></div>
						  
						  <div class="col-xs-6"><?php echo STATS_PLAYER_TOURNAMENTS_PLAYED; ?></div>
						  <div class="col-xs-6"><?php echo $tournamentsplayed; ?></div>

						  <div class="col-xs-6"><?php echo STATS_PLAYER_TOURNAMENTS_WON; ?></div>
						  <div class="col-xs-6"><?php echo $tournamentswon; ?></div>

						  <div class="col-xs-6"><?php echo STATS_PLAYER_TOURNAMENTS_RATIO; ?></div>
						  <div class="col-xs-6"><?php echo $tperc; ?></div>						  						  						  
						  
						</div>
				      </div>

				      <div class="col-xs-8 col-sm-6">
				      <p><b><?php echo STATS_HAND; ?></b></p>
						  <div class="row">  
						  <div class="col-xs-6"><?php echo STATS_PLAYER_HANDS_PLAYED; ?></div>
						  <div class="col-xs-6"><?php echo $handsplayed; ?></div>

						  <div class="col-xs-6"><?php echo STATS_PLAYER_HANDS_WON; ?></div>
						  <div class="col-xs-6"><?php echo $handswon; ?></div>

						  <div class="col-xs-6"><?php echo STATS_PLAYER_HAND_RATIO; ?></div>
						  <div class="col-xs-6"><?php echo $handsperc; ?></div>						  			
						  </div>
				      </div>
				    </div>
				  </div>
				</div>
				
				<hr>
				
				<div class="row">
				  <div class="col-sm-12">
				    <div class="row">
				      <div class="col-xs-8 col-sm-6">
				      <p><b><?php echo STATS_MOVE; ?></b></p>
				        <div class="row">
				        
						  <div class="col-xs-6"><?php echo STATS_PLAYER_FOLD_RATIO; ?></div>
						  <div class="col-xs-6"><?php echo $foldperc; ?></div>
						  
						  <div class="col-xs-6"><?php echo STATS_PLAYER_CHECK_RATIO; ?></div>
						  <div class="col-xs-6"><?php echo $checkperc; ?></div>

						  <div class="col-xs-6"><?php echo STATS_PLAYER_CALL_RATIO; ?></div>
						  <div class="col-xs-6"><?php echo $callperc; ?></div>

						  <div class="col-xs-6"><?php echo STATS_PLAYER_ALLIN_RATIO; ?></div>
						  <div class="col-xs-6"><?php echo $allinperc; ?></div>						  						  						  
						  
						</div>
				      </div>

					  <div class="col-xs-8 col-sm-6">
				      <p><b><?php echo STATS_FOLD; ?></b></p>
				        <div class="row">
				        
						  <div class="col-xs-6"><?php echo STATS_PLAYER_FOLD_PREFLOP; ?></div>
						  <div class="col-xs-6"><?php echo $foldpfperc; ?></div>
						  
						  <div class="col-xs-6"><?php echo STATS_PLAYER_FOLD_FLOP; ?></div>
						  <div class="col-xs-6"><?php echo $foldfperc; ?></div>

						  <div class="col-xs-6"><?php echo STATS_PLAYER_FOLD_TURN; ?></div>
						  <div class="col-xs-6"><?php echo $foldtperc; ?></div>

						  <div class="col-xs-6"><?php echo STATS_PLAYER_FOLD_RIVER; ?></div>
						  <div class="col-xs-6"><?php echo $foldrperc; ?></div>						  						  						  
						  
						</div>
				      </div>
				    </div>
				  </div>
				</div>				

            <!-- End Player Statistics -->
		  </div>
		  
		  <div role="tabpanel" class="tab-pane" id="avatar">
			<!-- Start Choose Avatar -->
			<form action="" enctype="multipart/form-data" method="post" name="chngava">
            <h3><?php echo PLAYER_CHOOSE_AVATAR; ?></h3>
            <input name="uploadedfile" type="file" class="fieldsetheadinputs" size="40" />
			<input name="update" type="hidden" id="action" value="image" />
			<br><input class="btn btn-success" name="submit" type="submit" class="betbuttons" id="submit" onClick="showdiv()" value="<?php echo BUTTON_UPLOAD; ?>" />
            </form>  
            <!-- End Choose Avatar -->
		  </div>
		</div>
            
        </div>
        
      </div>
      
    </div>

<?php include 'templates/footer.php'; ?>