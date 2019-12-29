<div id="acos_link" class="acl_links">
<?php
$selected = isset($selected) ? $selected : $this->params['action'];

$links = array();
$links[] = $this->MyHtml->link(__d('acl', 'Synchronize actions ACOs'), '/' . Configure::read('Config.language') . '/admin/acl/acos/synchronize', array('aco' => false, array('confirm' => __d('acl', 'are you sure ?')), 'class' => ($selected == 'admin_synchronize' ) ? 'selected' : null));
$links[] = $this->MyHtml->link(__d('acl', 'Clear actions ACOs'),       '/' . Configure::read('Config.language') . '/admin/acl/acos/empty_acos',  array('aco' => false, array('confirm' => __d('acl', 'are you sure ?')), 'class' => ($selected == 'admin_empty_acos' )  ? 'selected' : null));
$links[] = $this->MyHtml->link(__d('acl', 'Build actions ACOs'),       '/' . Configure::read('Config.language') . '/admin/acl/acos/build_acl',                                                     array('class' => ($selected == 'admin_build_acl' )   ? 'selected' : null));
$links[] = $this->MyHtml->link(__d('acl', 'Prune actions ACOs'),       '/' . Configure::read('Config.language') . '/admin/acl/acos/prune_acos',  array('aco' => false, array('confirm' => __d('acl', 'are you sure ?')), 'class' => ($selected == 'admin_prune_acos' )  ? 'selected' : null));


echo $this->Html->nestedList($links, array('class' => 'acl_links'));
?>
</div>