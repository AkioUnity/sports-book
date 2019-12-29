<li>

    <a href="<?php echo Router::url(array('language' => Configure::read('Config.language'), 'plugin' => $Event["type"] == 1 ? 'events' : null, 'controller' => $Event["type"] == 1 ? 'events' : 'live', 'action' => $Event["type"] == 1 ? 'display' : 'display_event', $Event["id"])); ?>">
        <?php echo $Event["name"]; ?>
    </a>
    <br />
    <?php if($Event["type"] == Event::EVENT_TYPE_LIVE): ?>
        <div class="ttime">
            <?php $time = round($Event['duration'] / 60); ?>
            <?php if($time >= 0): ?>
                <span><?php echo __("Time: %s min", $time); ?></span>
            <?php else: ?>
                <span><?php echo __("In progress"); ?></span>
            <?php endif; ?>
            <span><?php echo __("Score: %s", $Event['result']); ?></span>
        </div>
    <?php else: ?>
        <div class="ttime"><?php echo $this->TimeZone->convertDate($Event['date'], 'j/n/Y H:i'); ?></div>
    <?php endif; ?>
    <div class="right pakelti">
        <?php foreach ($Bet["BetPart"] AS $BetPart): ?>
            <?php $parts = array_map(function($part){ return !empty($part["line"]) ? $part["line"] : $part["name"]; }, $Bet["BetPart"]);?>
            <?php if (!in_array(1, $parts)): ?>
                <div class="lmb lock"></div>
            <?php endif; ?>
            <?php if ((isset($BetPart["suspended"]) && $BetPart["suspended"] == 1) || $Bet['state'] == 'inactive' || $BetPart["state"] == "inactive"): ?>
                <div class="lmb lock"></div>
            <?php else: ?>
            <div class="lmb click-add addBet" id="<?php echo $BetPart['id']; ?>">
                <span><?php echo $this->Beth->convertOdd($BetPart["odd"]);?></span>
                <?php if(!empty($BetPart["line"])): ?>
                    <em><?php echo $BetPart["line"];?></em>
                <?php else: ?>
                    <em><?php echo $BetPart["name"];?></em>
                <?php endif; ?>
                <div class="clear"></div>
            </div>
            <?php endif; ?>
            <?php if (!in_array(2, $parts)): ?>
                <div class="lmb lock"></div>
            <?php endif; ?>
        <?php endforeach; ?>
        <a href="" class="lmb-tv" onclick="return false;"></a>
        <a href="" class="lmb-chart" onclick="return false;"></a>
        <a href="<?php echo Router::url(array('language' => Configure::read('Config.language'), 'plugin' => $Event["type"] == 1 ? 'events' : null, 'controller' => $Event["type"] == 1 ? 'events' : 'live', 'action' => $Event["type"] == 1 ? 'display' : 'display_event', $Event["id"])); ?>" class="plus click-add"><?=sprintf("+%d", $Event["markets_count"])?></a>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</li>