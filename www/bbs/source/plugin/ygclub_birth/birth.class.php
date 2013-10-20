<?php

/** 
 *   [ygclub.org] (C)北京LEAD阳光志愿者俱乐部
 *
 *   @author leeyupeng
 *   @email  leeyupeng@gmail.com
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_ygclub_birth {

    public function __construct() {
        $this->plugin_ygclub_birth();
    }

	function plugin_ygclub_birth() {
	}
}

class plugin_ygclub_birth_forum extends plugin_ygclub_birth {

    function index_middle() {
        global $_G;
        include_once template('ygclub_birth:birth_member_list');

	    $birthmonth = date("m");
        $birthday   = date("d");
        $member_list = array();
        $member_list_string = '';

	    $sql = "SELECT `" . DB::table('common_member') . "`.`uid` , `" . DB::table('common_member')."`.`username` , `".DB::table('common_member_profile')."`.`uid` FROM `".DB::table('common_member')."` LEFT JOIN `".DB::table('common_member_profile')."` ON `".DB::table('common_member')."`.`uid` = `".DB::table('common_member_profile') . "`.uid WHERE birthmonth='$birthmonth' and birthday=" . $birthday;
        $member_list = DB::fetch_all($sql);
        foreach($member_list as $member)
        {
		    $member_list_string .= '<a href="home.php?mod=spacecp&ac=pm&op=showmsg&handlekey=showmsg_2&touid='.$member['uid'].'&pmid=0&daterange=2">'.$member['username'].'</a>';
		    $member_list_string .= ' &nbsp; &nbsp; &nbsp; ';
        }
        
        $forum_id = 10;
        $forum_moderator = C::t('forum_moderator')->fetch_all_by_fid($forum_id, false);
        $tomorrow_member_list_string = '';
        if($forum_moderator[$_G['uid']])
        {
            $tomorrow_birthmonth = date("m", strtotime("+1 day"));
            $tomorrow_birthday = date("d", strtotime("+1 day"));
            $tomorrow_member_list = array();
            $tomorrow_member_list_string = '';
	        $sql = "SELECT `" . DB::table('common_member') . "`.`uid` , `" . DB::table('common_member')."`.`username` , `".DB::table('common_member_profile')."`.`uid` FROM `".DB::table('common_member')."` LEFT JOIN `".DB::table('common_member_profile')."` ON `".DB::table('common_member')."`.`uid` = `".DB::table('common_member_profile') . "`.uid WHERE birthmonth='$tomorrow_birthmonth' and birthday=" . $tomorrow_birthday;
            $tomorrow_member_list = DB::fetch_all($sql);

            foreach($tomorrow_member_list as $member)
            {
		        $tomorrow_member_list_string .= '<a href="home.php?mod=spacecp&ac=pm&op=showmsg&handlekey=showmsg_2&touid='.$member['uid'].'&pmid=0&daterange=2">'.$member['username'].'</a>';
		        $tomorrow_member_list_string .= ' &nbsp; &nbsp; &nbsp; ';
            }
        }
        return tpl_birth_member_list();
	}
}
