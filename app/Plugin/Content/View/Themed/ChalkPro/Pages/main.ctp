<div class="main-left">
<!-- Slider START -->
    <?php echo $this->element('Slides/getSlides', array('slides' => $slides)); ?>
<!-- Slider END -->

<!-- Last Minute Bets START -->
<?php if (isset($lastMinuteBets) AND is_array($lastMinuteBets) AND !empty($lastMinuteBets)): ?>
    <div class="mid-cent">
        <h3><?php echo __('Last Minute Bets'); ?></h3>
        <div class="cent-txt txt-pad float">
            <?php foreach ($lastMinuteBets AS $lastMinuteBet): ?>
                <div class="time">
                    <?php if( (int) $this->TimeZone->getRemainingTime($lastMinuteBet['Event']['date'], false) < 10 ): ?>
                        <?php echo __('Shortly'); ?>
                    <?php else: ?>
                        <?php echo $this->TimeZone->getRemainingTime($lastMinuteBet['Event']['date']); ?>
                    <?php endif; ?>
                </div>
                <?php if($lastMinuteBet['Event']['count'] == 1): ?>
                    <?php $name = $lastMinuteBet['League']['name'] . ' - ' . $lastMinuteBet['Event']['name']; ?>
                <?php else: ?>
                    <?php $name = $lastMinuteBet['League']['name'] . ' - ' . __('%d events available', $lastMinuteBet['Event']['count']); ?>
                <?php endif; ?>
                <?php echo $this->MyHtml->link($name, array('language' => $this->Language->getLanguage(), 'plugin' => false, 'controller' => 'sports', 'action' => $lastMinuteBet['League']['id']), array('aco' => false)); ?>
                <div class="clear"></div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<!-- Last Minute Bets END -->

<!-- News START -->
<?php if (isset($news) AND is_array($news) AND !empty($news)): ?>
<div class="mid-cent">
    <h3><?php echo __('News'); ?></h3>
    <?php foreach ($news as $new): ?>
        <div class="cent-txt">
            <h1><?php echo $new['News']['title']; ?></h1>
            <?php echo $new['News']['summary']; ?>
            <?php echo $this->MyHtml->link(__('Read more', true), array('controller' => 'news', 'action' => 'view', $new['News']['id']), array('class' => 'rmore')); ?>
            <div class="clear"></div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<!-- News END -->

<!-- Top bets START -->
<?php if (isset($topBets) AND is_array($topBets)): ?>

<?php endif; ?>
<!-- Top bets END -->
</div>
<div class="main-right">

    <?php if(isset($FootballEvents) AND is_array($FootballEvents) AND !empty($FootballEvents)): ?>
    <div class="mid-cent">
        <h2>
            <?php echo __('Bet now'); ?>
            <span><?php echo __('Football'); ?></span>
        </h2>
        <div class="cent-bet">
            <table>
                <tr>
                    <th></th>
                    <th></th>
                    <th>1</th>
                    <th><?php echo __('Draw'); ?></th>
                    <th>2</th>
                    <th></th>
                </tr>
                <?php foreach ($FootballEvents as $FootballEvent): ?>
                        <?php foreach ($FootballEvent['Event'] as $event): ?>
                        <tr>
                            <td class="t1"><?php echo $this->TimeZone->convertDate($event['Event']['date']); ?></td>
                            <td class="t2"><?php echo $event['Event']['name']; ?></td>
                            <?php $betData = current($event['Bet']); ?>
                            <?php $i = 3; ?>
                            <?php foreach($betData['BetPart'] AS $betPart): ?>
                                <td class="t<?php echo $i++; ?>">
                                    <span id="<?php echo $betPart['BetPart']['id']; ?>" class="addBet vip"><?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?></span>
                                </td>
                            <?php endforeach; ?>
                            <td class="t<?php echo $i++; ?>">
                                <?php echo $this->MyHtml->link(null, array('plugin' => false, 'controller' => 'sports', 'action' => 'display', $event['Event']['league_id'], '#' => $betData['event_id']), array('class' => 'bet-link')); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php if(isset($BasketballEvents) AND is_array($BasketballEvents) AND !empty($BasketballEvents)): ?>
        <div class="mid-cent">
            <h2>
                <?php echo __('Bet now'); ?>
                <span><?php echo __('Basketball'); ?></span>
            </h2>
            <div class="cent-bet">
                <table>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>1</th>
                        <th>2</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <?php foreach ($BasketballEvents as $BasketballEvent): ?>
                        <?php foreach ($BasketballEvent['Event'] as $event): ?>
                            <tr>
                                <td class="t1"><?php echo $this->TimeZone->convertDate($event['Event']['date']); ?></td>
                                <td class="t2"><?php echo $event['Event']['name']; ?></td>
                                <?php $betData = current($event['Bet']); ?>
                                <?php $i = 3; ?>
                                <?php foreach($betData['BetPart'] AS $betPart): ?>
                                    <td class="t<?php echo $i++; ?>">
                                        <span id="<?php echo $betPart['BetPart']['id']; ?>" class="addBet vip"><?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?></span>
                                    </td>
                                <?php endforeach; ?>
                                <td class="t<?php echo $i++; ?>">
                                    <?php echo $this->MyHtml->link(null, array('plugin' => false, 'controller' => 'sports', 'action' => 'display', $event['Event']['league_id'], '#' => $betData['event_id']), array('class' => 'bet-link')); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($IceHockeyEvents) AND is_array($IceHockeyEvents) AND !empty($IceHockeyEvents)): ?>
        <div class="mid-cent">
            <h2>
                <?php echo __('Bet now'); ?>
                <span><?php echo __('Ice Hockey'); ?></span>
            </h2>
            <div class="cent-bet">
                <table>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>1</th>
                        <th><?php echo __('Draw'); ?></th>
                        <th>2</th>
                        <th></th>
                    </tr>
                    <?php foreach ($IceHockeyEvents as $IceHockeyEvent): ?>
                        <?php foreach ($IceHockeyEvent['Event'] as $event): ?>
                            <tr>
                                <td class="t1"><?php echo $this->TimeZone->convertDate($event['Event']['date']); ?></td>
                                <td class="t2"><?php echo $event['Event']['name']; ?></td>
                                <?php $betData = current($event['Bet']); ?>
                                <?php $i = 3; ?>
                                <?php foreach($betData['BetPart'] AS $betPart): ?>
                                    <td class="t<?php echo $i++; ?>">
                                        <span id="<?php echo $betPart['BetPart']['id']; ?>" class="addBet vip"><?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?></span>
                                    </td>
                                <?php endforeach; ?>
                                <td class="t<?php echo $i++; ?>">
                                    <?php echo $this->MyHtml->link(null, array('plugin' => false, 'controller' => 'sports', 'action' => 'display', $event['Event']['league_id'], '#' => $betData['event_id']), array('class' => 'bet-link')); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="clear"></div>