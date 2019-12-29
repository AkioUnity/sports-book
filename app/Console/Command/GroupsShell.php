<?php
 /**
 * Groups Shell
 *
 * Handles Groups Shell Tasks
 *
 * @package    Sports
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2014 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */

App::uses('ComponentCollection', 'Controller');
App::uses('Controller', 'Controller');
App::uses('AclComponent', 'Controller/Component');

class GroupsShell extends Shell
{
    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Group');

    /**
     * ACL
     * AclComponent $Acl
     */
    private $Acl;

    /**
     * Initializes the Shell
     * acts as constructor for subclasses
     * allows configuration of tasks prior to shell execution
     *
     * @return void
     * @link http://book.cakephp.org/2.0/en/console-and-shells.html#Shell::initialize
     */
    public function initialize()
    {
        $collection = new ComponentCollection();
        $this->Acl  = new AclComponent($collection);
        $controller = new Controller();

        $this->Acl->initialize($controller);

        parent::initialize();
    }

    /**
     * Updates Leagues activity statuses
     *
     * @return void
     */
    public function recoverGroupAcl()
    {
        $this->out("Processing...\n");

        $aro = $this->Acl->Aro;

        // Groups :)
        foreach ($this->Group->find('all') AS $group)
        {
            $aro->create();

            $aro->save(array(
                'parent_id'     =>  null,
                'model'         =>  $this->Group->name,
                'foreign_key'   =>  $group["Group"]["id"],
                'alias'         =>  $group["Group"]["name"]
            ));

            $this->out(sprintf("Processing group: %s...\n", $group["Group"]["name"]));
        }

        // Users :)
        foreach ($this->Group->find('all') AS $group) {
            if (!empty($group["User"]))
            {
                foreach ($group["User"] AS $user)
                {
                    $aro->create();

                    $aro->save(array(
                        'parent_id'     =>  $group["Group"]["id"],
                        'model'         =>  $this->Group->User->name,
                        'foreign_key'   =>  $user["id"],
                        'alias'         =>  $user["username"]
                    ));

                    $this->out(sprintf("Processing user: %s...\n",  $user["username"]));
                }
            }
        }

        $this->out(sprintf("Trying to grant all rights to Administrator group..\n"));

        $role =& $this->{Configure :: read('acl.aro.role.model')};
        $role->id = Group::ADMINISTRATOR_GROUP;

        /*
         * Check if the Role exists in the ARO table
         */
        $node = $this->Acl->Aro->node($role);

        if(empty($node)) {
            $this->out(sprintf("Cant  grant all rights to group, group does not exist..\n"));
        } else {
            $this->Acl->allow($role, 'controllers');
            $this->out(sprintf("Grant to group success..\n"));
        }

        $this->out("Complete\n");
    }
}