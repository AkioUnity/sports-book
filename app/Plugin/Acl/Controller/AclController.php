<?php
/**
 *
 * @author   Nicolas Rod <nico@alaxos.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.alaxos.ch
 */

App::uses('AclAppController', 'Acl.Controller');

class AclController extends AclAppController
{
	public $name = 'Acl';

	public function index() {
	    $this->redirect('/admin/acl/aros');
	}
	
	public function admin_index($conditions = array(), $model = null) {
	    $this->redirect('/admin/acl/acos');
	}
}