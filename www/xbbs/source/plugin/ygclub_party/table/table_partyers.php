<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_partyers extends discuz_table {

    public function __construct() {
        $this->_table = 'partyers';
        $this->_pk = 'pid';

        parent::__construct();
    }
}

?>
