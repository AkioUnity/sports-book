<?php
require('includes/gen_inc.php'); 
require('includes/inc_rankings.php'); 
?>

<?php include 'templates/header.php'; ?>

    <div class="container">

      <div class="row">
      
        <div class="col-md-3 hidden-xs">
        	<?php include 'templates/sidebar.php'; ?>
        </div>      

        <div class="col-md-9">

            <?php
            $staq = mysql_query("select ".DB_STATS.".winpot, ".DB_STATS.".rank, ".DB_STATS.".player, ".DB_STATS.".gamesplayed, ".DB_STATS.".tournamentsplayed, ".DB_STATS.".tournamentswon, ".DB_PLAYERS.".datecreated, ".DB_PLAYERS.".lastlogin  from ".DB_STATS.", ".DB_PLAYERS." where ".DB_PLAYERS.".username = ".DB_STATS.".player and ".DB_PLAYERS.".banned = '0' order by ".DB_STATS.".gamesplayed desc");
            $r = 0;
            while($star = mysql_fetch_array($staq)){	
            $name = $star['player'];
            //$win = $star['winpot'];
            $r++;
            $rank = $r;
            $tplayed = $star['tournamentsplayed'];
            $twon = $star['tournamentswon'];
            $played = $star['gamesplayed'];
            $created = date("m-d-Y",$star['datecreated']);
            $lastlogin = date("m-d-Y",$star['lastlogin']);
            ?>
 
    	 <div class="profile">
            <div class="col-xs-12 col-sm-3">
                
                <div class="player">
                    <figure>
                        <img class="img-circle img-responsive"><?php echo display_ava_rankings($name); ?></img>
                    </figure>
                </div>
                <div class="player">
                    <h2><?php echo $name; ?></h2>
                    <p><strong><?php echo STATS_PLAYER_LOGIN; ?> </strong> <?php echo $lastlogin; ?></p>
                    <p><strong><?php echo STATS_PLAYER_TOURNAMENTS_PLAYED; ?> </strong> <?php echo $tplayed; ?></p>
                    <p><strong><?php echo STATS_PLAYER_CREATED; ?> </strong> <?php echo $created; ?></p>
                    <p><strong><?php echo STATS_PLAYER_TOURNAMENTS_WON; ?></strong> <?php echo $twon; ?></p>
                </div>             
                
                <div class="player">
                    <h2><strong> <?php  echo $rank; ?> </strong></h2>                    
                    <p><small class="label label-success"><?php echo STATS_PLAYER_RANKING; ?></small></p>
                </div>
                <div class="player">
                    <h2><strong><?php echo $played; ?></strong></h2>                    
                    <p><small class="label label-info"><?php echo STATS_PLAYER_GAMES_PLAYED; ?></small></p>
				</div>
            </div>            
            
    	 </div>
        <?php } ?>
                
        </div>
        
        <div class="col-md-3 hidden-lg hidden-md hidden-sm">
          <?php include 'templates/sidebar.php'; ?>
        </div>
        
      </div>
      
    </div>

<?php include 'templates/footer.php'; ?>