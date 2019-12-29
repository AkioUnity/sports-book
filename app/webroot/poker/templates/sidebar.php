<div class="list-group">
  <?php if (($valid == false) && (MEMMOD == 0)){ ?>
  <a href="login.php"><?php echo MENU_LOGIN; ?></a>	
  <?php } ?>
  <?php if ($valid == false){ ?>
  <a href="create.php"><?php echo MENU_CREATE; ?></a>
  <?php } ?>
  <?php if ($valid == true){ ?>
  <a href="lobby.php"><?php echo MENU_LOBBY; ?></a>
  <a href="rankings.php"><?php echo MENU_RANKINGS; ?></a>
  <a href="myplayer.php"><?php echo MENU_MYPLAYER; ?></a>
  <?php } ?>
  <a href="rules.php">Poker Rules</a>
  <a href="faq.php">FAQ</a>
  <?php if ($ADMIN == true){ ?>
  <!--<a href="admin.php" class="list-group-item">Admin</a>-->
  <?php } ?>

</div>