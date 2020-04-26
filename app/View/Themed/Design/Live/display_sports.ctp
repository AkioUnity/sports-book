<?php echo "display_sports" ?>
<?php echo $this->element('Slides/getSlides', array('slides' => $slides)); ?>
<?php if(isset($data) && is_array($data) && !empty($data)): ?>
<div class="main-bets">
<!--    live/display_sports.ctp-->
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
                        <?php switch($Sport["SportI18n"][0]["content"]):
                            case Sport::SPORT_TYPE_FOOTBALL:?>
                            <?php case Sport::SPORT_TYPE_RUGBY_UNION:?>
                            <?php case Sport::SPORT_TYPE_GOLF:?>
                            <?php case Sport::SPORT_TYPE_HANDBALL:?>
                            <?php case Sport::SPORT_TYPE_ICE_HOCKEY:?>
                            <?php case Sport::SPORT_TYPE_BOXING:?>
                            <?php case Sport::SPORT_TYPE_FLOORBALL:?>
                            <?php case Sport::SPORT_TYPE_FUTSAL:?>
                            <?php case Sport::SPORT_TYPE_RUGBY_LEAGUE:?>
                            <?php case Sport::SPORT_TYPE_CRICKET:?>
                            <?php case Sport::SPORT_TYPE_BANDY:?>
                            <?php case Sport::SPORT_TYPE_PESAPALLO:?>
                            <?php case Sport::SPORT_TYPE_FIELD_HOCKEY:?>
                            <?php case Sport::SPORT_TYPE_WATERPOLO:?>
                            <?php case Sport::SPORT_TYPE_BEACH_SOCCER:?>
                            <?php case Sport::SPORT_TYPE_BEACH_VOLLEY:?>
                                <?php if ($Bet["type"] == Bet::BET_TYPE_MATCH_RESULT || $Bet["type"] == Bet::BET_TYPE_SECOND_ROUND_2_BALL): ?>
                                    <?php echo $this->element('Events/Betclick/main_1x2',  array('Event' => $Event, 'Bet' => $Bet)); ?>
                                <?php endif; ?>
                                <?php break; ?>
                            <?php case Sport::SPORT_TYPE_DARTS:?>
                                    <?php if ($Bet["type"] == Bet::BET_TYPE_MATCH_WINNER): ?>
                                    <?php echo $this->element('Events/Betclick/main_1x2',  array('Event' => $Event, 'Bet' => $Bet)); ?>
                                <?php endif; ?>
                                <?php break; ?>
                            <?php case Sport::SPORT_TYPE_VOLLEYBALL:?>
                                <?php if ($Bet["type"] == Bet::BET_TYPE_MATCH_RESULT): ?>
                                    <?php echo $this->element('Events/Betclick/main_12',  array('Event' => $Event, 'Bet' => $Bet)); ?>
                                <?php endif; ?>
                                <?php break; ?>
                            <?php case Sport::SPORT_TYPE_FORMULA_1:?>
                                <?php if ($Bet["type"] == Bet::BET_TYPE_HEAD_TO_HEAD_CHAMPIONSHIP): ?>
                                    <?php echo $this->element('Events/Betclick/main_12',  array('Event' => $Event, 'Bet' => $Bet)); ?>
                                <?php endif; ?>
                                <?php break; ?>
                            <?php case Sport::SPORT_TYPE_TENNIS:?>
                            <?php case Sport::SPORT_TYPE_BASKETBALL:?>
                            <?php case Sport::SPORT_TYPE_MARTIAL_ARTS:?>
                            <?php case Sport::SPORT_TYPE_TABLE_TENNIS:?>
                            <?php case Sport::SPORT_TYPE_SNOOKER:?>
                            <?php case Sport::SPORT_TYPE_BASEBALL:?>
                            <?php case Sport::SPORT_TYPE_CURLING:?>
                            <?php case Sport::SPORT_TYPE_AUSTRALIAN_RULES:?>
                            <?php case Sport::SPORT_TYPE_AMERICAN_FOOTBALL:?>
                                <?php if ($Bet["type"] == Bet::BET_TYPE_MATCH_WINNER): ?>
                                    <?php echo $this->element('Events/Betclick/main_12',  array('Event' => $Event, 'Bet' => $Bet)); ?>
                                <?php endif; ?>
                                <?php break; ?>
                            <?php endswitch;?>
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
                                    <?php echo $BetPart["name"];?>
                                </a>
                                <br />
                                <div class="ttime">
                                    <span><?php echo __("Score: %s", $Event['result']); ?></span>
                                    <?php $time = round($Event['duration'] / 60); ?>
                                    <?php if($time >= 0): ?>
                                    <span><?php echo __("Time: %s min", $time); ?></span>
                                    <?php else: ?>
                                    <span><?php echo __("In progress"); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="right pakelti">
                                    <div class="lock-btn right"></div>
                                    <div class="btv right addBet" id="<?php echo $BetPart['id']; ?>">
                                        <span><?php echo $this->Beth->convertOdd($BetPart["odd"]);?></span>
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
</div>
<?php endif; ?>