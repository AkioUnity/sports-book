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
</head>
<body <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>dir="rtl"<?php endif; ?>>
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
                <ul class="faq-list">
                    <?php if($this->MyHtml->checkAcl(array('plugin' => null, 'controller' => 'tickets', 'action' => 'index'))): ?>
                        <li>
                            <?php $class = (in_array($this->params['controller'], array('tickets'))) ? 'icon-faq8 active' : 'icon-faq8'; ?>
                            <?php echo $this->MyHtml->link(__('Tickets'), array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'tickets', 'action' => 'index'), array('class' => $class)); ?>
                        </li>
                    <?php endif; ?>

                    <?php if($this->MyHtml->checkAcl(array('plugin' => null, 'controller' => 'deposits', 'action' => 'index'))): ?>
                        <li>
                            <?php $class = (in_array($this->params['controller'], array('deposits'))) ? 'icon-faq9 active' : 'icon-faq9'; ?>
                            <?php echo $this->MyHtml->link(__('Deposit'), array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'deposits', 'action' => 'index'), array('class' => $class)); ?>
                        </li>
                    <?php endif; ?>

                    <?php if(Configure::read('Settings.withdraws') && $this->MyHtml->checkAcl(array('plugin' => null, 'controller' => 'withdraws', 'action' => 'index'))): ?>
                        <li>
                            <?php $class = (in_array($this->params['controller'], array('withdraws'))) ? 'icon-faq9 active' : 'icon-faq9'; ?>
                            <?php echo $this->MyHtml->link(__('Withdraws'), array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'withdraws', 'action' => 'index'), array('class' => $class)); ?>
                        </li>
                    <?php endif; ?>
					
					<?php if($this->MyHtml->checkAcl(array('plugin' => 'casino', 'controller' => 'gamelogs', 'action' => 'index'))): ?>
                        <li>
                            <?php $class = (in_array($this->params['action'], array('gamelogs'))) ? 'icon-faq11 active' : 'icon-faq11'; ?>
                            <?php echo $this->MyHtml->link(__('Casino log'), array('language' => Configure::read('Config.language'), 'plugin' => 'casino', 'controller' => 'GameLogs', 'action' => 'index'), array('class' => $class)); ?>
                        </li>
                    <?php endif; ?>

                    <?php if($this->MyHtml->checkAcl(array('plugin' => null, 'controller' => 'users', 'action' => 'settings'))): ?>
                        <li>
                            <?php $class = (in_array($this->params['action'], array('settings'))) ? 'icon-faq11 active' : 'icon-faq11'; ?>
                            <?php echo $this->MyHtml->link(__('Settings'), array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'users', 'action' => 'settings'), array('class' => $class)); ?>
                        </li>
                    <?php endif; ?>

                    <?php if($this->MyHtml->checkAcl(array('plugin' => null, 'controller' => 'users', 'action' => 'bonus'))): ?>
                        <li>
                            <?php $class = (in_array($this->params['action'], array('bonus'))) ? 'icon-faq5 active' : 'icon-faq5'; ?>
                            <?php echo $this->MyHtml->link(__('Promotional Code'), array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'users', 'action' => 'bonus'), array('class' => $class)); ?>
                        </li>
                    <?php endif; ?>

                    <?php if($this->MyHtml->checkAcl(array('plugin' => null, 'controller' => 'users', 'action' => 'account'))): ?>
                        <li>
                            <?php $class = (in_array($this->params['action'], array('account'))) ? 'icon-faq10 active' : 'icon-faq10'; ?>
                            <?php echo $this->MyHtml->link(__('Account Information'), array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'users', 'action' => 'account'), array('class' => $class)); ?>
                        </li>
                    <?php endif; ?>

                    <?php if($this->MyHtml->checkAcl(array('plugin' => null, 'controller' => 'users', 'action' => 'docs')) && Configure::read('Settings.user_doc_upload')): ?>
                        <li>
                            <?php $class = (in_array($this->params['action'], array('account'))) ? 'icon-faq1 active' : 'icon-faq1'; ?>
                            <?php echo $this->MyHtml->link(__('Upload documentation'), array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => 'users', 'action' => 'docs'), array('class' => $class)); ?>
                        </li>
                    <?php endif; ?>
                </ul>
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