<?php if($this->MyHtml->checkAcl(array('language' => Configure::read('Config.language'), 'plugin' => 'events', 'controller' => 'search', 'action' => 'index'))): ?>
<!-- Event Search Block -->
<div class="box">
    <?php  echo $this->element('layout-blocks/left-block/search-event'); ?>
</div>
<!-- END Event Search Block -->
<?php endif; ?>
<!-- Sports Menu Block -->
<div id="sports-menu" class="box">
    <?php echo $this->element('layout-blocks/left-block/sports-menu'); ?>
</div>
<!-- END Sports Menu  Block-->