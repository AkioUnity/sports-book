<!--betclick/event-other.ctp-->
<li><h2><?php echo $Bet["type"]; ?></h2></li>
<?php foreach ($Bet["BetPart"] AS $BetPart): ?>
    <li>
        <a href="<?php echo Router::url(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'events', 'action' => 'display', $Event["id"])); ?>">
            <?php echo $BetPart["name"]; ?>
        </a>
        <br/>
        <div class="ttime"><?php echo $this->TimeZone->convertDate($Event['date'], 'j/n/Y H:i'); ?></div>
        <div class="right pakelti">
            <div class="lock-btn right"></div>
            <div class="btv right addBet" id="<?php echo $BetPart['id']; ?>">
                <span><?php echo $this->Beth->convertOdd($BetPart["odd"]); ?></span>
                <em><?php echo __("Yes"); ?></em>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </li>
<?php endforeach; ?>