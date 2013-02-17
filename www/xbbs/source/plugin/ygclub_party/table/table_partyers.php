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

    public function fetch_all_for_thread($tid, $start = 0, $limit = 100, $uid = 0, $master = 0) {
        $verifiedsql = empty($master) ? ' AND verified=1' : '';
        $verifiedsql = '';
        if(intval($uid)) {
            $verifiedsql .= ' AND uid='.intval($uid);
        }
        return DB::fetch_all("SELECT * FROM %t WHERE tid=%d $verifiedsql ORDER BY dateline DESC".DB::limit($start, $limit), array($this->_table, $tid));
    }
}
?>
