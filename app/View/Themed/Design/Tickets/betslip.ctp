<h2 class="ico-slip"><?php echo __('Bet Slip'); ?></h2>
<div class="bl-box">
    <form name="betslip" action="" method="post">
        <ul class="nav nav-tabs" role="tablist">
            <?php if(isset($bets) AND is_array($bets) AND !empty($bets)): ?>
                <li <?php if(count($bets) == 1):?>class="active"<?php endif;?>><a onclick="return false;" href="#betslip-single" aria-controls="betslip-single" role="tab" ><?php echo __("Single"); ?></a></li>
                <li <?php if(count($bets) > 1):?>class="active"<?php endif;?>><a onclick="return false;" href="#betslip-multi" aria-controls="betslip-multi" role="tab" ><?php echo __("Multi"); ?></a></li>
             <?php else: ?>
                
            <?php endif; ?>
        </ul>
        <div class="clear"></div>
        <div class="tab-content">
            <?php if(isset($bets) AND is_array($bets) AND !empty($bets)): ?>
            <div role="tabpanel" class="tab-pane active" id="<?php if(count($bets) == 1):?>betslip-single<?php else:?>betslip-multi<?php endif;?>">
                <?php foreach($bets AS $bet): ?>
                <div class="stake reall rem1">
                    <div class="stake-top">
                        <a onclick="Ticket.removeBet(<?php echo $bet['BetPart']['id']; ?>, $(this));" href="#real2" class="remove stsckrem" id="rem_<?php echo $bet['BetPart']['id']; ?>"></a>
                        <?php echo $bet['League']['name']; ?>
                        <div class="clear"></div>
                    </div>
                    <h3><?php echo $bet['Event']['name']; ?></h3>
                    <h4>
                        <?php echo $bet['Bet']['name']; ?>
                        <?php if(strtolower($bet['Bet']['type']) == "under/over"):?>
                            <?php echo $bet['BetPart']['line']; ?>
                        <?php endif;?>
                    </h4>
                    <div class="stake-pick"><?php echo __('Your pick'); ?>: <?php echo $bet['BetPart']['name']; ?></div>
                    <div class="stake-no float-right"><?php echo $bet['BetPart']['odd']; ?></div>
                    <input name="type[]" type="hidden" value="<?php echo $bet['Event']['type']; ?>">
                    <div class="clear"></div>
                </div>
                <?php endforeach; ?>
                <div id="bestlip_errors" class="error-log">
                    <?php echo $this->element('flash_message'); ?>
                    <?php echo $this->element('Tickets/BetSlip/flash_message'); ?>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="betslip-multi"></div>
            <?php else: ?>
                <div role="tabpanel" class="tab-pane active" id="betslip-information">
                    <div class="stake reall rem1">
                        <?php if (!$this->Session->check('Auth.User') AND Configure::read('Settings.login') == 1): ?>
                            <div class="stake-top"><?php echo __("Information"); ?><div class="clear"></div></div>
                            <div style="margin-top: 10px; font:13px 'Fira Sans',Arial,Helvetica,sans-serif;"><?php echo __("Please");?>
                                <a href="/<?=Configure::read('Config.language');?>/users/login"><?php echo __("Log in");?></a>
                                <?php echo __("or");?>
                                <a href="/<?=Configure::read('Config.language');?>/users/register"><?php echo __("Register");?></a>
                                <?php echo __("on the website to place tickets.");?></div>
                        <?php endif; ?>
                        <?php if(isset($bets) AND is_array($bets) AND !empty($bets)): ?>
                        <?php else: ?>
                            <div style="margin-top: 5px; font:13px 'Fira Sans',Arial,Helvetica,sans-serif;"><?php echo __('You need at least one bet to place a ticket.'); ?></div>
                        <?php endif; ?>
                        <div class="clear"></div>
                    </div>
                    <div id="bestlip_errors" class="error-log ">
                        <?php echo $this->element('flash_message'); ?>
                        <?php echo $this->element('Tickets/BetSlip/flash_message'); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <div class="total">
            <span class="total-odds"><?php echo __('Total odds'); ?>: <?php echo $this->Beth->convertOdd($totalOdds); ?></span>
            <span class="total-bets"><?php echo __('Total bets'); ?>: <?php echo $betsCount; ?></span>
            <div class="clear"></div>
        </div>
        <div class="total">
            <input class="stake-total" name="stake" id="total-stake"  placeholder="<?=Configure::read('Settings.currency');?> 0.00" type="text" value="<?php echo $totalStake; ?>">
            <?php echo __('Stake'); ?>:
            <div class="clear"></div>
        </div>
        <div class="winings">
            <input class="stake-total" name="possible-winning" placeholder="<?=Configure::read('Settings.currency');?> 0.00" type="text" value="<?=Configure::read('Settings.currency');?> <?php echo $this->Beth->convertCurrency($totalWinning); ?>" disabled="disabled">
            <?php echo __("Possible Winnings")?>:
            <div class="clear"></div>
        </div>

        <?php if(isset($bets) AND count($bets) >= 1): ?>
            <?php if( (isset($ajaxBetting) AND !$ajaxBetting) || !isset($ajaxBetting) ): ?>
                <?php echo $this->MyHtml->link(__('Place Bet', true), array('controller' => 'tickets', 'action' => 'place'), array('id' => 'betslip-place', 'class' => 'side-btn-blue')); ?>
            <?php else: ?>
                <?php echo $this->MyHtml->link(__('Place Bet', true), '#', array('class' => 'ajax submit-bet')); ?>
            <?php endif; ?>
        <?php endif; ?>
    </form>
</div>

<script>
    $('.stsckrem').click(function(){
        let stack_id = $(this).attr('id').replace(/\D/g, '');;
        $("#"+stack_id).removeClass('current');
    })
</script>