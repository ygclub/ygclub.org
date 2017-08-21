<?php
/**
 * Created by PhpStorm.
 * User: yangzhiguo
 * Date: 15/9/3
 * Time: 17:31
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$page = max(1, intval(getgpc('page')));
$lpp   = 30;
$start_limit = ($page - 1) * $lpp;
$url = "plugins&operation=config&do=$pluginid&identifier=xigua_login&pmod=user&page=$page";

if(submitcheck('acter') ){
    $uid = intval($_GET['actid']);
    $username = DB::result_first('SELECT username FROM %t WHERE uid=%d', array('common_member', $uid));

    switch ($_GET['acter']){
        case 'unbindanddel':

            include_once libfile('function/delete');
            $numdeleted = deletemember(array($uid), 0);
            loaducenter();
            uc_user_delete(array($uid));
            cpmsg(sprintf(lang('plugin/xigua_login', 'delete_succeed1'), 'UID '.$uid.' '.$username) , "action=$url", 'succeed');

            break;
        case 'unbinduser':
            if(DB::query("DELETE FROM %t WHERE uid=%d LIMIT 1", array('common_member_wechat', $uid))){
                cpmsg(sprintf(lang('plugin/xigua_login', 'delete_succeed2'), 'UID '.$uid.' '.$username), "action=$url", 'succeed');
            }
            break;
    }
}

if(submitcheck('searchtxt')){
    $txt = stripsearchkey($_GET['searchtxt']);
    if(is_numeric($txt)){
        $user = DB::fetch_all('SELECT * FROM %t WHERE uid IN (%n)', array('common_member', array($txt)), 'uid');
    }else{
        $user = DB::fetch_all("SELECT * FROM %t WHERE username LIKE %s", array('common_member', '%'.$txt.'%'), 'uid');
    }
    foreach ($user as $v) {
        if ($v['uid']) {
            $uids[$v['uid']] = $v['uid'];
        }
    }

    $list  = DB::fetch_all('SELECT * FROM %t WHERE uid IN (%n)', array('common_member_wechat', $uids), 'uid');

    $count = count($user);
}
else
{
    $list  = DB::fetch_all("SELECT * FROM %t ORDER BY uid DESC " . DB::limit($start_limit, $lpp), array('common_member_wechat'));
    foreach ($list as $v) {
        if ($v['uid']) {
            $uids[$v['uid']] = $v['uid'];
        }
    }
    $user = DB::fetch_all('SELECT * FROM %t WHERE uid IN (%n)', array('common_member', $uids), 'uid');

    $count = DB::result_first('SELECT count(*) as c FROM '.DB::table('common_member_wechat'));
}

$multipage = multi($count, $lpp, $page, ADMINSCRIPT."?action=$url");

showformheader($url,'','vlist');

echo '<div><input type="text" id="searchtxt" name="searchtxt" value="'.$txt.'" class="txt" /> <input type="submit" class="btn" value="'.cplang('search').'" /></div>';
showtableheader(lang('home/template', 'member_manage'));

$rowhead = array(
    'openid',
    'UID',
    cplang('username'),
    cplang('forums_edit_perm_formula_regdate'),
);

showtablerow('class="header"', array(), $rowhead);
$lang_unbind = lang('plugin/xigua_login', 'unbind');
$lang_unbindandel = lang('plugin/xigua_login', 'unbindanddel');
$confirm1 = lang('plugin/xigua_login', 'confirm1');
$confirm2 = lang('plugin/xigua_login', 'confirm2');

foreach ($list as $row) {
    $uid = $row['uid'];
    $username = $user[$uid]['username'];

    $rowbody = array(
        $row['openid'],
        $uid,
        "<img src='".avatar($uid, 'small', true)."' style='vertical-align:middle;height:20px;width:20px;' /> $username",
        date('Y-m-d H:i:s', $user[$uid]['regdate']),
        "<a onclick=\"return unbinduser($uid, '$username');\" class=\"btn\">$lang_unbind</a>"
    );
    showtablerow('', array(), $rowbody);
}

showtablerow('', 'colspan="99"', $multipage.'<input type="hidden" name="actid" id="actid" value="0" /><input type="hidden" name="acter" id="acter" value="" />');
showtablefooter();
showformfooter();
?>
<script>
    function unbinduser(uid, username){
        if(confirm('<?php echo $confirm2; ?>'.replace('%s', 'UID:'+uid+' '+username))) {
            $('actid').value = uid;
            $('acter').value = 'unbinduser';
            $('vlist').submit();
        }
        return false;
    }
    function unbindanddel(uid, username){
        if(confirm('<?php echo $confirm1; ?>'.replace('%s', 'UID:'+uid+' '+username))) {
            $('actid').value = uid;
            $('acter').value = 'unbindanddel';
            $('vlist').submit();
            return false;
        }
    }
</script>