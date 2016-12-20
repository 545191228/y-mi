<?php
namespace DB\Orm;

use DB;

class Orm {
    private $db_info;
    private $_route;
    function __construct($route)
    {
        $this->_route = $route;
    }

    public function analysisDbTable(){

    }
}