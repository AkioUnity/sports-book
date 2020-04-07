<!--layout-slots/right-slot.ctp-->
<div class="box mobile-view">
    <?php  echo $this->element('layout-blocks/left-block/search-event'); ?>
</div>
<!-- Bet Slip Block -->
<?php if($this->Ticket->isAllowedToPlaceTicket() && $this->MyHtml->checkAcl(array('controller' => 'Tickets', 'action' => 'getBets'))) : ?>
    <img src="<?=$this->Html->url('/theme/Design/img/loader.gif');?>" alt="" class="roll betslip" />
    <div id="bet-slip-container" class="box" style="position: relative;">
        <div class="loading"></div>
        <div id="bet-slip-container-html">
            <?php echo $this->element('layout-blocks/right-block/bet-slip'); ?>
        </div>
    </div>
<?php endif; ?>

<?php if (Configure::read('Settings.reservation_ticket_mode') && $this->MyHtml->checkAcl(array('controller' => 'ReservationTickets', 'action' => 'search'))): ?>
<div style="margin-top: 15px;" class="box">
    <?php echo $this->element('layout-blocks/right-block/search-ticket'); ?>
</div>
<?php endif; ?>
<!-- END Bet Slip Block -->