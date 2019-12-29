<!-- Slider START -->
<?php echo $this->element('Slides/getSlides', array('slides' => $slides)); ?>
<!-- Slider END -->

<img src="/theme/Redesign/img/loader.gif" alt="" class="roll" />
<div style="display: none" class="blokas">
    <?php if(isset($FootballEvents) AND is_array($FootballEvents) AND !empty($FootballEvents)): ?>
        <div class="mbox">
            <h3><?php echo __("Football"); ?></h3>
            <?php foreach ($FootballEvents AS $LeagueId => $LeagueEvents): ?>
                <table>
                    <?php foreach ($LeagueEvents["Event"] AS $Event): ?>
                        <tr>
                            <td class="cell1">
                                <span class="icon Football"></span>
                                <?php echo $this->TimeZone->convertDate($Event['date'], "j/n/Y"); ?>
                                <span><?php echo $this->TimeZone->convertDate($Event['date'], 'H:i'); ?></span>
                            </td>
                            <td class="cell2">
                                <?php foreach ($Event["Bet"]["1X2"]["BetPart"] AS $betPart): ?>
                                    <a href="#" onclick="return false;" id="<?php echo $betPart['BetPart']['id']; ?>" class="bet-<?php echo strtolower($betPart['BetPart']['name']) != "x" ? "x" : ""?>l addBet">
                                        <span class="right"><?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?></span>
                                        <?php echo strtolower($betPart['BetPart']['name']) == "x" ? "X" : String::truncate($Event["Team"][$betPart['BetPart']['name']], 18, array('ellipsis' => '...')) ?>
                                        <span class="clear"></span>
                                    </a>
                                <?php endforeach; ?>
                                <div class="clear"></div>
                            </td>
                            <td class="cell3">
                                <?php echo $this->MyHtml->link(null, array('plugin' => false, 'controller' => 'sports', 'action' => 'display', $Event['league_id'], '#' => $Event['id']), array('class' => 'plus')); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if(isset($BasketballEvents) AND is_array($BasketballEvents) AND !empty($BasketballEvents)): ?>
        <div class="mbox">
            <h3><?php echo __("Basketball"); ?></h3>
            <?php foreach ($BasketballEvents AS $LeagueId => $LeagueEvents): ?>
                <table>
                    <?php foreach ($LeagueEvents["Event"] AS $Event): ?>
                        <tr>
                            <td class="cell1">
                                <span class="icon Basketball"></span>
                                <?php echo $this->TimeZone->convertDate($Event['date'], "j/n/Y"); ?>
                                <span><?php echo $this->TimeZone->convertDate($Event['date'], 'H:i'); ?></span>
                            </td>
                            <td class="cell2">
                                <?php foreach ($Event["Bet"]["12"]["BetPart"] AS $betPart): ?>
                                    <a style="width: 172px" href="#" onclick="return false;" id="<?php echo $betPart['BetPart']['id']; ?>" class="bet-<?php echo strtolower($betPart['BetPart']['name']) != "x" ? "x" : ""?>l addBet">
                                        <span class="right"><?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?></span>
                                        <?php echo strtolower($betPart['BetPart']['name']) == "x" ? "X" : String::truncate($Event["Team"][$betPart['BetPart']['name']], 25, array('ellipsis' => '...')); ?>
                                        <span class="clear"></span>
                                    </a>
                                <?php endforeach; ?>
                                <div class="clear"></div>
                            </td>
                            <td class="cell3">
                                <?php echo $this->MyHtml->link(null, array('plugin' => false, 'controller' => 'sports', 'action' => 'display', $Event['league_id'], '#' => $Event['id']), array('class' => 'plus')); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if(isset($IceHockeyEvents) AND is_array($IceHockeyEvents) AND !empty($IceHockeyEvents)): ?>
        <div class="mbox">
            <h3><?php echo __("Ice Hockey"); ?></h3>
            <?php foreach ($IceHockeyEvents AS $LeagueId => $LeagueEvents): ?>
                <table>
                    <?php foreach ($LeagueEvents["Event"] AS $Event): ?>
                        <tr>
                            <td class="cell1">
                                <span class="icon IceHockey"></span>
                                <?php echo $this->TimeZone->convertDate($Event['date'], "j/n/Y"); ?>
                                <span><?php echo $this->TimeZone->convertDate($Event['date'], 'H:i'); ?></span>
                            </td>
                            <td class="cell2">
                                <?php foreach ($Event["Bet"]["1X2"]["BetPart"] AS $betPart): ?>
                                    <a href="#" onclick="return false;" id="<?php echo $betPart['BetPart']['id']; ?>" class="bet-<?php echo strtolower($betPart['BetPart']['name']) != "x" ? "x" : ""?>l addBet">
                                        <span class="right"><?php echo $this->Beth->convertOdd($betPart['BetPart']['odd']); ?></span>
                                        <?php echo strtolower($betPart['BetPart']['name']) == "x" ? "X" : String::truncate($Event["Team"][$betPart['BetPart']['name']], 18, array('ellipsis' => '...')) ?>
                                        <span class="clear"></span>
                                    </a>
                                <?php endforeach; ?>
                                <div class="clear"></div>
                            </td>
                            <td class="cell3">
                                <?php echo $this->MyHtml->link(null, array('plugin' => false, 'controller' => 'sports', 'action' => 'display', $Event['league_id'], '#' => $Event['id']), array('class' => 'plus')); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>