<div class="search-box">
    <h2 class="ico-slip"><?php echo __("Reservation Ticket");?></h2>
    <?php echo $this->MyForm->create('ReservationTickets', array('type' => 'get','url' => array('language' => Configure::read('Config.language'), 'plugin' => false, 'controller' => 'ReservationTickets', 'action' => 'search'), 'id' => 'TicketSearchForm')); ?>
    <?php echo $this->MyForm->input('id', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => __('Ticket ID', true), 'class' => 'srch-inp')); ?>
    <button type="submit" class="srch-btn"></button>
    <div class="clear"></div>
    <?php echo $this->MyForm->end(); ?>
</div>