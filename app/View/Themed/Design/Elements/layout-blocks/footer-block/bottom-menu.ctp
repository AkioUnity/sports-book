<ul class="list-fot">
    <?php foreach($this->Menu->getMenu('sidebar') AS $menuItem): ?>
        <li><?php echo $this->MyHtml->link($menuItem['Menu']['title'], array('language' => Configure::read('Config.language'), 'plugin' => null, 'controller' => $menuItem['Menu']['url']), array('aco' => false)); ?></li>
    <?php endforeach; ?>
</ul>