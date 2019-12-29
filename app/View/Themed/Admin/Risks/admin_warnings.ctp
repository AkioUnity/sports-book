<div class="container-fluid">
    <!-- BEGIN PAGE HEADER-->
    <div class="row-fluid">
        <div class="span12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Warnings'), 2 => __('List Warnings'))))); ?>
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
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
                                        <?php if(empty($bigOddTickets) AND empty($bigStakeTickets) AND empty($bigWinningTickets) AND empty($bigDeposits) AND empty($bigWithdraws)): ?>
                                            <?php echo __('No warnings are available at this moment.'); ?>
                                        <?php endif; ?>
                                        <?php if (!empty($bigOddTickets)): ?>
                                            <h1><?php echo __('Odds Alert'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?php echo __('Id'); ?></th>
                                                    <th><?php echo __('Date'); ?></th>
                                                    <th><?php echo __('User'); ?></th>
                                                    <th><?php echo __('Stake'); ?></th>
                                                    <th><?php echo __('Odd'); ?></th>
                                                    <th><?php echo __('Winning'); ?></th>
                                                </tr>
                                                <?php foreach ($bigOddTickets as $ticket): ?>
                                                    <tr>
                                                        <td><?php echo $ticket['Ticket']['id']; ?></td>
                                                        <td><?php echo $ticket['Ticket']['date']; ?></td>
                                                        <td>
                                                            <?php if($ticket['User']['username'] == null):?>
                                                                <?php echo __('User Terminated'); ?>
                                                            <?php else: ?>
                                                                <?php echo $ticket['User']['username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $ticket['Ticket']['amount'] . ' ' .Configure::read('Settings.currency'); ?></td>
                                                        <td><?php echo $ticket['Ticket']['odd']; ?></td>
                                                        <td><?php echo $ticket['Ticket']['return'] . ' ' . Configure::read('Settings.currency'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>

                                        <?php if (!empty($bigStakeTickets)): ?>
                                            <h1><?php echo __('Warning Stakes'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?php echo __('Id'); ?></th>
                                                    <th><?php echo __('Date'); ?></th>
                                                    <th><?php echo __('User'); ?></th>
                                                    <th><?php echo __('Stake'); ?></th>
                                                    <th><?php echo __('Odd'); ?></th>
                                                    <th><?php echo __('Winning'); ?></th>
                                                </tr>
                                                <?php foreach ($bigStakeTickets as $ticket): ?>
                                                    <tr>
                                                        <td><?php echo $ticket['Ticket']['id']; ?></td>
                                                        <td><?php echo $ticket['Ticket']['date']; ?></td>
                                                        <td>
                                                            <?php if($ticket['User']['username'] == null):?>
                                                                <?php echo __('User Terminated'); ?>
                                                            <?php else: ?>
                                                                <?php echo $ticket['User']['username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $ticket['Ticket']['amount']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                        <td><?php echo $ticket['Ticket']['odd']; ?></td>
                                                        <td><?php echo $ticket['Ticket']['return']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>

                                        <?php if (!empty($bigWinningTickets)): ?>
                                            <h1><?php echo __('Warning Winnings'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?php echo __('Id'); ?></th>
                                                    <th><?php echo __('Date'); ?></th>
                                                    <th><?php echo __('User'); ?></th>
                                                    <th><?php echo __('Stake'); ?></th>
                                                    <th><?php echo __('Odd'); ?></th>
                                                    <th><?php echo __('Winning'); ?></th>
                                                </tr>
                                                <?php foreach ($bigWinningTickets as $ticket): ?>
                                                    <tr>
                                                        <td><?php echo $ticket['Ticket']['id']; ?></td>
                                                        <td><?php echo $ticket['Ticket']['date']; ?></td>
                                                        <td>
                                                            <?php if($ticket['User']['username'] == null):?>
                                                                <?php echo __('User Terminated'); ?>
                                                            <?php else: ?>
                                                                <?php echo $ticket['User']['username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $ticket['Ticket']['amount']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                        <td><?php echo $ticket['Ticket']['odd']; ?></td>
                                                        <td><?php echo $ticket['Ticket']['return']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>

                                        <?php if (!empty($bigDeposits)): ?>
                                            <h1><?php echo __('Warning Deposits'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?php echo __('Id'); ?></th>
                                                    <th><?php echo __('Date'); ?></th>
                                                    <th><?php echo __('User'); ?></th>
                                                    <th><?php echo __('Amount'); ?></th>
                                                </tr>
                                                <?php foreach ($bigDeposits as $deposit): ?>
                                                    <tr>
                                                        <td><?php echo $deposit['Deposit']['id']; ?></td>
                                                        <td><?php echo $deposit['Deposit']['date']; ?></td>
                                                        <td>
                                                            <?php if($deposit['User']['username'] == null):?>
                                                                <?php echo __('User Terminated'); ?>
                                                            <?php else: ?>
                                                                <?php echo $deposit['User']['username']; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo $deposit['Deposit']['amount']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>

                                        <?php if (!empty($bigWithdraws)): ?>
                                            <h1><?php echo __('Warning Withdraws'); ?></h1>
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th><?php echo __('Id'); ?></th>
                                                    <th><?php echo __('Date'); ?></th>
                                                    <th><?php echo __('User'); ?></th>
                                                    <th><?php echo __('amount'); ?></th>
                                                </tr>
                                                <?php foreach ($bigWithdraws as $deposit): ?>
                                                    <tr>
                                                        <td><?php echo $deposit['Withdraw']['id']; ?></td>
                                                        <td><?php echo $deposit['Withdraw']['date']; ?></td>
                                                        <td><?php echo $deposit['User']['username']; ?></td>
                                                        <td><?php echo $deposit['Withdraw']['amount']  . ' ' . Configure::read('Settings.currency');; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        <?php else: ?>
                                        <?php endif; ?>
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
<style type="text/css">
    h1 { padding: 10px 0 10px 0; }
</style>