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

    .container .summary .data {
        padding: 15px 0 0 20px;
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
<!--staff.ctp-->
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
            <h1><?php echo __('Staff report'); ?></h1>
            <h2><?php echo __('From %s to %s', gmdate('Y-m-d H:i', strtotime($interval[0])), gmdate('Y-m-d H:i', strtotime($interval[1]))); ?></h2>
        </div>
    </div>
    <div class="graphs">
        <div class="header">
            <h2><?php echo __('Staff list'); ?></h2>
            <span class="border"></span>
            <span class="border"></span>
        </div>
        <div class="data">
            <table class="border">
                <thead>
                <tr>
                    <td><?php echo __('Nr'); ?></td>
                    <td><?php echo __('Username'); ?></td>
                    <td><?php echo __('Group'); ?></td>
                    <td><?php echo __('Income'); ?></td>
                    <td><?php echo __('Outcome'); ?></td>
                    <td><?php echo __('Balance'); ?></td>
                    <td><?php echo __('Pending'); ?></td>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($Staffs) AND is_array($Staffs) AND !empty($Staffs)): ?>
                <?php foreach($Staffs AS $index => $Staff): ?>
                <tr>
                    <td><?php echo __('%d', $index+1); ?></td>
                    <td><?php echo __('%s', $Staff['User']['username']); ?></td>
                    <td><?php echo __('%s', $Staff['Group']['name']); ?></td>
                    <td><?php echo __('%.2f %s', $Staff['Stats']['income'], Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('%.2f %s', $Staff['Stats']['outcome'], Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('%.2f %s', $Staff['Stats']['balance'], Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('%.2f %s / %.2f %s', $Staff['Stats']['pending'][0], Configure::read('Settings.currency'), $Staff['Stats']['pending'][1], Configure::read('Settings.currency')); ?></td>
                </tr>
                </tbody>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td style="text-align: center;" colspan="7"><?php echo __('No data is available'); ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>

    </div>
    <div class="clear"></div>
    <div class="summary">
        <div class="header">
            <h2><?php echo __('Finances'); ?></h2>
            <span class="border"></span>
            <span class="border"></span>
        </div>
        <div class="data">
            <table class="border">
                <tr>
                    <td><?php echo __('Staff income: %.2f %s', (float) $StaffIncome, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('Staff outcome: %.2f %s', (float) $StaffOutcome, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('Pending potential income: +%.2f %s', (float) $PendingIncome, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('Pending potential outcome: -%.2f %s', (float) $PendingOutcome, Configure::read('Settings.currency')); ?></td>
                </tr>
            </table>
            <table class="no-border">
                <thead>
                <tr>
                    <th><?php echo __('Factual Income: %.2f %s', (float) $StaffIncome, Configure::read('Settings.currency')); ?></th>
                    <th><?php echo __('Factual Outcome: %.2f %s', (float) $StaffOutcome, Configure::read('Settings.currency')); ?></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="totals">
        <h1><?php echo __('Total earnings %.2f %s', (float) ($StaffIncome - $StaffOutcome), Configure::read('Settings.currency')); ?></h1>
    </div>
</div>