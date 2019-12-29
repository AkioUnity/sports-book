<div class="list-group">
  <?php if (($valid == false) && (MEMMOD == 0)){ ?>
  <a href="login.php" class="list-group-item"><?php echo MENU_LOGIN; ?></a>	
  <?php } ?>
  <?php if ($valid == false){ ?>
  <a href="create.php" class="list-group-item"><?php echo MENU_CREATE; ?></a>
  <?php } ?>
  <?php if ($valid == true){ ?>
  <a href="lobby.php" class="list-group-item"><?php echo MENU_LOBBY; ?></a>
  <a href="rankings.php" class="list-group-item"><?php echo MENU_RANKINGS; ?></a>
  <a href="myplayer.php" class="list-group-item"><?php echo MENU_MYPLAYER; ?></a>
  <?php } ?>
  <a href="rules.php" class="list-group-item">Poker Rules</a>
  <a href="faq.php" class="list-group-item">FAQ</a>
  <?php if ($ADMIN == true){ ?>
  <!--<a href="admin.php" class="list-group-item">Admin</a>-->
  <?php } ?>
  <?php if ($valid == true){ ?>
  <a href="/" class="list-group-item">Return To Main Website</a>
  <?php } ?>
</div>