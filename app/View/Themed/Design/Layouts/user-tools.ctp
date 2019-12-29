<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo Configure::read('Settings.defaultTitle'); ?></title>
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
    <?php echo $this->MyHtml->css(array('reset', 'fonts', "icons")); ?>
    <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>
        <link rel="stylesheet" type="text/css" href="<?=$this->Html->url('/theme/Design/css/style-rtl.css?'.time());?>" />
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="<?=$this->Html->url('/theme/Design/css/style.css?'.time());?>" />
    <?php endif; ?>

    <!--[if lt IE 9]>
    <?php echo $this->MyHtml->script(array('html5shiv.min', 'respond.min.js')); ?>
    <![endif]-->

    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>dir="rtl"<?php endif; ?>>
<style>
.reverse {
unicode-bidi: bidi-override;
direction: rtl;
}
</style>

<header>
    <?php echo $this->element('layout-slots/header-slot'); ?>
</header>

<?php echo $this->element('layout-slots/top-slot'); ?>

<section id="main">
    <div class="container">
        <div class="content">
            <?php echo $this->element('layout-slots/center-slot'); ?>
        </div>
        <div class="sidebar">
            <div class="blue-box">
                <div class="blue-in">
                    <h5><i class="icon-support"></i> <?php echo __("Support"); ?></h5>
                    <div class="white-in">
                        <ul>
                            <li><i class="ico icon-contact"></i><strong><?php echo __("Skype us"); ?></strong><br /><span class="reverse">reganaMtcejorPkoobstropS</span><div class="clear"></div></li>
                            <li><i class="ico icon-faq"></i><strong><?php echo __("Call us"); ?></strong><br /><span class="reverse">569130 76 073+</span><div class="clear"></div></li>
                            <li><i class="ico icon-call"></i><strong><?php echo __("Contact us by mail"); ?></strong><br /><a href="http://betscheme.com/#contacts" target="_blank"">Email us</a><div class="clear"></div></li>
                            <li><i class="ico icon-other"></i><strong><?php echo __("Other contacts"); ?></strong><br /><a href="http://betscheme.com/#contacts" target="_blank"">Contacts</a><div class="clear"></div></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
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
</body>
</html>