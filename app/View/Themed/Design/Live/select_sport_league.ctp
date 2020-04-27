<?php echo $this->element('Slides/getSlides', array('slides' => $slides)); ?>
<?php if (isset($data) && is_array($data) && !empty($data)): ?>
    <div class="main-bets">
        <!--        live/select_sport_league.ctp-->
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

                                    <?php if (in_array($Bet["type"], array(
                                    Bet::BET_TYPE_OUTRIGHT_WINNER, Bet::BET_TYPE_DRIVERS_WINNER,
                                    Bet::BET_TYPE_OUTRIGHT_CHAMPION, Bet::BET_TYPE_CHAMPIONSHIP_WINNER,
                                    Bet::BET_TYPE_PLACE_1_3, Bet::BET_TYPE_WINNER,
                                    Bet::BET_TYPE_DRIVERS_CHAMPIONSHIP_WINNER, Bet::BET_TYPE_CONSTRUCTORS_CHAMPIONSHIP
                                ))): ?>
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
                                <?php endif; ?>
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