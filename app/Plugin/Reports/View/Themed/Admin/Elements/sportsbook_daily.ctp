<style type="text/css">
    .container {
        width: 755px;
        height: 975px;
        margin: 0 auto;
    }

    .container .header {
        display: block;
        padding: 15px 0 0 20px;
    }
    .container .header .logo {
        float: left;
        width: 295px;
        height: 65px;
    }

    .container .header h2 {
        margin-bottom: 5px;
    }

    .container .header .data {
        float: left;
        width: 440px;
        height: 65px;
        text-align: right;
        font: 11px 'Arial';
        margin-top: 15px;
    }

    .container .graphs {
        padding: 0 0 0 20px;
    }

    .container div .header span.border {
        padding: 0 0 0 20px;
        margin-bottom: 4px;
        display: block;
        border-bottom: 2px solid #000;
    }

    .container .header .title h1 {
        font: 20px 'Arial';
        text-align: center;
        font-weight: 100;
        margin-top: 22px;
    }

    .container .header .title h2 {
        font: 10px 'Arial';
        text-align: center;
    }

    .container .graphs .data {
        padding: 15px 0 0 20px;
    }

    .container .graphs .graph .deposits-charts {
        margin: -80px 0 0 250px;
    }

    .container .summary  {
        padding: 0 0 0 20px;
    }
    .container .summary .data table {
        margin: 15px auto;
        border: none;
    }


    .container .summary .data table.border tr td, th {
        width: 200px;
        text-align: center;
    }

    .container .totals h1 {
        text-align: center;
    }

    .container .graph {
        max-height: 270px;
    }

    .container .graph .chart-data-0,
    .container .graph .chart-data-2 {
        height: 280px;
        position: relative;
        top: 30px;
        margin: -200px 0 0 200px;
    }

    .container .graph .chart-data-3,
    .container .graph .chart-data-1 {
        position: relative;
        bottom: 30px;
        margin: -240px 0 0 -300px;
    }

    .container .graph .chart-data-3,
    .container .graph .chart-data-2 {
        height: 250px;
    }
    .container .graph .chart-data-0 {
        top: 15px;
    }
    .container .graph .chart-data-2 {
        top: -10px;
    }

    .container .graph .chart-title-0,
    .container .graph .chart-title-2 {
        position: absolute;
        margin-left: 320px;
        margin-top: 25px;
    }

    .container .graph .chart-title-3,
    .container .graph .chart-title-1 {
        position: absolute;
        margin-left: 620px;
        margin-top: 25px;
    }

    .clear { clear: both; }

</style>
<div class="container">
    <div class="header">
        <div class="logo">
            <?php echo $this->Html->image('/img/printing_logo.png', array('alt' => 'Daily Report')); ?>
        </div>
        <div class="data">
            <div><span><?php echo __('Requested at: %s', gmdate('Y-m-d H:i:s')); ?></span></div>
            <div><span><?php echo __('Requested by: %s', $user['username']); ?> </span></div>
        </div>
        <div class="clear"></div>
        <div class="title">
            <h1><?php echo __('Daily sportsbook report'); ?></h1>
            <h2><?php echo __('From %s to %s', gmdate('Y-m-d H:i', strtotime($interval[0])), gmdate('Y-m-d H:i', strtotime($interval[1]))); ?></h2>
        </div>
    </div>
    <div class="graphs">
        <div class="header">
            <h2><?php echo __('Online Finances'); ?></h2>
            <span class="border"></span>
            <span class="border"></span>
        </div>
        <div class="data">
            <div class="stats">
                <span class="title"><?php echo __('Today registered: %d new users', $registeredTotalCount); ?></span>
            </div>
            <br />
            <div class="stats">
                <span class="title"><?php echo __('Deposited:'); ?></span>
                <span class="title"><?php echo __('%d users', $depositFundStats[0]['count']); ?></span>
            </div>
            <div class="stats">
                <span class="title"><?php echo __('Withdrawed:'); ?></span>
                <span class="title"><?php echo __('%d users', $withdrawChargeStats[0]['count']); ?></span>
            </div>
            <br />
            <div class="stats">
                <span class="title"><?php echo __('Deposited amount:'); ?></span>
                <span class="title"><?php echo __('%.2f %s', (float) $depositFundStats[0]['total'], Configure::read('Settings.currency')); ?></span>
            </div>
            <div class="stats">
                <span class="title"><?php echo __('Withdrawed amount:'); ?></span>
                <span class="title"><?php echo __('%.2f %s', (float) $withdrawChargeStats[0]['total'], Configure::read('Settings.currency')); ?></span>
            </div>
        </div>
        <div class="graph">
            <?php echo $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $chartsData));?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="graphs">
        <div class="header">
            <h2><?php echo __('Online and offline tickets'); ?></h2>
            <span class="border"></span>
            <span class="border"></span>
        </div>
        <div class="data">
            <div class="stats">
                <span class="title"><?php echo __('Today placed: %d new tickets', $ticketsTotalCount); ?></span>
            </div>
            <br />
            <div class="stats">
                <span class="title"><?php echo __('Single type:'); ?></span>
                <span class="title"><?php echo __('%d tickets', $singleTicketsCount); ?></span>
            </div>
            <div class="stats">
                <span class="title"><?php echo __('Multi type:'); ?></span>
                <span class="title"><?php echo __('%d tickets', $multiTicketsCount); ?></span>
            </div>
            <br />
            <div class="stats">
                <span class="title"><?php echo __('Won status:'); ?></span>
                <span class="title"><?php echo __('%d tickets', $wonTicketsCount); ?></span>
            </div>
            <div class="stats">
                <span class="title"><?php echo __('Lost status:'); ?></span>
                <span class="title"><?php echo __('%d tickets', $lostTicketsCount); ?></span>
            </div>
            <div class="stats">
                <span class="title"><?php echo __('Pending status:'); ?></span>
                <span class="title"><?php echo __('%d tickets', $pendingTicketsCount); ?></span>
            </div>
            <div class="stats">
                <span class="title"><?php echo __('Cancelled status:'); ?></span>
                <span class="title"><?php echo __('%d tickets', $cancelledTicketsCount); ?></span>
            </div>
        </div>
        <div class="graph">
            <?php echo $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $chartsData1));?>
        </div>
    </div>
    <div class="summary">
        <div class="header">
            <h2>Summary</h2>
            <span class="border"></span>
            <span class="border"></span>
        </div>
        <div class="data">
            <table class="border">
                <thead>
                  <tr>
                      <th><?php echo __('Income'); ?></th>
                      <th><?php echo __('Outcome'); ?></th>
                  </tr>
                </thead>
                <tr>
                    <td><?php echo __('Deposited amount %.2f %s', (float) $depositFundStats[0]['total'], Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('Withdrawed amount %.2f %s', (float) $withdrawChargeStats[0]['total'], Configure::read('Settings.currency')); ?></td>
                </tr>
                <tr>
                    <td><?php echo __('Cashier profit %.2f %s', 0, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('Cashier loss %.2f %s', 0, Configure::read('Settings.currency')); ?></td>
                </tr>
                <tr>
                    <td><?php echo __('Operator profit %.2f %s', 0, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('Operator loss %.2f %s', 0, Configure::read('Settings.currency')); ?></td>
                </tr>
            </table>
            <table class="no-border">
                <tr>
                    <td><?php echo __('Total income:'); ?></td>
                    <td><?php echo __('%.2f %s', 0, Configure::read('Settings.currency')); ?></td>
                </tr>
                <tr>
                    <td><?php echo __('Total outcome:'); ?></td>
                    <td><?php echo __('%.2f %s', 0, Configure::read('Settings.currency')); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="totals">
        <h1><?php echo __('Total earnings: %.2f %s', 0, Configure::read('Settings.currency')); ?></h1>
    </div>
</div>