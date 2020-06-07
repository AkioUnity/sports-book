<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
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
    <meta name="verify-webtopay" content="Layouts/intro.ctp">

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


<div id="content">
    <div class="container">
        <div class="clear"></div>

        <div class="Banner4LinksContainer">
            <div class="Banner4LinksFlex">
                <div class="Banner4Links">
                    <a href="/<?=Configure::read('Config.language')?>">
                        <h2>Sportsbook</h2>
                        <div class="icon"><img src="/theme/Design/img/intro/sportsbook.jpg" alt=""></div>

                    </a>
                </div>
                <div class="Banner4Links">
                    <a  href="/<?=Configure::read('Config.language');?>/casino/content">

                        <h2>Casino</h2>
                        <div class="icon"><img src="/theme/Design/img/intro/casino.jpg" alt=""></div>
                    </a>
                </div>
                <div class="Banner4Links">
                    <a href="/<?=Configure::read('Config.language');?>/live-betting">

                        <h2>Live</h2>
                        <div class="icon"><img src="/theme/Design/img/intro/livebetting.jpg" alt=""></div>
                    </a>
                </div>
                <div class="Banner4Links">
                    <a href="/poker/index.php">
                        <h2>Poker</h2>
                        <div class="icon"><img src="/theme/Design/img/intro/poker.jpg" alt=""></div>
                    </a>
                </div>

            </div>
            <div class="mobile-view">
                <ul data-role="listview" class="tw-ui-listview ui-listview">
                   <li class="homeBox ui-first-child"><a href="/<?=Configure::read('Config.language');?>" class="ui-btn"><span class="homeBox-span"><?php echo __('Sports betting');?></span> <span class="menuBoxIconContainer tw-betting"></span></a></li>

                   <li class="homeBox"><a class="ui-btn" href="/<?=Configure::read('Config.language');?>/live-betting"><span class="homeBox-span"><?php echo __('Live betting');?></span><span class="menuBoxIconContainer tw-sport-1000000" ></span></a></li>

                   <li class="homeBox"><a href="<?php if (!$this->Session->check('Auth.User') AND Configure::read('Settings.login') == 1): ?>/<?=Configure::read('Config.language');?>/users/login/
                    <?php else: ?>
                    /poker/index.php
                    <?php endif;?>
                    " class="ui-btn"><span class="homeBox-span"><?php echo __('Poker');?></span><span class="menuBoxIconContainer tw-live-casino"></span></a></li>

                   <li class="homeBox"><a href="/<?=Configure::read('Config.language');?>/casino/content" class="ui-btn"><span class="homeBox-span"><?php echo __('Casino');?></span><span class="menuBoxIconContainer tw-slot-casino"></span></a></li>

<!--                   <li class="homeBox"><a href="/--><?//=Configure::read('Config.language');?><!--/virtualsports" class="ui-btn"><span class="homeBox-span">--><?php //echo __('Virtual games');?><!--</span><span class="menuBoxIconContainer tw-virtual-games"></span></a></li>-->

                   <li class="homeBox"><a href="mailto:support@planet1x2.com" class="ui-btn"><span class="homeBox-span"><?php echo __('Contact');?></span><span class="menuBoxIconContainer tw-contact"></span></a></li>

                </ul>
            </div>
        </div>

        <div class="clear"></div>

        <?php if(!empty($firstEvent)): ?>
        <div class="top-head"><?php echo $firstEvent["League"]["name"]; ?></div>
        <table class="big-tbl">
            <tr>
                <td class="l-corn"><?php echo __("Today", $this->TimeZone->convertDate($firstEvent['Event']['date'], 'H:i')); ?></td>
                <?php foreach($firstEvent["Bet"][0]["BetPart"] AS $betPart): ?>
                    <td class="on-click" id="<?php echo $betPart["BetPart"]['id']; ?>" class="addBet" data-href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $firstEvent["Event"]["id"]))?>">
                        <span class="right">
                            <?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?>
                        </span>
                        <?php echo $betPart['BetPart']['name']; ?>
                        <span class="clear"></span>
                    </td>
                <?php endforeach;?>
                <td data-href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $firstEvent["Event"]["id"]))?>" class="r-corn">
                    +
                </td>
            </tr>
        </table>
        <?php endif; ?>

        <?php if(!empty($otherEvent)): ?>
        <div class="mid-head">
            <span>
                <?php if($firstEvent["League"]["name"] != $otherEvent[0]["League"]["name"]): ?>
                <?php echo $otherEvent[0]["League"]["name"]; ?>
                <?php endif; ?>
            </span>
        </div>

        <?php $otherEvent = array_chunk($otherEvent, (int)ceil(count($otherEvent)/2)); ?>

        <?php if (isset($otherEvent[0]) && is_array($otherEvent[0]) && !empty($otherEvent[0])): ?>
        <div class="tbl-cell1">
            <table class="small-tbl">
                <?php foreach($otherEvent[0] AS $Event): ?>
                <tr>
                    <td class="l-corn"><?php echo __("Today", $this->TimeZone->convertDate($firstEvent['Event']['date'], 'H:i')); ?></td>
                    <?php foreach($Event["Bet"][0]["BetPart"] AS $betPart): ?>
                        <td class="on-click" id="<?php echo $betPart["BetPart"]['id']; ?>" class="addBet" data-href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $firstEvent["Event"]["id"]))?>">
                        <span class="right">
                            <?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?>
                        </span>
                            <?php echo $betPart['BetPart']['name']; ?>
                            <span class="clear"></span>
                        </td>
                    <?php endforeach;?>
                    <td data-href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $Event["Event"]["id"]))?>" class="r-corn">
                        +
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
        <?php if (isset($otherEvent[1]) && is_array($otherEvent[1]) && !empty($otherEvent[1])): ?>
        <div class="tbl-cell2">
            <table class="small-tbl">
                <?php foreach($otherEvent[1] AS $Event): ?>
                <tr>
                    <td class="l-corn"><?php echo __("Today", $this->TimeZone->convertDate($firstEvent['Event']['date'], 'H:i')); ?></td>
                    <?php foreach($Event["Bet"][0]["BetPart"] AS $betPart): ?>
                        <td class="on-click" id="<?php echo $betPart["BetPart"]['id']; ?>" class="addBet" data-href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $Event["Event"]["id"]))?>">
                        <span id="<?php echo $betPart["BetPart"]['id']; ?>" class=" addBet right">
                            <?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?>
                        </span>
                            <?php echo $betPart['BetPart']['name']; ?>
                            <span class="clear"></span>
                        </td>
                    <?php endforeach;?>
                    <td data-href="<?=Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $Event["Event"]["id"]))?>" class="r-corn">
                        +
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
        <div class="clear"></div>
        <?php endif; ?>
    </div>
</div>

<footer>
    <?php echo $this->element('layout-slots/footer-slot'); ?>
</footer>
<!-- JavaScript -->
<?php echo $this->MyHtml->script(array('jquery.min', 'tooltip', 'carousel', 'BetSlip','script')); ?>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        $('.carousel').carousel();
        $('td.r-corn, td.on-click').click(function(el){
            window.location.href = $(this).attr('data-href');
        });
    });
</script>
</body>
</html>