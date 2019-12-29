<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo __('Crypto betting and casino with NO limits-');?> wizabet</title>
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
<body <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>dir="rtl"<?php endif; ?> class="contact-page">
<div class="overlay"></div>
<header>
    <?php echo $this->element('layout-slots/header-slot'); ?>
</header>

<?php echo $this->element('layout-slots/login-container'); ?>
<?php echo $this->element('layout-slots/mobile-menu-container'); ?>
<?php echo $this->element('layout-slots/top-slot'); ?>

<div class="con-page-content">
	<div class="contact-blue-box">

	            <div class="tgl-content" style="display: block;overflow: hidden;">
					<div class="contact-form">
						<h2><?php echo __("Contact Us"); ?></h2>
						<?php
							echo $this->Form->create('Contact', array());
							echo $this->Form->input('name');
							echo $this->Form->input('email');
							echo $this->Form->input('subject');
							echo $this->Form->input('message', array('type' => 'textarea', 'rows' => '3',));
							echo $this->Form->end('Submit');
						?>
					</div>
					<div class="address">
						<h2><?php echo __("Wizabet"); ?></h2>
						<address>
                            <?php echo __(" 71-75, Shelton Street, Covent Garden, London, WC2H9JQ ");?>
						</address>
					</div>

	        	</div>

	</div>
</div>

<footer class="footer">
    <?php echo $this->element('layout-slots/footer-slot'); ?>
</footer>
<!-- JavaScript -->
<?php echo $this->MyHtml->script(array('jquery.min', 'tooltip')); ?>
<script type="text/javascript" src="<?=$this->Html->url('/theme/Design/js/script.js');?>"></script>
<script type="text/javascript">
    window.onload = date_time('date_time');
    function date_time(id){
        date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
        result = ' '+months[month]+' '+d+', '+year+' '+h+':'+m+':'+s;
        
        document.getElementById('date_time').innerHTML = result;
        setTimeout('date_time("'+id+'");','1000');
        return true;
    
    }
</script>

</body>
</html>