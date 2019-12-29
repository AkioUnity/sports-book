<?php

App::uses('ReportsAppModel', 'Reports.Model');

class Report extends ReportsAppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Report';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable bool
     */
    public $useTable = false;

    /**
     * Fake Report
     *
     * @return array
     */
    public function getReport() {
        return array();
    }
}