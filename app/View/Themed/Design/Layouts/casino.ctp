<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo Configure::read('Settings.defaultTitle'); ?></title>
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
    <?php endif; ?>

    <!--[if lt IE 9]>
    <?php echo $this->MyHtml->script(array('html5shiv.min', 'respond.min.js')); ?>
    <![endif]-->
	
</head>
<body <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>dir="rtl"<?php endif; ?>>
<style>
.reverse {
unicode-bidi: bidi-override;
direction: rtl;
}

.cardHolder {
  display: flex;
  flex-flow: row wrap;
}

.card {
  flex: 1;
  margin: .5em;
  height: 300px;
  min-width: 400px;
  background-color: #161616;
  background-image: url("/casino/assets/coming.png");
  border-radius: 3px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  transition: all .25s ease;
  animation: populate .5s ease-out normal backwards;
}
.card:hover {
  transform: scale(1.05);
  z-index: 1;
  box-shadow: 0 5px 12px rgba(0, 0, 0, 0.2);
}

@keyframes populate {
  0% {
    transform: scale(0);
  }
}
</style>

<header>
    <?php echo $this->element('layout-slots/header-slot'); ?>
</header>

<?php echo $this->element('layout-slots/top-slot'); ?>

<section id="main">
	<div class="cardHolder">
		<?php if (!$this->Session->check('Auth.User') AND Configure::read('Settings.login') == 1): ?>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("casino/assets/bingo.jpg");'>&nbsp;</div>
      <div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/keno.jpg");'>&nbsp;</div>
      <div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/bj.png");'>&nbsp;</div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/roulette-royale.jpg");'>&nbsp;</div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/highlow.png");'>&nbsp;</div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/roulette.png");'>&nbsp;</div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/stud.jpg");'></div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/baccarat.jpg");'></div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/scratch.jpg");'></div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/chicken.jpg");'></div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/christmas.jpg");'></div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/fruits.jpg");'></div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/ramses.jpg");'></div>
			<div class="card" onclick="location.href='/<?=Configure::read('Config.language');?>/users/login';" style='background-image: url("/casino/assets/space.jpg");'></div>
		<?php else: ?>
			<div class="card" onclick="location.href='/casino/keno/index.html';" style='background-image: url("/casino/assets/keno.jpg");'>&nbsp;</div>
      <div class="card" onclick="location.href='/casino/bingo/index.html';" style='background-image: url("/casino/assets/bingo.jpg");'>&nbsp;</div>
      <div class="card" onclick="location.href='/casino/blackjack/index.html';" style='background-image: url("/casino/assets/bj.png");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/roulette-royale/index.html';" style='background-image: url("/casino/assets/roulette-royale.jpg");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/highlow/index.html';" style='background-image: url("/casino/assets/highlow.png");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/roulette/index.html';" style='background-image: url("/casino/assets/roulette.png");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/stud/index.html';" style='background-image: url("/casino/assets/stud.jpg");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/baccarat/index.html';" style='background-image: url("/casino/assets/baccarat.jpg");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/scratch/index.html';" style='background-image: url("/casino/assets/scratch.jpg");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/slot-chicken/index.html';" style='background-image: url("/casino/assets/chicken.jpg");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/slot-christmas/index.html';" style='background-image: url("/casino/assets/christmas.jpg");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/slot-fruits/index.html';" style='background-image: url("/casino/assets/fruits.jpg");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/slot-ramses/index.html';" style='background-image: url("/casino/assets/ramses.jpg");'>&nbsp;</div>
			<div class="card" onclick="location.href='/casino/slot-space/index.html';" style='background-image: url("/casino/assets/space.jpg");'>&nbsp;</div>
		<?php endif; ?>
        <div class="card" onclick="location.href='/casino/wuking-slot/index.html';" style='background-image: url("/casino/assets/wuking-slot.png");'>&nbsp;</div>
	</div>
	<?php echo $this->element('layout-slots/center-slot'); ?>
</section>
<footer>
    <?php echo $this->element('layout-slots/footer-slot'); ?>
</footer>
<!-- JavaScript -->
<?php echo $this->MyHtml->script(array('jquery.min', 'tooltip')); ?>
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