<div class="blue-box">
    <div class="blue-in">
        <h5><i class="header-icon icon-faqw"></i> <?php echo __("Place ticket"); ?></h5>
        <div class="white-in">

            <div class="tgl tgl-first on-top"></div>

            <?php if(isset($ticket)): ?>
                <div class="tgl-content table-cnt open-cell">
                    <div>
                        <?php echo __("Ticket is placed"); ?>
                    </div>

                    <?php echo $this->MyHtml->link(__('Back'), array('plugin' => false, 'action' => 'index'), array('class' => 'btn-blue')); ?>
                    <?php if (Configure::read('Settings.printing')): ?>
                        <?php echo $this->MyHtml->link(__('Print', true), array('plugin' => false, 'action' => 'printTicket', $ticket['Ticket']['id'] . '.pdf'), array('class' => 'btn-silver', 'target' => '_blank', 'style' => 'margin-left: 5px;')); ?>
                    <?php endif; ?>
                </div>



            <?php else: ?>
                <?php echo  __("Ticket not found."); ?>
            <?php endif; ?>
        </div>
    </div>
</div>