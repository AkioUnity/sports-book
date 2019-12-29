<h3><?php echo __("Bet Information"); ?></h3>
<ul class="links">
    <?php foreach($this->Menu->getMenu('sidebar') AS $menuItem): ?>
        <li><?php echo $this->MyHtml->link($menuItem['Menu']['title'], array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => $menuItem['Menu']['url']), array('aco' => false)); ?></li>
    <?php endforeach; ?>
</ul>