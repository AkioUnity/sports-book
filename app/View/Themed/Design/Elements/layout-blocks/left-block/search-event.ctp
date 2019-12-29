<div class="search-box">
    <?php echo $this->MyForm->create('Event', array('type' => 'get','url' => array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'search', 'action' => 'index'), 'id' => 'EventSearchForm')); ?>
        <?php echo $this->MyForm->input('name', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => __('Bet search by name / ID...', true), 'class' => 'srch-inp')); ?>
        <button type="submit" class="srch-btn"></button>
        <div class="clear"></div>
    <?php echo $this->MyForm->end(); ?>
</div>