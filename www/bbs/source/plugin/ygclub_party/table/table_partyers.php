<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_partyers extends discuz_table {

    public function __construct() {
        $this->_table = 'ygclub_partyers';
        $this->_pk = 'pid';

        parent::__construct();
    }

    public function fetch_all_for_thread($tid, $start = 0, $limit = 100, $uid = 0, $verified = array()) {
        $verifiedsql = empty($verified) ? '' : " AND verified IN ("  . join(',', $verified) . ")";
        if(intval($uid)) {
            $verifiedsql .= ' AND uid='.intval($uid);
        }
        return DB::fetch_all("SELECT * FROM %t WHERE tid=%d $verifiedsql ORDER BY updatetime DESC, dateline DESC".DB::limit($start, $limit), array($this->_table, $tid));
    }

    public function fetch_by_uid_tid($uid, $tid) {
        return DB::fetch_first("SELECT * FROM %t WHERE uid=%d AND tid=%d", array($this->_table, $uid, $tid));
    }
}
?>
