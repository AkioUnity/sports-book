<style type="text/css">
    .container {
        width: 755px;
        height: 975px;
        /*background: url(/img/cashier_daily.jpg);*/
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

    .container .header .data div {
        line-height: 20px;
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
        /*display: block;*/
        /*float: left;*/
        padding: 15px 0 0 20px;
    }

    .container .graphs .graph .chart-title {
        display: none;
    }

    .container .graphs .graph .deposits-charts {
        margin: -80px 0 0 250px;
    }

    .container .summary .data table {
        margin: 15px auto;
        border: none;
    }


    .container .summary .data table.border tr {

    }

    .container .summary .data table.border tr td, th {
        width: 200px;
        padding: 15px;
        text-align: center;
    }

    .container .totals h1 {
        text-align: center;
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
            <h1><?php echo __('Daily report of cashier %s', $user['username']); ?></h1>
            <h2><?php echo __('From %s to %s', gmdate('Y-m-d H:i', strtotime($interval[0])), gmdate('Y-m-d H:i', strtotime($interval[1]))); ?></h2>
        </div>
    </div>
    <div class="graphs">
        <div class="header">
            <h2><?php echo __('Finances'); ?></h2>
            <span class="border"></span>
            <span class="border"></span>
        </div>
        <div class="data">
            <div class="stats">
                <span class="title"><?php echo __('Statistics'); ?></span>
            </div>
            <br />
            <div class="stats">
                <span class="title"><?php echo __('Today funded:'); ?></span>
                <span class="title"><?php echo __('%d users', $depositFundStats[0]['count']); ?> </span>
            </div>
            <div class="stats">
                <span class="title"><?php echo __('Today charged:'); ?></span>
                <span class="title"><?php echo __('%d users', $withdrawChargeStats[0]['count']); ?></span>
            </div>
            <br />
            <div class="stats">
                <span class="title"><?php echo __('Amount of total funds:'); ?></span>
                <span class="title"><?php echo __('%.2f %s', (float) $depositFundStats[0]['total'], Configure::read('Settings.currency')); ?></span>
            </div>
            <div class="stats">
                <span class="title"><?php echo __('Amount of total charges:'); ?></span>
                <span class="title"><?php echo __('%.2f %s', (float) $withdrawChargeStats[0]['total'], Configure::read('Settings.currency')); ?></span>
            </div>
        </div>
        <div class="graph">
            <?php echo $this->element('charts/pie', array('placeholderClass' => 'deposits-charts', 'chartsData' => $chartsData));?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="summary">
        <div class="header">
            <h2><?php echo __('Summary'); ?></h2>
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
                    <td><?php echo __('Funded: %.2f %s', (float) $depositFundStats[0]['total'], Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('Charged: %.2f %s', (float) $withdrawChargeStats[0]['total'], Configure::read('Settings.currency')); ?></td>
                </tr>
            </table>
            <table class="no-border">
                <tr>
                    <td><?php echo __('Total income:'); ?></td>
                    <td><?php echo __('%.2f %s', (float) $depositFundStats[0]['total'], Configure::read('Settings.currency')); ?></td>
                </tr>
                <tr>
                    <td><?php echo __('Total outcome:'); ?></td>
                    <td><?php echo __('%.2f %s', (float) $withdrawChargeStats[0]['total'], Configure::read('Settings.currency')); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <br />
    <div class="totals">
        <h1><?php echo __('Total earnings: %.2f %s', (float) ($depositFundStats[0]['total']) - abs($withdrawChargeStats[0]['total']), Configure::read('Settings.currency')); ?></h1>
    </div>
</div>