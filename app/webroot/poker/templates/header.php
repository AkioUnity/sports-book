<html>
<head>
<title><?php echo TITLE; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="css/default-style.css">

<!-- Latest compiled and minified JavaScript -->


<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

</head>

<body>

    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="header_logo">  
                        <a href="/"><img src="images/logo.png" alt="Site Logo" ></a>            
                    </div>  
                </div>
                
                <div class="col-lg-6 col-md-4">
                    <div class="Top_Banner_Casino">
                        <a href="/"><img src="images/Homepage_Top_Banner_Casino-IronMan_880x75.png" alt="" ></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-5">
                    <div id="loged">
                        
                            
                        <div class="usr-pl">
                           <?php print_r($_SESSION['Auth']['User']['username']);?>
                        </div>
                        <div class="usr-mo">
                           <?php print_r($_SESSION['Auth']['User']['balance']);?>
                        </div>  
                        <div class="clear"></div>
                        <a href="/eng/tickets" class="usr-btn">My account</a>                            
                        <a href="/eng/users/logout" class="usr-btn">Log out</a>                     
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="container">
    <div class="">    
        <nav id="sports-menu">
            <ul> 
                <li><a href="/">Home</a></li>
                <li><a href="/casino/bingo/index.html">Bingo</a></li>
                <li><a href="/casino/keno/index.html">Keno</a></li>
                <li><a href="/poker/index.php">Poker</a></li> 
                <li><a href="/eng/casino/content">Casino</a></li>
                <li><a href="/eng/contact">Support</a></li>
          </ul>
          <div class="clear"></div>
      </nav>
    </div>
</div>