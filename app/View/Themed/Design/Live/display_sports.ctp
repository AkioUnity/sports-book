<?php echo $this->element('Slides/getSlides', array('slides' => $slides)); ?>
<?php if(isset($data) && is_array($data) && !empty($data)): ?>
<div class="main-bets">
<!--    live/display_sports.ctp        eng/live-betting/2   -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane-sport icon-sport<?=$Sport["Sport"]["id"]?> hover">
            <h1 class="inside"><?php echo $Sport["Sport"]["name"]; ?></h1>
        </div>
        <?php foreach ($data AS $League): ?>
        <div role="tabpanel" class="tab-pane active">
            <h4><?php echo $League["League"]["name"]; ?></h4>
            <ul class="bets-panel">
                <?php $totalEvents = count($League["Event"]); ?>
                <?php foreach ($League["Event"] AS $i => $Event): ?>
                    <?php foreach($Event["Bet"] AS $Bet): ?>
                        <?php  echo $this->element('Events/Betclick/event', array('Event' => $Event, 'Bet' => $Bet)); ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>