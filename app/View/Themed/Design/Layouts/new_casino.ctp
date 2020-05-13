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
    <?php if (in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>
        <link rel="stylesheet" type="text/css"
              href="<?= $this->Html->url('/theme/Design/css/style-rtl.css?' . time()); ?>"/>
    <?php else: ?>
        <link rel="stylesheet" type="text/css"
              href="<?= $this->Html->url('/theme/Design/css/style.css?' . time()); ?>"/>
    <?php endif; ?>

    <!--[if lt IE 9]>
    <?php echo $this->MyHtml->script(array('html5shiv.min', 'respond.min.js')); ?>
    <![endif]-->

</head>
<body <?php if (in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>dir="rtl"<?php endif; ?>>
<style>
    .reverse {
        unicode-bidi: bidi-override;
        direction: rtl;
    }

    .cardHolder {
        display: flex;
        flex-flow: row wrap;
        justify-content: center;
    }

    .card {
        flex: 1;
        margin: .5em;
        height: 235px;
        width: 235px;
        min-width: 235px;
        background-color: #161616;
        background-image: url("/Casino/assets/coming.png");
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
<!--/Design/Layouts/new_casino.ctp-->
<section id="main">
    <div class="cardHolder">
        <!--        --><?php //if (!$this->Session->check('Auth.User') AND Configure::read('Settings.login') == 1): ?>
        <?php foreach ($games as $key => $value) { ?>
            <a href="content/createDemoSession?GameId=<?php echo $value['Id'];?>" target="_blank">
                <div class="card" style='background-image: url("/Casino/games/<?php echo $value['Id'] ?>.jpg");'>
                </div>
            </a>
        <?php } ?>
    </div>
    <?php echo $this->element('layout-slots/center-slot'); ?>
</section>
<footer>
    <?php echo $this->element('layout-slots/footer-slot'); ?>
</footer>
<!-- JavaScript -->
<?php echo $this->MyHtml->script(array('jquery.min', 'tooltip')); ?>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(".tgl").click(function () {
            $(this).toggleClass("opened");
            $(this).next('.tgl-content').toggle();
        });
    });
</script>
<script>
    // var boxes = document.querySelectorAll('.card');
    // var delay = .05; // seconds
    // var last = boxes[0].offsetTop;
    // var col = 0;
    // var row = 0;
    // for (var i = 0; i < boxes.length; i++) {
    //     if (boxes[i].offsetTop > last) {
    //         row = row + 1;
    //         col = 0;
    //     }
    //     var last = boxes[i].offsetTop;
    //     boxes[i].style.animationDelay = (row + col) * delay + 's';
    //     col = col + 1;
    // }
</script>
</body>
