<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_party extends discuz_table {

    public function __construct() {
        $this->_table = 'ygclub_party';
        $this->_pk = 'tid';

        parent::__construct();
    }

    // 查找相关召集帖
    public function fetch_by_ctid($tid) {
        return DB::fetch_first("SELECT tid FROM %t WHERE ctid=%d", array($this->_table, $tid));
    }

    // 查找相关总结帖
    public function fetch_ctid($tid) {
        return DB::fetch_first("SELECT ctid FROM %t WHERE tid=%d", array($this->_table, $tid));
    }
}

?>
