<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
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
<header>
    <?php echo $this->element('layout-slots/header-slot'); ?>
</header>

<?php echo $this->element('layout-slots/top-slot'); ?>

<section id="main">
    <div class="container">
        <div class="sidebar2">
            <?php echo $this->element('layout-slots/right-slot'); ?>
        </div>
        <div class="content-main">
            <?php echo $this->element('layout-slots/center-slot'); ?>
        </div>
        <div class="sidebar">
            <?php echo $this->element('layout-slots/left-slot'); ?>
        </div>
        <div class="clear"></div>
    </div>
</section>
<footer>
    <?php echo $this->element('layout-slots/footer-slot'); ?>
</footer>
<!-- JavaScript -->
<?php echo $this->MyHtml->script(array('jquery.min', 'tooltip', 'tab', 'carousel','script')); ?>
<script type="text/javascript" src="<?=$this->Html->url('/theme/Design/js/Ticket.js?'.time());?>"></script>
<script type="text/javascript" src="<?=$this->Html->url('/theme/Design/js/BetSlip.js?'.time());?>"></script>
<script type="text/javascript">
    $(function(){
        Ticket.setUrl('<?php echo Router::url( array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'tickets', 'action' => ''), true ); ?>');
        BetSlip.loadBetSlip('<?php echo Router::url( array('language' => $this->language->getLanguage(), 'plugin' => null, 'controller' => 'tickets', 'action' => 'getBets'), true ); ?>');
    }());
</script>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('.carousel').carousel();
        $( ".tgl-blue" ).click(function() {
            $(this).toggleClass( "opened" );
            $(this).next('.tgl-blue-content').toggle();
        });
        $( ".filt-close" ).click(function() {
            $(this).toggleClass( "opened" );
            $(this).next(".filt-box").toggle();
        });
        $("#selecctall").change(function(){
            $(".checkbox1").prop("checked", $(this).prop("checked"));
        });
        $("#selecctall2").change(function(){
            $(".checkbox2").prop("checked", $(this).prop("checked"));
        });
        $(".nav-tabs a").click(function(){
            $(this).tab("show");
        });
        // Hide bets
        $( "#remall" ).click(function() {
            $( ".reall" ).remove();
        });
        $("#single").on( "click", ".remove", function(event) {
            var currentId = $(this).attr('id');
            $( "." + currentId).remove();
        });
    });
</script>
</body>
</html>