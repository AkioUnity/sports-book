<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planet1x2 - <?php echo __('Paris Sportif, Casino, Bet');?></title>
    <?php echo $this->MyHtml->meta('keywords', (isset($keywords) ? $keywords : Configure::read('Settings.metaKeywords'))); ?>
    <?php echo $this->MyHtml->meta('description', (isset($description) ? $description : Configure::read('Settings.metaDescription'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'author', 'content' => Configure::read('Settings.metaAuthor'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'reply-to', 'content' => Configure::read('Settings.metaReplayTo'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'copyright', 'content' => Configure::read('Settings.metaCopyright'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'revisit-after', 'content' => Configure::read('Settings.metaRevisitTime'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'identifier-url', 'content' => Configure::read('Settings.metaIdentifierUrl'))); ?>
    <meta name="verify-webtopay" content="90d888a7029def4d923a93aaec715262">

    <!-- Core CSS -->
    <?php echo $this->MyHtml->css(array('reset', 'fonts', "icons")); ?>
    <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>
        <link rel="stylesheet" type="text/css" href="<?=$this->Html->url('/theme/Design/css/style-rtl.css?'.time());?>" />
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="<?=$this->Html->url('/theme/Design/css/style.css?'.time());?>" />
        <link rel="stylesheet" type="text/css" href="<?=$this->Html->url('/theme/Design/css/font-awesome.min.css');?>" />
    <?php endif; ?>

    <!--[if lt IE 9]>
    <?php echo $this->MyHtml->script(array('html5shiv.min', 'respond.min.js')); ?>
    <![endif]-->
	
</head>
<body <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>dir="rtl"<?php endif; ?>>

<div class="overlay"></div>
<div class="backtop">
    <a href="#" ID="backToTop"><img src="<?=$this->Html->url('/theme/Design/img/ticket-icon.png');?>"></a> 
</div>
<header>
    <?php echo $this->element('layout-slots/header-slot'); ?>
</header>
<?php echo $this->element('layout-slots/login-container'); ?>
<?php echo $this->element('layout-slots/mobile-menu-container'); ?>
<?php echo $this->element('layout-slots/top-slot'); ?>

<div class="live-casino-content">
    <div class="container">

        <div class="leftsidebar"></div>

        <div class="livecasino-main">
            <div class="live-item">
               <img src="<?=$this->Html->url('/theme/Design/img/live-casino/live1.jpg');?>">
                <div class="hover-text">
                    <h2>Comming soon</h2>
                </div>
            </div>
            <div class="live-item">
                <img src="<?=$this->Html->url('/theme/Design/img/live-casino/live2.jpg');?>">
                <div class="hover-text">
                    <h2>Comming soon</h2>
                </div>
            </div>
            <div class="live-item">
                <img src="<?=$this->Html->url('/theme/Design/img/live-casino/live3.jpg');?>">
                <div class="hover-text">
                    <h2>Comming soon</h2>
                </div>
            </div>
            <div class="live-item">
                <img src="<?=$this->Html->url('/theme/Design/img/live-casino/live4.jpg');?>">
                <div class="hover-text">
                    <h2>Comming soon</h2>
                </div>
            </div>
            <div class="live-item">
                <img src="<?=$this->Html->url('/theme/Design/img/live-casino/live5.jpg');?>">
                <div class="hover-text">
                    <h2>Comming soon</h2>
                </div>
            </div>
            <div class="live-item">
                <img src="<?=$this->Html->url('/theme/Design/img/live-casino/live6.jpg');?>">
                <div class="hover-text">
                    <h2>Comming soon</h2>
                </div>
            </div>
        </div>
        
    </div>
</div>
<div class="clear"></div>
<footer class="footer">
    <?php echo $this->element('layout-slots/footer-slot'); ?>
</footer>
<!-- JavaScript -->
<?php echo $this->MyHtml->script(array('jquery.min', 'tooltip')); ?>
<script type="text/javascript" src="<?=$this->Html->url('/theme/Design/js/script.js');?>"></script>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $( ".tgl" ).click(function() {
            $(this).toggleClass( "opened" );
            $(this).next('.tgl-content').toggle();
        });
    });
</script>
<script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script>
<script >var boxes = document.querySelectorAll('.card');
var delay = .05; // seconds

var last = boxes[0].offsetTop;
var col = 0;
var row = 0;
for (var i = 0; i < boxes.length; i++) {if (window.CP.shouldStopExecution(1)){break;}
  if(boxes[i].offsetTop > last) {
    row = row+1;
    col = 0;
  }
  var last = boxes[i].offsetTop;
  
  boxes[i].style.animationDelay = (row + col) * delay + 's';  
  col = col+1;
}
window.CP.exitedLoop(1);

//# sourceURL=pen.js
</script>
</body>
</html>