<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_party extends discuz_table {

    public function __construct() {
        $this->_table = 'party';
        $this->_pk = 'tid';

        parent::__construct();
    }
}

?>
