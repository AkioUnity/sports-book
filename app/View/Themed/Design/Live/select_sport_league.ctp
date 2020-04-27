<?php echo $this->element('Slides/getSlides', array('slides' => $slides)); ?>
<?php if (isset($data) && is_array($data) && !empty($data)): ?>
    <div class="main-bets">
        <!--        live/select_sport_league.ctp    eng/live-betting-->
        <?php foreach ($data AS $leagues): ?>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane-sport icon-sport<?= $leagues["sport_id"] ?> hover">
                    <h1 class="inside"><?php echo $sports[$leagues["sport_id"]]; ?></h1>
                </div>
                <?php foreach ($leagues["data"] AS $League): ?>
                    <div role="tabpanel" class="tab-pane active">
                        <h4><?php echo $League["name"]; ?></h4>
                        <ul class="bets-panel">
                            <?php $totalEvents = count($League["Event"]); ?>
                            <?php foreach ($League["Event"] AS $i => $Event): ?>
                                <?php foreach ($Event["Bet"] AS $Bet):
//                                print_r ($Sport[$leagues["sport_id"]]["Sport"]); ?>
<!--                                    <li><h2>--><?php //echo $Bet["type"]; ?><!--</h2></li>-->
                                    <?php  echo $this->element('Events/Betclick/event', array('Event' => $Event, 'Bet' => $Bet)); ?>
<!--                                    --><?php // echo $this->element('Events/Betclick/event-other', array('Event' => $Event, 'Bet' => $Bet)); ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="main-bets">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane-sport> hover">
                <h1 class="inside"></h1>
            </div>
            <p align="center"><?= __("There are no live events at this moment"); ?></p>
        </div>
    </div>
<?php endif; ?>