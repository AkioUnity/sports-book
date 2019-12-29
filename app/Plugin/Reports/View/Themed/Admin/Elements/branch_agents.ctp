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
            <h1><?php echo __('Agents Branch report'); ?></h1>
            <h2><?php echo __('From %s to %s', gmdate('Y-m-d H:i', strtotime($interval[0])), gmdate('Y-m-d H:i', strtotime($interval[1]))); ?></h2>
        </div>
    </div>
    <?php $Income = 0; ?>
    <?php $Outcome = 0; ?>
    <?php $PendingIncome = 0; ?>
    <?php $PendingOutcome = 0; ?>
    <?php foreach($agents AS $agent): ?>
        <div class="graphs">
            <div class="header">
                <h2><?php echo __('%s', $agent["User"]["username"]); ?></h2>
                <span class="border"></span>
                <span class="border"></span>
            </div>
            <div class="data">
                <table class="border">
                    <thead>
                    <tr>
                        <td><?php echo __('ID'); ?></td>
                        <td><?php echo __('Income'); ?></td>
                        <td><?php echo __('Outcome'); ?></td>
                        <td><?php echo __('Pending Income'); ?></td>
                        <td><?php echo __('Pending Outcome'); ?></td>
                        <td><?php echo __('-'); ?></td>
                        <td><?php echo __('-'); ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo __('%d', $agent["User"]["id"]); ?></td>
                        <td><?php echo __('%.2f %s', $agent["User"]["Income"], Configure::read('Settings.currency')); ?></td>
                        <td><?php echo __('%.2f %s', $agent["User"]["Outcome"], Configure::read('Settings.currency')); ?></td>
                        <td><?php echo __('%.2f %s', $agent["User"]["PendingIncome"], Configure::read('Settings.currency')); ?></td>
                        <td><?php echo __('%.2f %s', $agent["User"]["PendingOutcome"], Configure::read('Settings.currency')); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                    <?php $Income += $agent["User"]["Income"]; ?>
                    <?php $Outcome += $agent["User"]["Outcome"]; ?>
                    <?php $PendingIncome += $agent["User"]["PendingIncome"]; ?>
                    <?php $PendingOutcome += $agent["User"]["PendingOutcome"]; ?>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
    <br />
    <br />
    <div class="clear"></div>
    <div class="summary">
        <div class="header">
            <h2><?php echo __('Finances'); ?></h2>
            <span class="border"></span>
            <span class="border"></span>
        </div>
        <div class="data">
            <table class="border">
                <thead>
                  <tr>
                      <th><?php echo __('Total Income'); ?></th>
                      <th></th>
                      <th></th>
                      <th><?php echo __('Total Outcome'); ?></th>
                  </tr>
                </thead>
                <tr>
                    <td><?php echo __('%.2f %s', (float) $Income, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('+%s %.2f %s', 'Pending Income', (float) $PendingIncome, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('-%s %.2f %s', 'Pending Outcome', (float) $PendingOutcome, Configure::read('Settings.currency')); ?></td>
                    <td><?php echo __('%.2f %s', (float) $Outcome, Configure::read('Settings.currency')); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="totals">
        <h1><?php echo __('Total earnings %.2f %s', (float) ($Income - $Outcome), Configure::read('Settings.currency')); ?></h1>
    </div>
</div>