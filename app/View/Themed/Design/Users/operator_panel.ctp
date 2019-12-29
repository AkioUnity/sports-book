<?php // TODO: ?>
<!-- Header START -->
<div id="header">
    <div class="header-top">

        <a href="/" class="home left"></a>

        <p id="real-time-clock" class="left">
            <?php echo $this->element('layout-blocks/header-block/header-clock'); ?>
        </p>

        <!-- TOP menu START -->
        <ul class="right">
            <li></li>
            <li></li>
            <li></li>
        </ul>

        <!-- TOP menu END -->
        <div class="clear"></div>
    </div>
    <div class="header-bottom">
        <!-- LOGO START -->
        <a href="/">
            <?php echo $this->Html->image('logo.png', array('class' => 'logo left', 'alt' => 'ChalkPro')); ?>
        </a>
        <!-- LOGO END -->

        <!-- Info Box START -->
        <div class="operator-box">
            <div class="lhold1">
                <span><?php echo __('Username'); ?>:</span>
                <span class="user-data">
                    <span class="username"><?php echo CakeSession::read('Auth.User.username'); ?></span>
                </span>
            </div>
            <div class="lhold1">
                <span><?php echo __('Current balance'); ?>:</span>
                <span class="current-balance">
                    <span class="currency"><?php echo __('%s', Configure::read('Settings.currency')); ?></span>
                    <span class="value"><?php echo __("%s", number_format((float)CakeSession::read('Auth.User.balance'), intval(Configure::read('Settings.balance_decimal_places')), '.', '')); ?></span>
                </span>
            </div>
            <div class="lhold1">
                <span><?php echo __('Today placed tickets'); ?>:</span>
                <span class="today-tickets"><?php echo $todayTickets; ?></span>
            </div>
        </div>
        <!-- Info Box END -->

        <div class="clear"></div>
    </div>
</div>
<!-- Header END'-->

<!-- ContentSTART -->
<div id="content">

    <!-- Left side START -->
    <div class="side-left">
        <!-- Find Event Block -->
        <div class="side-box">
            <?php echo $this->element('layout-blocks/right-block/find-event');  ?>
        </div>
        <!-- END Find Event Block -->

        <!-- Find Ticket Block -->
        <div class="side-box">
            <?php echo $this->element('layout-blocks/left-block/search-ticket');  ?>
        </div>
        <!-- END Find Ticket Block -->

    </div>
    <!-- Left side END -->

    <!-- Main CONTENT START -->
    <div class="main-content">
        <!-- Page Content START -->
        <div class="mid-cent">
            <h3><?php echo __('Activity Column'); ?></h3>
            <div id="operator-panel-content" class="cent-txt txt-pad">
                <!-- Nothin' here :P -->
            </div>
        </div>
        <!-- Page Content END -->
        <div class="clear"></div>
    </div>
    <!-- Main CONTENT END -->

    <!-- Right side START -->
    <div class="side-right">
        <?php if($this->Ticket->isAllowedToPlaceTicket() && $this->MyHtml->checkAcl(array('controller' => 'Tickets', 'action' => 'getBets'))) : ?>
        <!-- Bet Slip Block -->
        <div id="bet-slip-container" class="side-box rel">
            <?php echo $this->element('layout-blocks/right-block/bet-slip'); ?>
        </div>
        <?php endif; ?>
        <div id="tickets-history-container" class="side-box rel">
            <?php echo $this->element('layout-blocks/right-block/tickets-history'); ?>
        </div>
        <!-- END Bet Slip Block -->
    </div>
    <!-- Right side END -->

    <div class="clear"></div>
</div>
<!-- Content END-->

<!-- Footer START -->
<div id="footer">

</div>

<!-- Footer END -->

<!-- Javascript -->
<?php echo $this->Html->script(array('jquery.tools.js', 'BetSlip.js', 'Events.js', 'Ticket.js')); ?>
<script type="text/javascript">
    $(document).ready(function(){
        Ticket.setUrl('<?php echo Router::url( array('plugin' => null, 'controller' => 'tickets', 'action' => null), true ); ?>');
        Ticket.ticketsHistory();
        Ticket.setAjax();
        BetSlip.loadBetSlip('<?php echo Router::url( array('plugin' => null, 'controller' => 'tickets', 'action' => 'getBets', 'ajax'), true ); ?>');
        Ticket.searchTicket('<?php echo Router::url( array('plugin' => null, 'controller' => 'tickets', 'action' => 'getTicket', 'ext' => 'json'), true ); ?>');
        Ticket.payForTicket('<?php echo Router::url( array('plugin' => null, 'controller' => 'tickets', 'action' => 'payForTicket', 'ext' => 'json'), true ); ?>');
        Ticket.placeTicket('<?php echo Router::url( array('plugin' => null, 'controller' => 'tickets', 'action' => 'place', 'ext' => 'json'), true ); ?>');
        Events.searchEvent('<?php echo Router::url( array('plugin' => null, 'controller' => 'events', 'action' => 'search'), true ); ?>', 'example');
    });
</script>