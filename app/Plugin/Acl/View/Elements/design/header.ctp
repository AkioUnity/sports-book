<?php
echo $this->Html->css('/acl/css/acl.css');
?>
<div id="plugin_acl">
	
	<?php
	echo $this->Session->flash('plugin_acl');
	?>
	
	<h1><?php echo __d('acl', 'ACL plugin'); ?></h1>
	
	<?php

	if(!isset($no_acl_links))
	{
	    $selected = isset($selected) ? $selected : $this->params['controller'];
    
        $links = array();
        $links[] = $this->MyHtml->link(__d('acl', 'Permissions'), '/' . Configure::read('Config.language') . '/admin/acl/aros/index', array('aco' => false, 'class' => ($selected == 'aros' )? 'selected' : null));
        $links[] = $this->MyHtml->link(__d('acl', 'Actions'), '/' . Configure::read('Config.language') . '/admin/acl/acos/index', array('aco' => false, 'class' => ($selected == 'acos' )? 'selected' : null));
        
        echo $this->Html->nestedList($links, array('class' => 'acl_links'));
	}
	?>