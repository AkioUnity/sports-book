<!-- Slider START -->
<?php echo $this->element('Slides/getSlides', array('slides' => $slides)); ?>
<!-- Slider END -->
<div class="main-bets">
    <!--Design/pages/main.ctp-->
    <ul class="nav nav-tabs" role="tablist">
        <?php $active = null; ?>
        <?php foreach(array_keys($lastMinuteBets) AS $i => $sportName): ?>
            <?php if (isset($lastMinuteBets[$sportName]) && is_array($lastMinuteBets[$sportName]) && !empty($lastMinuteBets[$sportName])): ?>
                <?php $active = is_null($active) ? $sportName : $active ; ?>
                <li class="<?=$lastMinuteBetsClass[$sportName];?> <?php if($active == $sportName): ?>active<?php endif; ?>">
                    <a href="#<?=strtolower($sportName);?>" aria-controls="<?=strtolower($sportName);?>" role="tab" data-toggle="tab">
                        <span class="menutxt"><?=ucfirst(__($sportName));?></span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <h1><?php echo __("Last minute bets"); ?></h1>
    <div class="clear"></div>
    <div class="tab-content">
        <?php foreach($lastMinuteBets AS $sportName => $data): ?>
            <?php if (is_array($lastMinuteBets[$sportName]) && !empty($lastMinuteBets[$sportName])): ?>
            <div role="tabpanel" class="tab-pane <?php if($active == $sportName): ?>active<?php endif; ?>" id="<?=strtolower($sportName);?>">
                <?php foreach ($lastMinuteBets[$sportName] AS $Event): ?>
                <h2 class="h2-bets"><?php echo $Event["League"]["name"]; ?></h2>
                <ul class="bets-panel no-border-bottom">
                    <li>
                        <a href="<?php echo Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $Event["Event"]["id"])); ?>">
                            <?php echo $Event["Event"]["name"]; ?>
                        </a>
                        <br />
                        <div class="ttime"><?php echo $this->TimeZone->convertDate($Event["Event"]['date'], 'j/n/Y H:i'); ?></div>
                        <div class="right pakelti">
                            <?php foreach($Event["Bet"][0]["BetPart"] AS $BetPart): ?>
                                <div class="lmb click-add addBet" id="<?php echo $BetPart['id']; ?>">
                                    <span><?php echo $this->Beth->convertOdd($BetPart["odd"]);?></span>
                                    <em><?php echo $BetPart["line"];?></em>
                                    <div class="clear"></div>
                                </div>
                            <?php endforeach; ?>
                            <a href="" onclick="return false;" class="lmb-tv"></a>
                            <a href="" onclick="return false;" class="lmb-chart"></a>
                            <a href="<?php echo Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $Event["Event"]["id"])); ?>" class="plus click-add"><?=sprintf("+%d", $Event["Event"]["markets_count"])?></a>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </li>
                </ul>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>