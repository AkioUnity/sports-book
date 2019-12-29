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

    table {
        margin: 15px auto;
        border: none;
    }

    table thead {
        font-weight: bold;;
    }

    table.border {
        width: 100%;
        /*border: 1px solid #000000;*/
    }


    table.border tr td {
        border-bottom: 1px solid #000000;
    }


    .container .summary .data table.border tr td, th {
        width: 200px;
        padding: 15px;
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
            <h1><?php echo __('Admin financial report'); ?></h1>
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
            <table class="border">
                <thead>
                <tr>
                    <td><?php echo __('ID'); ?></td>
                    <td><?php echo __('User'); ?></td>
                    <td><?php echo __('Group'); ?></td>
                    <td><?php echo __('Funded'); ?></td>
                    <td><?php echo __('Charged'); ?></td>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($Finances) AND is_array($Finances) AND !empty($Finances)): ?>
                <?php foreach($Finances AS $Finance): ?>
                <tr>
                    <td><?php echo $Finance['User']['id']; ?></td>
                    <td><?php echo $Finance['User']['username']; ?></td>
                    <td><?php echo $Finance['Group']['name']; ?></td>
                    <td><?php echo __('%.2f %s', (float) $Finance['Deposit']['total'], Configure::read('Settings.currency')) ?></td>
                    <td><?php echo __('%.2f %s', (float) $Finance['Withdraw']['total'], Configure::read('Settings.currency')) ?></td>
                </tr>
                </tbody>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td style="text-align: center;" colspan="5"><?php echo __('No data is available'); ?></td>
                </tr>
                <?php endif; ?>
            </table>
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
                      <th><?php echo __('Total Income'); ?></th>
                      <th><?php echo __('Total Outcome'); ?></th>
                  </tr>
                </thead>
                <tr>
                    <td><?php echo __('%.2f %s', (float) $totalIncome, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('%.2f %s', (float) $totalOutcome, Configure::read('Settings.currency')); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="totals">
        <h1><?php echo __('Total earnings %.2f %s', (float) ($totalIncome - $totalOutcome), Configure::read('Settings.currency')); ?></h1>
    </div>
</div>