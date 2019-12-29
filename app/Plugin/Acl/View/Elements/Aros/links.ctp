<div id="aros_link" class="acl_links">
<?php
$selected = isset($selected) ? $selected : $this->params['action'];

$links = array();
$links[] = $this->MyHtml->link(__d('acl', 'Build missing AROs'), '/' . Configure::read('Config.language') . '/admin/acl/aros/check', array('aco' => false, 'class' => ($selected == 'admin_check' )? 'selected' : null));
$links[] = $this->MyHtml->link(__d('acl', 'Users roles'), '/' . Configure::read('Config.language') . '/admin/acl/aros/users', array('aco' => false, 'class' => ($selected == 'admin_users' )? 'selected' : null));

if(Configure :: read('acl.gui.roles_permissions.ajax') === true)
{
    $links[] = $this->MyHtml->link(__d('acl', 'Roles permissions'), '/' . Configure::read('Config.language') . '/admin/acl/aros/ajax_role_permissions', array('aco' => false, 'class' => ($selected == 'admin_role_permissions' || $selected == 'admin_ajax_role_permissions' )? 'selected' : null));
}
else
{
    $links[] = $this->MyHtml->link(__d('acl', 'Roles permissions'), '/' . Configure::read('Config.language') . '/admin/acl/aros/role_permissions', array('aco' => false, 'class' => ($selected == 'admin_role_permissions' || $selected == 'admin_ajax_role_permissions' )? 'selected' : null));
}
$links[] = $this->MyHtml->link(__d('acl', 'Users permissions'), '/' . Configure::read('Config.language') . '/admin/acl/aros/user_permissions', array('aco' => false, 'class' => ($selected == 'admin_user_permissions' )? 'selected' : null));

echo $this->Html->nestedList($links, array('class' => 'acl_links'));
?>
</div>