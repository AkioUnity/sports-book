<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Planet1x2 - Paris Sportif, Casino, Bet</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo $this->MyHtml->meta('keywords', (isset($keywords) ? $keywords : Configure::read('Settings.metaKeywords'))); ?>
    <?php echo $this->MyHtml->meta('description', (isset($description) ? $description : Configure::read('Settings.metaDescription'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'author', 'content' => Configure::read('Settings.metaAuthor'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'reply-to', 'content' => Configure::read('Settings.metaReplayTo'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'copyright', 'content' => Configure::read('Settings.metaCopyright'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'revisit-after', 'content' => Configure::read('Settings.metaRevisitTime'))); ?>
    <?php echo $this->MyHtml->meta(array('name' => 'identifier-url', 'content' => Configure::read('Settings.metaIdentifierUrl'))); ?>
    <meta name="verify-webtopay" content="90d888a7029def4d923a93aaec715262">

    <!-- Core CSS -->
    <?php echo $this->MyHtml->css(array('reset', 'fonts', 'bxslider','font-awesome.min', "icons")); ?>

    <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>
        <link rel="stylesheet" type="text/css" href="<?=$this->Html->url('/theme/Design/css/style-rtl.css?'.time());?>" />
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="<?=$this->Html->url('/theme/Design/css/default-style.css?'.time());?>" />
        <link rel="stylesheet" type="text/css" href="<?=$this->Html->url('/theme/Design/css/style.css?'.time());?>" />
    <?php endif; ?>

    <!--[if lt IE 9]>
    <?php echo $this->MyHtml->script(array('html5shiv.min', 'respond.min.js')); ?>
    <![endif]-->
</head>
<body <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>dir="rtl"<?php endif; ?> class="intro-page">

<header>
    <?php echo $this->element('layout-slots/header-slot'); ?>
</header>

<?php echo $this->element('layout-slots/top-slot'); ?>

<div class="clear"></div>
<div class="virtual-casino-content">
    <div class="container">
        <div class="virtual-main">
            <h2><?php echo __('Virtual Sports') ;?></h2>
            <?php if (!$this->Session->check('Auth.User') AND Configure::read('Settings.login') == 1): ?>
            <?php $href = $this->MyHtml->url( array('language' => Configure::read('Config.language'), 'plugin' => false, 'controller' => 'users', 'action' => 'login'), array('class' => 'btn-blue')); ?>

            <div class="virtual-item">
                <img src="<?=$this->Html->url('/casino/assets/greyhound.jpg');?>">

                <h3>Greyhound racing</h3>

                <div class="playg">
                    <button onclick="window.location.href='<?=$href;?>';" id="signin-button" >Play</button>
                </div>
            </div>

            <div class="virtual-item">
                <img src="<?=$this->Html->url('/casino/assets/horse.jpg');?>">

                <h3>Horse racing</h3>

                <div class="playg">
                    <button onclick="window.location.href='<?=$href;?>';" id="signin-button" >Play</button>
                </div>
            </div>
            <?php else: ?>

            <div class="virtual-item">
               <img src="<?=$this->Html->url('/casino/assets/greyhound.jpg');?>">

               <h3>Greyhound racing</h3>

                <div class="playg">
                    <a href="/casino/greyhound-racing/index.html">Play</a>
                </div>

            </div>

            <div class="virtual-item">
                <img src="<?=$this->Html->url('/casino/assets/horse.jpg');?>">

                <h3>Horse racing</h3>

                <div class="playg">
                    <a href="/casino/horse-racing/index.html">Play</a>
                </div>
            </div>

            <?php endif; ?>

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