<?php

App::uses('PokerAppController', 'Poker.Controller');

class PokerTablesController extends PokerAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'PokerTables';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Poker.PokerTable');

    public function admin_tables()
    {
        $this->Paginator->settings  =   array($this->PokerTable->name => array(
            'fields' => array(
                'PokerTable.gameID',
                'PokerTable.tablename',
                'PokerTable.tabletype',
                'PokerTable.tablelow',
                'PokerTable.tablelimit'
            ),
            'limit' => Configure::read('Settings.itemsPerPage')
        ));

        $data    =   $this->Paginator->paginate( $this->PokerTable->name );
        $this->set('data', $data);
        $this->set('model', 'PokerTable');
        $this->set('tabs', $this->PokerTable->getTabs($this->request->params));
    }

    public function admin_create()
    {
        $data = $this->request->data["PokerTable"];
        if (!empty($data)) {
            if (!empty($data["name"]) && !empty($data["type"]) && !empty($data["max"])) {
                $data["min"] = intval($data["min"])*10;
                $data["max"] = intval($data["max"])*10;
                if ($this->PokerTable->insertTable($data["name"], $data["type"], $data["min"], $data["max"])) {
                    $this->Admin->setMessage(__('Table created successfully.', true), 'success');
                } else {
                    $this->Admin->setMessage(__('Can\'t create table.', true), 'error');
                }
            } else {
                $this->Admin->setMessage(__('Can\'t create table.', true), 'error');
            }
        }

        $this->set('tabs', $this->PokerTable->getTabs($this->request->params));
    }

    public function admin_delete($gameID)
    {
        if ($this->PokerTable->delete($gameID))
        {
            $this->Admin->setMessage(__('Item deleted', true), 'success');
        } else {
            $this->Admin->setMessage(__('This cannot be deleted.', true), 'error');
        }

        $this->redirect($this->referer(array('action' => 'admin_tables')));
    }
}