<div class="main-bets">
    <!-- Banner START -->
    <div class="banner">
        <img src="<?=$this->Html->url('/theme/Design/img/banners/'.$Event['Sport']['id'].'.png');?>" alt="" class="bg">
        <div class="vs"><a href=""><?php echo $Event["Event"]['firstTeam']; ?><span><?php echo __("VS"); ?></span><?php echo $Event["Event"]['secondTeam']; ?></a></div>
        <div class="clock-gray"><img src="<?=$this->Html->url('/theme/Design/img/time-small-w.png');?>" alt="" class="clock">
            <?php echo $this->TimeZone->convertTime(strtotime($Event["Event"]['date']), "j/n/Y"); ?>
            <?php echo $this->TimeZone->convertTime(strtotime($Event["Event"]['date']), 'H:i'); ?>
        </div>
        <button type="button" class="btn-silver" onclick="window.history.go(-1); return false;"><?php echo __("Back"); ?></button>
    </div>
    <!-- Banner END -->

    <div class="light-menu">
        <ul>
            <li><a href="/<?=Configure::read('Config.language');?>/sports/<?=$Event['Sport']['id']; ?>"><?php echo $Event['Sport']['name']; ?></a></li>
            <li><a href="/<?=Configure::read('Config.language');?>/sports//<?=$Event['Sport']['id']; ?>/<?=$Event['League']['id']; ?>"><?php echo $Event['League']['name']; ?></a></li>
        </ul>
    </div>
    <div class="sep">
        <?php foreach ($Event["Bet"] AS $Bet): ?>
            <?php echo $this->element('Events/Betclick/'. $this->Beth->countToPath(count($Bet["BetPart"])), array('Event' => $Event['Event'], 'League' => $Event["League"], "Bet" => $Bet)); ?>
        <?php endforeach; ?>
    </div>
</div>