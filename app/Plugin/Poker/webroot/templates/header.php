<html>
<head>
<title><?php echo TITLE; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

<style>
body {
  padding-top: 80px;
}
</style>
</head>

<body>

    <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
        <?php if ($ADMIN == true){ ?>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
       <?php } ?>
          <a class="navbar-brand" href="/"><?php echo TITLE; ?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
        
		<?php if ($ADMIN == true){ ?>
		
		<ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Control Panel <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
	        <li><a href="admin.php"><?php echo ADMIN_MANAGE_TABLES; ?></a></li>
	        <li><a href="admin.php?admin=members"><?php echo ADMIN_MANAGE_MEMBERS; ?></a></li>
	        <li><a href="admin.php?admin=styles"><?php echo ADMIN_MANAGE_STYLES; ?></a></li>
	        <li><a href="admin.php?admin=settings"><?php echo ADMIN_MANAGE_SETTINGS; ?></a></li>
          </ul>
        </li>
		</ul>
        
        <?php } ?>
        
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </nav><!-- /.navbar -->