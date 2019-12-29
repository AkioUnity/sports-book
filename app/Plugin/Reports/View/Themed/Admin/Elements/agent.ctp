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
            <h1><?php echo __('Agent report'); ?></h1>
            <h2><?php echo __('From %s to %s', gmdate('Y-m-d H:i', strtotime($interval[0])), gmdate('Y-m-d H:i', strtotime($interval[1]))); ?></h2>
        </div>
    </div>
    <div class="graphs">
        <div class="header">
            <h2><?php echo __('Users list'); ?></h2>
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
                    <td><?php echo __('Commissions'); ?></td>
                    <td><?php echo __('Income'); ?></td>
                    <td><?php echo __('Outcome'); ?></td>

                </tr>
                </thead>
                <tbody>
                <?php $commissions = 0; ?>
                <?php if(isset($users2) AND is_array($users2) AND !empty($users2)): ?>
                <?php foreach($users2 AS $index => $user): ?>
                <tr>
                    <td><?php echo __('%d', $index+1); ?></td>
                    <td><?php echo __('%s', $user['User']['username']); ?></td>
                    <td><?php echo __('%s', 'user'); ?></td>

                    <?php if($user['User']['balance'] > 0): ?>
                        <?php $commission = 0; ?>
                    <?php else: ?>
                        <?php  $commission = (float) abs($this->Utils->percentage(($user['User']['Deposit']['total'] - $user['User']['Withdraw']['total']), Configure::read('Settings.commission'))); ;?>
                    <?php endif; ?>
                    <td><?php echo __('%s %s  ( %s %% )', $commission,  Configure::read('Settings.currency'), Configure::read('Settings.commission'), '%'); ?></td>
                    <td><?php echo __('%.2f %s', $user['User']['Deposit']['total'], Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('%.2f %s', $user['User']['Withdraw']['total'], Configure::read('Settings.currency')); ?></td>
                    <?php $commissions += $commission; ?>
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
                    <td><?php echo __('Total income: %.2f %s', (float) $totalIncome, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('Total outcome: %.2f %s', (float) $totalOutcome, Configure::read('Settings.currency')); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="totals">
        <h1><?php echo __('Total earnings %.2f %s', (float) ($totalIncome - $totalOutcome), Configure::read('Settings.currency')); ?></h1>
    </div>
    <div class="totals">
        <h1><?php echo __('Total commissions %.2f %s', (float) $commissions, Configure::read('Settings.currency')); ?></h1>
    </div>
</div>