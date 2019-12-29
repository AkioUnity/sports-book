<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $this->Admin->getSingularName())))); ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>
    <!-- END PAGE HEADER-->


    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">

        <!-- START BETTING-->
        <div class="row-fluid">

            <div class="span4">
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-user"></i> <?=__('USER');?>: <strong><?=$user_name;?></strong></h4>

                    </div>
                    <div class="widget-body">
                        <div id="site_statistics_loading">
                            <img src="/theme/Admin/img/user.png" alt="chart"/>
                        </div>
                        <div id="site_statistics_content" class="hide">
                            <div id="site_statistics" class="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-file-alt"></i> <?=__('BETTING ACTIVITY');?></h4>
                        <span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									</span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td><?=__('Registration date');?>:</td>
                                <td><strong><?=$registration_date;?></strong></td>
                            </tr>
                            <tr>
                                <td><?=__('Total ticket placed');?>:</td>
                                <td><strong><?=$total_tickets_placed;?></strong></td>
                            </tr>
                            <tr>
                                <td><?=__('Tickets won');?>:</td>
                                <td><strong><?=$total_tickets_won;?></strong></td>
                            </tr>
                            <tr>
                                <td><?=__('Tickets lost');?>:</td>
                                <td><strong><?=$total_tickets_lost;?></strong></td>
                            </tr>
                            <tr>
                                <td><?=__('Tickets pending');?>:</td>
                                <td><strong><?=$total_tickets_pending;?></strong></td>
                            </tr>
                            <tr>
                                <td><?=__('Tickets cancelled');?>:</td>
                                <td><strong><?=$total_tickets_cancelled;?></strong></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="widget">
                    <div class="widget-title">
                        <h4><i class="icon-file-alt"></i> <?=__('FINANCIAL ACTIVITY');?></h4>
                        <span class="tools">
									<a href="javascript:;" class="icon-chevron-down"></a>
									</span>
                    </div>
                    <div class="widget-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td><?=__('Current balance');?>:</td>
                                <td><strong><?php echo __("%s%s", $user_balance, Configure::read('Settings.currency')); ?></strong></td>
                            </tr>
                            <tr>
                                <td><?=__('Last deposit');?>:</td>
                                <?php if(!empty($user_last_deposit)): ?>
                                    <td><strong><?php echo __("%s%s (%s)", $user_last_deposit["Deposit"]["amount"], Configure::read('Settings.currency'), $user_last_deposit["Deposit"]["date"]); ?></strong></td>
                                <?php else: ?>
                                    <td><strong>-</strong></td>
                                <?php endif;?>
                            </tr>
                            <tr>
                                <td><?=__('Average deposit');?>:</td>
                                <td><strong><?php echo __("%s%s", intval($user_average_deposit[0]["AverageDeposit"]), Configure::read('Settings.currency')); ?></strong></td>
                            </tr>
                            <tr>
                                <td><?=__('Last withdrawal');?>:</td>
                                <?php if(!empty($user_last_withdraw)): ?>
                                    <td><strong><?php echo __("%s%s (%s)", $user_last_withdraw["Withdraw"]["amount"], Configure::read('Settings.currency'), $user_last_withdraw["Withdraw"]["date"]); ?></strong></td>
                                <?php else: ?>
                                    <td><strong>-</strong></td>
                                <?php endif;?>
                            </tr>
                            <tr>
                                <td><?=__('Average withdrawal');?>:</td>
                                <td><strong><?php echo __("%s%s", intval($user_average_withdraw[0]["AverageWithdraw"]), Configure::read('Settings.currency')); ?></strong></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END BETTING -->

        <!-- BEGIN TABLE widget-->
        <div class="table table-custom">
            <div class="tab-content">
                <?php echo $this->element('list', array('data' => Ticket::assignTicketData($data)));?>
            </div>
        </div>
        <!-- END TABLE widget-->
    </div>
    <!-- END PAGE CONTENT-->
</div>
