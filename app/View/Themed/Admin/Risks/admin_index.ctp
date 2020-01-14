<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getPluralName(), 2 => __('Risk Management Settings'))))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">

                                    <div class="tab-content">

                                        <?php echo $this->element('flash_message'); ?>

                                        <h4><?php echo __('Setup risk management to settings to secure your sportbook profit and reduce risks.'); ?></h4></br>

                                        <?php
                                        $options = array(
                                            'url' => array(
                                                'language' => $this->language->getLanguage(),
                                                'plugin' => null,
                                                'controller' => 'risks'
                                            ),
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );
                                        echo $this->MyForm->create('Setting', $options);
                                        ?>

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td><?php echo __('Lock betting till event starts'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['betBeforeEventStartDate']['id'], array('value' => $settings['betBeforeEventStartDate']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Lock betting for certain minutes till event start date. 0 stands for no lock."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Lowest stake'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['minBet']['id'], array('value' => $settings['minBet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Lowest amount of money that user must have to place a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest stake'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['maxBet']['id'], array('value' => $settings['maxBet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Highest amount of money that user can use place a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest winning amount'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['maxWin']['id'], array('value' => $settings['maxWin']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Highest amount of money that can be won in one ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Lowest number of events in one ticket'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['minBetsCount']['id'], array('value' => $settings['minBetsCount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Lowest number of events that can be enetered into a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Highest number of events in one ticket'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['maxBetsCount']['id'], array('value' => $settings['maxBetsCount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Highest number of events that can be entered a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('AUOTO PAY WINS'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['auto_pay_wins']['id'], array('value' => $settings['auto_pay_wins']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Automatic payment of winnings:
(Parameters: 0 = No 1 = Yes)"); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('DEFAULT STOCK IMPORT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['default_stock_import']['id'], array('value' => $settings['default_stock_import']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("When no limitation is specified,
this is the default amount value that arises
as a limit to let a bet enter the reserve."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('IGNORE PARSING OFFLINE'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['ignore_passing_offline']['id'], array('value' => $settings['ignore_passing_offline']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("If set to 0, the software will offline
games placed offline by the FEED provider.
If set to 1, the software will leave them online."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('LIVE MAX BET'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['live_max_bet']['id'], array('value' => $settings['live_max_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum amount for a LIVE bet
(Can be inserted on the betslip)."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('LIVE MAX REPEAT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['live_max_repeat']['id'], array('value' => $settings['live_max_repeat']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum number of repeated LIVE ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('LIVE MAX WIN'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['live_max_win']['id'], array('value' => $settings['live_max_win']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum amount for winning a LIVE ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('LIVE MIN BET'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['live_min_bet']['id'], array('value' => $settings['live_min_bet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Minimum amount for a LIVE bet."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('LIVE PREMATCH ALLOW'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['live_prematch_allow']['id'], array('value' => $settings['live_prematch_allow']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Placed on the value \"1\" allows you to play odds
prematch in the livebetting ticket.
\"0\" to disable the feature."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('LIVE STOCK AMOUNT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['live_stock_amount']['id'], array('value' => $settings['live_stock_amount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Amount beyond which a single LIVE play
or multiple is passed through the reserve."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX BET CONFIRMTIME'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_bet_confirmtime']['id'], array('value' => $settings['max_bet_confirmtime']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Number of seconds allowed to confirm one ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX BET IMPORT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_bet_import']['id'], array('value' => $settings['max_bet_import']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum bet amount allowed with a single
pre-match ticket (BETSLIP DIGITABLE AMOUNT)."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX BET REPEAT '); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_bet_repeat']['id'], array('value' => $settings['max_bet_repeat']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum number of times it is allowed
play the same ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('REGION BLACKLIST'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['region_blacklist']['id'], array('value' => $settings['region_blacklist']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Regional block. Leave blank to disable,
otherwise enter the two-digit code of the
nations to want to block separated by comma e
without leaving empty spaces. (A list of codes
possibility is available at the following address:
https://it.wikipedia.org/wiki/ISO_3166-1_alpha-2)."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX BET STOCK ADMIN'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_bet_stock_admin']['id'], array('value' => $settings['max_bet_stock_admin']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Number of minutes within which a bet
in reserve it is automatically rejected.
Set 0 to disable the feature.."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX BET SOCKTIME'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_bet_socktime']['id'], array('value' => $settings['max_bet_socktime']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Number of minutes within which the player can
confirm the proposed changes on a ticket in reserve."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX BET WIN'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_bet_win']['id'], array('value' => $settings['max_bet_win']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum amount allowed with a singleticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX DAILY DP SERVICES'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_daily_dp_services']['id'], array('value' => $settings['max_daily_dp_services']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum default amount that is possible
deposit daily on the service counters.
Leave zero or empty to avoid limits."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX DAILY WD SERVICES'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_daily_wd_services']['id'], array('value' => $settings['max_daily_wd_services']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum default amount that is possible
withdraw daily from the service counters.
Leave zero or empty to avoid limits."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX EVENTS'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_events']['id'], array('value' => $settings['max_events']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum number of events that is possible
play with a single ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX INLINE ODDS'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_inline_odds']['id'], array('value' => $settings['max_inline_odds']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum number of shares to display on
a single row with compact display."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX SYSTEM IMPORT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_system_import']['id'], array('value' => $settings['max_system_import']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum amount TOTAL to play a system."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX SYSTEM SPLITIMPORT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_system_splitimport']['id'], array('value' => $settings['max_system_splitimport']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum amount that can be played for column for
systems."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX SYSTEM WIN'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_system_win']['id'], array('value' => $settings['max_system_win']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum winning amount TOTAL WITHOUT BONUS,
Permission to SEND BY PLAYER for a system."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX SYSTEM WIN NOSTOCK'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_system_win_nostock']['id'], array('value' => $settings['max_system_win_nostock']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum amount of TOTAL payout for a system
above which it goes to RESERVE."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MAX WIN REPEAT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['max_win_repeat']['id'], array('value' => $settings['max_win_repeat']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Total winning amount that you can win on
repeated coupons. Setting \"0\" disables it
this function."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MIN BET IMPORT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['min_bet_import']['id'], array('value' => $settings['min_bet_import']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Minimum amount allowed to play a ticket."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MIN PLAYABLE ODD '); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['min_playable_odd']['id'], array('value' => $settings['min_playable_odd']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Minimum quota to display the prematch quotas
(only major)."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Minimum quota to display the prematch quotas
(only major)'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['min_system_import']['id'], array('value' => $settings['min_system_import']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Minimum quota to display the prematch quotas
(only major)."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('MIN SYSTEM SPLITIMPORT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['min_system_splitimport']['id'], array('value' => $settings['min_system_splitimport']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Minimum amount that can be played on a column
for systems."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('NEW MARKET STOCK IMPORT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['new_market_stock_import']['id'], array('value' => $settings['new_market_stock_import']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum amount playable on a bet whose shares
have been entered for less than 30 minutes."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('NIGHT BET IMPORT PRE MATCH'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['night_bet_import_pre_match']['id'], array('value' => $settings['night_bet_import_pre_match']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum amount allowed during the hours
night in the PRE-MATCH."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('NIGHT BET IMPORT LIVE'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['night_bet_import_live']['id'], array('value' => $settings['night_bet_import_live']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum amount allowed during the hours
night events for LIVE."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('NIGHT INTERVAL '); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['night_interval']['id'], array('value' => $settings['night_interval']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Time interval in which the
night checks (do not enter 00:00)."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('NIGHT WIN IMPORT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['night_win_import']['id'], array('value' => $settings['night_win_import']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Maximum winning amount allowed during
night hours."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('NO BET LIMIT '); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['no_bet_limit']['id'], array('value' => $settings['no_bet_limit']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Allows you to play the desired amount for the
coupon without ever exceeding the winning limit
maximum."); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('PRE STOCK AMOUNT'); ?></td>
                                                <td><?php echo $this->MyForm->input($settings['pre_stock_amount']['id'], array('value' => $settings['pre_stock_amount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Amount beyond which a bet is played
single or multiple is passed through the reserve."); ?></span></td>
                                            </tr>

                                        </table>
                                        <br />
                                        <?php echo $this->MyForm->submit(__('Save', true), array('class' => 'btn')); ?>
                                        <?php echo $this->MyForm->end(); ?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>