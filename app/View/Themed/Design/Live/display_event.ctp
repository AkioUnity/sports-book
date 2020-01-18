<?php echo $this->element('Slides/getSlides', array('slides' => $slides)); ?>
<div class="main-bets">
    <!-- Banner START -->
    <div class="banner">
        <img src="<?=$this->Html->url('/theme/Design/img/banners/');?><?=$Event['Sport']['id']; ?>.png" alt="" class="bg">
        <div class="vs"><a href=""><?php echo $Event["Event"]['firstTeam']; ?><span><?php echo __("VS"); ?></span><?php echo $Event["Event"]['secondTeam']; ?></a></div>
        <div class="clock-gray live">
            <span><?php echo __("Score: %s", $Event["Event"]['result']); ?></span>
            <br />
            <img src="<?=$this->Html->url('/theme/Design/img/time-small-w.png');?>" alt="" class="clock">
            <br />
            <?php $time = round($Event["Event"]['duration'] / 60); ?>
            <?php if($time > 0): ?>
            <span><?php echo __("Time: %s min", round($Event["Event"]['duration'] / 60)); ?></span>
            <?php else: ?>
            <span><?php echo __("In progress"); ?></span>
            <?php endif; ?>
        </div>
        <button type="button" class="btn-silver" onclick="window.history.go(-1); return false;"><?php echo __("Back"); ?></button>
    </div>
    <!-- Banner END -->

    <div class="light-menu">
        <ul>
            <li><a href="/<?=Configure::read('Config.language');?>/live-betting/<?=$Event['Sport']['id']; ?>"><?php echo $Event['Sport']['name']; ?></a></li>
            <li><a href="/<?=Configure::read('Config.language');?>/live-betting/<?=$Event['Sport']['id']; ?>/<?=$Event['League']['id']; ?>"><?php echo $Event['League']['name']; ?></a></li>
        </ul>
    </div>
    <div class="sep">
        <?php foreach ($Event["Bet"] AS $Bet): ?>
            <?php if($Bet["state"] != "inactive"): ?>
                <?php echo $this->element('Events/Betclick/'. $this->Beth->countToPath(count($Bet["BetPart"])), array('Event' => $Event['Event'], 'League' => $Event["League"], "Bet" => $Bet)); ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>