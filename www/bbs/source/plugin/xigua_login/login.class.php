<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/15
 * Time: 15:43
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);

require_once libfile('function/member');
require_once DISCUZ_ROOT.'source/plugin/wechat/wechat.class.php';

function __getErrorMessage($errroCode) {
    $str = sprintf('connect_error_code_%d', $errroCode);

    return lang('plugin/qqconnect', $str);
}
function __connect_login($connect_member) {
    global $_G;

    if(!($member = getuserbyuid($connect_member['uid'], 1))) {
        return false;
    } else {
        if(isset($member['_inarchive'])) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
    }

    require_once libfile('function/member');
    $cookietime = 1296000;
    setloginstatus($member, $cookietime);

    dsetcookie('connect_login', 1, $cookietime);
    dsetcookie('connect_is_bind', '1', 31536000);
    dsetcookie('connect_uin', $connect_member['conopenid'], 31536000);
    return true;
}

class plugin_xigua_login{
    public function register_logging_method()
    {
        global $_G;
        $config = $_G['cache']['plugin']['xigua_login'];
        if(!$config['pcbind']){
            return '';
        }
        return '<a href="javascript:;" onclick="showWindow(\'wechat_bind1\', \'plugin.php?id=xigua_login:login\')"><img src="source/plugin/xigua_login/static/wechat_login1.png" align="absmiddle"></a> ';
    }

    public function global_usernav_extra1()
    {
        global $_G;
        $config = $_G['cache']['plugin']['xigua_login'];
        if(!$config['pcbind']){
            return '';
        }
        global $_G;
        $has = '<span class="pipe">|</span><a href="javascript:;" onclick="showWindow(\'wechat_bind1\', \'plugin.php?id=xigua_login:login\')"><img src="source/plugin/xigua_login/static/wechat_bind.png" class="qq_bind" align="absmiddle"></a> ';
        if($_G['uid']){
            $fetch = C::t('#wechat#common_member_wechat')->fetch($_G['uid']);
            if($fetch){
                $has = '';
            }
        }
        return !$_G['uid'] ? '<span class="pipe">|</span><a href="javascript:;" onclick="showWindow(\'wechat_bind1\', \'plugin.php?id=xigua_login:login\')"><img src="source/plugin/xigua_login/static/wechat_login.png" class="qq_bind" align="absmiddle"></a> ' : $has;
    }

    public function global_login_text()
    {
        return $this->logging_method();
    }
    function logging_method()
    {
        global $_G;
        $config = $_G['cache']['plugin']['xigua_login'];
        if(!$config['pcbind']){
            return '';
        }
        return '<a href="javascript:;" onclick="showWindow(\'wechat_bind1\', \'plugin.php?id=xigua_login:login\')"><img src="source/plugin/xigua_login/static/wechat_login1.png" align="absmiddle"></a> ';
    }
    function global_login_extra(){
        global $_G;
        $config = $_G['cache']['plugin']['xigua_login'];
        if(!$config['pcbind']){
            return '';
        }
        return "<div class=\"fastlg_fm y\" style=\"margin-right: 10px; padding-right: 10px\">
		<p><a href=\"javascript:;\" onclick=\"showWindow('wechat_bind1', 'plugin.php?id=xigua_login:login')\"><img src=\"source/plugin/xigua_login/static/wechat_login1.png\" class=\"vm\"/></a></p>
		<p class=\"hm xg1\" style=\"padding-top: 2px;\">{$config['onekey']}</p>
	</div>";
    }

    public static function doconnect_login($connect_member) {
        global $_G;
        if(!$_G['cache']['plugin']['xigua_login']['qqmiao']){
            return false;
        }
        if(!($member = getuserbyuid($connect_member['uid'], 1))) {
            return false;
        } else {
            if(isset($member['_inarchive'])) {
                C::t('common_member_archive')->move_to_master($member['uid']);
            }
        }

        require_once libfile('function/member');
        $cookietime = 1296000;
        setloginstatus($member, $cookietime);

        dsetcookie('connect_login', 1, $cookietime);
        dsetcookie('connect_is_bind', '1', 31536000);
        dsetcookie('connect_uin', $connect_member['conopenid'], 31536000);

        global $_G;
        $params['mod'] = 'login';

        loadcache('usergroups');
        $usergroups = $_G['cache']['usergroups'][$_G['groupid']]['grouptitle'];
        $param = array('username' => $_G['member']['username'], 'usergroup' => $_G['group']['grouptitle']);

        C::t('common_member_status')->update($connect_member['uid'], array('lastip'=>$_G['clientip'], 'lastvisit'=>TIMESTAMP, 'lastactivity' => TIMESTAMP));
        $ucsynlogin = '';
        if($_G['setting']['allowsynlogin']) {
            loaducenter();
            $ucsynlogin = uc_user_synlogin($_G['uid']);
            if($ucsynlogin){
                showmessage('login_succeed', dreferer(), $param, array('extrajs' => $ucsynlogin));
            }
        }

        dsetcookie('stats_qc_login', 3, 86400);

        dheader('Location: '.dreferer());
        return true;
    }

    public static function do_register($username, $return = 0, $otype = 'qq', $groupid = 0, $userinfo = array()) {
        global $_G;

        if(!$_G['cache']['plugin']['xigua_login']['qqmiao']){
            return false;
        }
        if(!$username) {
            return;
        }
        if(!$_G['cache']['plugin']){
            loadcache('plugin');
        }
        $config = $_G['cache']['plugin']['xigua_login'];

        loaducenter();
        $groupid = !$groupid ? ($config['groupid'] ? $config['groupid'] : $_G['setting']['newusergroupid']) : $groupid;

        $password = md5(random(10));
        $email = $otype . '_' . strtolower(random(8)).'@'.$otype.'.com';

        $username = str_replace(array('[', ']', '{', '}', '+', '*', '\\', '$', '?', ' ', "\n", "\r", '%', '/' ), '', trim($username));
        $usernamelen = dstrlen($username);
        if($usernamelen < 3) {
            $username = $username.mt_rand(1000, 9999);
        }
        if($usernamelen > 15) {
            $username = cutstr($username, 15, '');
        }
        if(C::t('common_member')->fetch_by_username($username) || uc_user_checkname($username)!='1'){
            $username =  cutstr($username, 12, '').mt_rand(1, 999);
        }

        $censorexp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($_G['setting']['censoruser'] = trim($_G['setting']['censoruser'])), '/')).')$/i';

        if($_G['setting']['censoruser'] && @preg_match($censorexp, $username)) {
            if(!$return) {
                showmessage('profile_username_protect');
            } else {
                return;
            }
        }

        $sms = '';
        @include_once DISCUZ_ROOT. 'source/discuz_version.php';
        if(DISCUZ_VERSION == 'F1.0'){
            $sms = '139'.mt_rand(10000000, 99999999);
            $uid = uc_user_register(addslashes($username), $password, $email, $sms, '', '', $_G['clientip']);
        }else{
            $uid = uc_user_register(addslashes($username), $password, $email, '', '', $_G['clientip']);
        }
        if($uid <= 0) {
            if(!$return) {
                if($uid == -1) {
                    showmessage('profile_username_illegal');
                } elseif($uid == -2) {
                    showmessage('profile_username_protect');
                } elseif($uid == -3) {
                    showmessage('profile_username_duplicate');
                } elseif($uid == -4) {
                    showmessage('profile_email_illegal');
                } elseif($uid == -5) {
                    showmessage('profile_email_domain_illegal');
                } elseif($uid == -6) {
                    showmessage('profile_email_duplicate');
                } else {
                    showmessage('undefined_action');
                }
            } else {
                return;
            }
        }

        $init_arr = array('credits' => explode(',', $_G['setting']['initcredits']));
        if($sms) {
            C::t('common_member')->insert($uid, $username, $password, $email, $sms, $_G['clientip'], $groupid, $init_arr);
        }else{
            C::t('common_member')->insert($uid, $username, $password, $email, $_G['clientip'], $groupid, $init_arr);
        }

        if($_G['setting']['regverify'] == 2) {
            C::t('common_member_validate')->insert(array(
                'uid' => $uid,
                'submitdate' => $_G['timestamp'],
                'moddate' => 0,
                'admin' => '',
                'submittimes' => 1,
                'status' => 0,
                'message' => '',
                'remark' => '',
            ), false, true);
            manage_addnotify('verifyuser');
        }

        setloginstatus(array(
            'uid' => $uid,
            'username' => $username,
            'password' => $password,
            'groupid' => $groupid,
        ), 0);

        include_once libfile('function/stat');
        updatestat('register');

        WeChat::syncAvatar($uid, $userinfo['figureurl_qq_2']);
        $gender = $userinfo['gender'] ==lang('plugin/xigua_login', 'gender2') ? 2 : ($userinfo['gender'] ==lang('plugin/xigua_login', 'gender1') ? 1 : 0);
        C::t('common_member_profile')->update($uid, array('realname'=> $userinfo['nickname'], 'gender'=>$gender, 'birthyear' => $userinfo['year'], ));

        return $uid;
    }

    public static function get_connect_userInfo($conopenid, $conuintoken, $conuin, $conuinsecret)
    {
        $connectUserInfo = array();
        global $_G;
        if(!$_G['cache']['plugin']['xigua_login']['qqmiao']){
            return false;
        }
        try {
            $connectOAuthClient = Cloud::loadClass('Service_Client_ConnectOAuth');
            $connectUserInfo = $connectOAuthClient->connectGetUserInfo_V2($conopenid, $conuintoken);
            if ($connectUserInfo['nickname']) {
                $connectUserInfo['nickname'] = strip_tags($connectUserInfo['nickname']);
            }
        } catch(Exception $e) {
        }

        return $connectUserInfo;
    }

    public function login_callback(){
        global $_G;

        if(!$_G['cache']['plugin']['xigua_login']['qqmiao']){
            return false;
        }
        if(DISCUZ_VERSION=='X3.4'){
            return false;
        }
        $params = $_GET;
        $referer = dreferer();

        if($params['op'] != 'callback'){
            return false;
        }

        try {
            $connectOAuthClient = Cloud::loadClass('Service_Client_ConnectOAuth');
        } catch(Exception $e) {
            showmessage('qqconnect:connect_app_invalid');
        }

        if(!isset($params['receive'])) {
            $utilService = Cloud::loadClass('Service_Util');
            echo '<script type="text/javascript">setTimeout("window.location.href=\'connect.php?receive=yes&'.str_replace("'", "\'", $utilService->httpBuildQuery($_GET, '', '&')).'\'", 1)</script>';
            exit;
        }


            if($_GET['state'] != md5(FORMHASH)){
                showmessage('qqconnect:connect_get_access_token_failed', $referer);
            }
            try {
                $response = $connectOAuthClient->connectGetOpenId_V2($_G['cookie']['con_request_uri'], $_GET['code']);
            } catch(Exception $e) {
                showmessage('qqconnect:connect_get_access_token_failed_code', $referer, array('codeMessage' => __getErrorMessage($e->getmessage()), 'code' => $e->getmessage()));
            }

            dsetcookie('con_request_token');
            dsetcookie('con_request_token_secret');

            $conuintoken = $response['access_token'];
            $conopenid = strtoupper($response['openid']);
            if(!$conuintoken || !$conopenid) {
                showmessage('qqconnect:connect_get_access_token_failed', $referer);
            }


        loadcache('connect_blacklist');
        if(in_array($conopenid, array_map('strtoupper', $_G['cache']['connect_blacklist']))) {
            $change_qq_url = $_G['connect']['discuz_change_qq_url'];
            showmessage('qqconnect:connect_uin_in_blacklist', $referer, array('changeqqurl' => $change_qq_url));
        }

        $referer = $referer && (strpos($referer, 'logging') === false) && (strpos($referer, 'mod=login') === false) ? $referer : 'index.php';

        if($params['uin']) {
            $old_conuin = $params['uin'];
        }

        $is_notify = true;

        $conispublishfeed = 0;
        $conispublisht = 0;

        $is_user_info = 1;
        $is_feed = 1;

        $user_auth_fields = 1;

        $cookie_expires = 2592000;
        dsetcookie('client_created', TIMESTAMP, $cookie_expires);
        dsetcookie('client_token', $conopenid, $cookie_expires);

        $connect_member = array();
        $fields = array('uid', 'conuin', 'conuinsecret', 'conopenid');
        if($old_conuin) {
            $connect_member = C::t('#qqconnect#common_member_connect')->fetch_fields_by_openid($old_conuin, $fields);
        }
        if(empty($connect_member)) {
            $connect_member = C::t('#qqconnect#common_member_connect')->fetch_fields_by_openid($conopenid, $fields);
        }
        if($connect_member) {
            $member = getuserbyuid($connect_member['uid']);
            if($member) {
                if(!$member['conisbind']) {
                    C::t('#qqconnect#common_member_connect')->delete($connect_member['uid']);
                    unset($connect_member);
                } else {
                    $connect_member['conisbind'] = $member['conisbind'];
                }
            } else {
                C::t('#qqconnect#common_member_connect')->delete($connect_member['uid']);
                unset($connect_member);
            }
        }

        $connect_is_unbind = $params['is_unbind'] == 1 ? 1 : 0;
        if($connect_is_unbind && $connect_member && !$_G['uid'] && $is_notify) {
            dsetcookie('connect_js_name', 'user_bind', 86400);
            dsetcookie('connect_js_params', base64_encode(serialize(array('type' => 'registerbind'))), 86400);
        }

        if($_G['uid']) {

            if($connect_member && $connect_member['uid'] != $_G['uid']) {
                showmessage('qqconnect:connect_register_bind_uin_already', $referer, array('username' => $_G['member']['username']));
            }

            $isqqshow = !empty($_GET['isqqshow']) ? 1 : 0;

            $current_connect_member = C::t('#qqconnect#common_member_connect')->fetch($_G['uid']);
            if($_G['member']['conisbind'] && $current_connect_member['conopenid']) {
                if(strtoupper($current_connect_member['conopenid']) != $conopenid) {
                    showmessage('qqconnect:connect_register_bind_already', $referer);
                }
                C::t('#qqconnect#common_member_connect')->update($_G['uid'],
                    array(
                        'conuintoken' => $conuintoken,
                        'conopenid' => $conopenid,
                        'conisregister' => 0,
                        'conisfeed' => 1,
                        'conisqqshow' => $isqqshow,
                    )
                );

            } else { // debug 当前登录的论坛账号并没有绑定任何QQ号，则可以绑定当前的这个QQ号
                if(empty($current_connect_member)) {
                    C::t('#qqconnect#common_member_connect')->insert(
                        array(
                            'uid' => $_G['uid'],
                            'conuin' => '',
                            'conuintoken' => $conuintoken,
                            'conopenid' => $conopenid,
                            'conispublishfeed' => $conispublishfeed,
                            'conispublisht' => $conispublisht,
                            'conisregister' => 0,
                            'conisfeed' => 1,
                            'conisqqshow' => $isqqshow,
                        )
                    );
                } else {
                    C::t('#qqconnect#common_member_connect')->update($_G['uid'],
                        array(
                            'conuintoken' => $conuintoken,
                            'conopenid' => $conopenid,
                            'conispublishfeed' => $conispublishfeed,
                            'conispublisht' => $conispublisht,
                            'conisregister' => 0,
                            'conisfeed' => 1,
                            'conisqqshow' => $isqqshow,
                        )
                    );
                }
                C::t('common_member')->update($_G['uid'], array('conisbind' => '1'));

                C::t('#qqconnect#common_connect_guest')->delete($conopenid);
            }

            if($is_notify) {
                dsetcookie('connect_js_name', 'user_bind', 86400);
                dsetcookie('connect_js_params', base64_encode(serialize(array('type' => 'loginbind'))), 86400);
            }
            dsetcookie('connect_login', 1, 31536000);
            dsetcookie('connect_is_bind', '1', 31536000);
            dsetcookie('connect_uin', $conopenid, 31536000);
            dsetcookie('stats_qc_reg', 3, 86400);
            if($is_feed) {
                dsetcookie('connect_synpost_tip', 1, 31536000);
            }

            C::t('#qqconnect#connect_memberbindlog')->insert(
                array(
                    'uid' => $_G['uid'],
                    'uin' => $conopenid,
                    'type' => 1,
                    'dateline' => $_G['timestamp'],
                )
            );

            showmessage('qqconnect:connect_register_bind_success', $referer);

        } else {

            if($connect_member) { // debug 此分支是用户直接点击QQ登录，并且这个QQ号已经绑好一个论坛账号了，将直接登进论坛了
                C::t('#qqconnect#common_member_connect')->update($connect_member['uid'],
                    array(
                        'conuintoken' => $conuintoken,
                        'conopenid' => $conopenid,
                        'conisfeed' => 1,
                    )
                );

                $params['mod'] = 'login';
                __connect_login($connect_member);

                loadcache('usergroups');
                $usergroups = $_G['cache']['usergroups'][$_G['groupid']]['grouptitle'];
                $param = array('username' => $_G['member']['username'], 'usergroup' => $_G['group']['grouptitle']);

                C::t('common_member_status')->update($connect_member['uid'], array('lastip'=>$_G['clientip'], 'lastvisit'=>TIMESTAMP, 'lastactivity' => TIMESTAMP));
                $ucsynlogin = '';
                if($_G['setting']['allowsynlogin']) {
                    loaducenter();
                    $ucsynlogin = uc_user_synlogin($_G['uid']);
                    if($ucsynlogin){
                        showmessage('login_succeed', $referer, $param, array('extrajs' => $ucsynlogin));
                    }
                }

                dsetcookie('stats_qc_login', 3, 86400);
                dheader('Location: '.$referer);

            } else { // debug 此分支是用户直接点击QQ登录，并且这个QQ号还未绑定任何论坛账号，将自动登录》》》》》

                $userinfo = self::get_connect_userInfo($conopenid, $conuintoken, $conuin, $conuinsecret);

                if($uid = self::do_register($userinfo['nickname'],0,'qq',0, $userinfo)){

                    C::t('#qqconnect#common_member_connect')->insert(array(
                        'uid' => $uid,
                        'conuin' => $conuin,
                        'conuinsecret' => $conuinsecret,
                        'conuintoken' => $conuintoken,
                        'conopenid' => $conopenid,
                        'conispublishfeed' => $conispublishfeed,
                        'conispublisht' => $conispublisht,
                        'conisregister' => '1',
                        'conisqzoneavatar' => 1,
                        'conisfeed' => '1',
                        'conisqqshow' => 1,
                    ));

                    dsetcookie('connect_js_name', 'user_bind', 86400);
                    dsetcookie('connect_js_params', base64_encode(serialize(array('type' => 'register'))), 86400);
                    dsetcookie('connect_login', 1, 31536000);
                    dsetcookie('connect_is_bind', '1', 31536000);
                    dsetcookie('connect_uin', $conopenid, 31536000);
                    dsetcookie('stats_qc_reg', 1, 86400);
                    if ($_GET['is_feed']) {
                        dsetcookie('connect_synpost_tip', 1, 31536000);
                    }

                    C::t('#qqconnect#connect_memberbindlog')->insert(array('uid' => $uid, 'uin' => $conopenid, 'type' => '1', 'dateline' => $_G['timestamp']));
                    dsetcookie('con_auth_hash');

//                    $auth_hash = authcode($conopenid, 'ENCODE');
//                    dsetcookie('con_auth_hash', $auth_hash, 86400);
//                    dsetcookie('connect_js_name', 'guest_ptlogin', 86400);
//                    dsetcookie('stats_qc_login', 4, 86400);

                    C::t('#qqconnect#common_connect_guest')->delete($conopenid);
                    if(!function_exists('build_cache_userstats')) {
                        require_once libfile('cache/userstats', 'function');
                    }
                    build_cache_userstats();

                    $userdata = array();
                    $userdata['avatarstatus'] = 1;
                    $userdata['conisbind'] = 1;
                    if($_G['setting']['connect']['register_groupid']) {
                        $userdata['groupid'] = $groupinfo['groupid'] = $_G['setting']['connect']['register_groupid'];
                    }
                    C::t('common_member')->update($uid, $userdata);

                    if($_G['setting']['connect']['register_addcredit']) {
                        $addcredit = array('extcredits'.$_G['setting']['connect']['register_rewardcredit'] => $_G['setting']['connect']['register_addcredit']);
                    }
                    C::t('common_member_count')->increase($uid, $addcredit);

                    self::doconnect_login(array('uid' => $uid));
                }

            }
        }
        dexit();
    }
}

class plugin_xigua_login_member extends plugin_xigua_login{}

class mobileplugin_xigua_login_member extends plugin_xigua_login
{
    public function logging_1_output(){
        global $pluginversion;
        $pluginversion = '2017041409';

        if($_GET['infloat']){
            return false;
        }
        if(strtolower($_SERVER['REQUEST_METHOD']) != 'get'){
            return false;
        }
        if ($_GET['action'] != 'login') {
            return false;
        }
        global $_G;
        if(!$_G['cache']['plugin']){
            loadcache('plugin');
        }

        $referer = $_SERVER['HTTP_REFERER'];
        if(strpos($referer, 'http://') === false && strpos($referer, 'https://') === false){
            $referer = $_G['siteurl'] . $referer;
        }

        $wechat = unserialize($_G['setting']['mobilewechat']);
        $config = $_G['cache']['plugin']['xigua_login'];
        if(!$config['get']){
            return false;
        }
        if(!$config['bgcolor']){
            $config['bgcolor'] ='#f5f5f5';
        }
        if($config['radius']){
            $rds = intval($config['radius']).'px';
            $radius = ".form-control input, .form-control select,.btn{border-radius:$rds}";
        }
        if ($config['opacity']) {
            $opacity = $config['opacity']/100;
            $opacity = ".login_from{opacity:$opacity}";
        }
        if($config['logincolor']){
            $logincolor = ".btn-outline{background-color:$config[logincolor]}";
        }
        if($config['onekeycolor']){
            $onekeycolor = ".btn-orange{background-color:$config[onekeycolor]}";
        }
        if($config['bgimg']){
            if($zTopbg = array_filter(explode("\n", trim($config['bgimg']))) ){
                $_k = array_rand($zTopbg, 1);
                $topbg = trim($zTopbg[$_k]);
            }

            $bgimg = "html,body{background:url($topbg) no-repeat center center;background-size:cover}";
        }
        if($config['regcolor']){
            $regcolor = ".reg_link, .reg_link a{font-size:14px;color:$config[regcolor]}";
        }

        $customstyle = "html,body{background-color:$config[bgcolor];}$radius$opacity$logincolor$onekeycolor$bgimg$regcolor";


        $param = urlencode(($referer));
        $wehaturl = $_G['siteurl'] . "plugin.php?id=xigua_login:login&backreferer=$param";

        $inwechat = strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger") !== false;
        if($config['hidebtn']){
            $inwechat = false;
        }
        $logo = $_G['style']['boardlogo'] ?$_G['style']['boardlogo']:"<img src=\"$wechat[wsq_sitelogo]\" />";
        if($config['logo']){
            $logo = "<img src=\"$config[logo]\" />";
        }
        if(!$_G['connect']['login_url']){
            $_G['connect']['login_url'] = $_G['siteurl'].'connect.php?mod=login&op=init';
        }

        $loginhash = 'L'.random(4);

        if($_G['uid']) {
            dheader('Location:'.($referer ? $referer : './'));
        }
/*
        if($config['openautologin'] && (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)){
            $url = dreferer() ? dreferer() : $this->currenturl_232();
            if(strpos($url, 'code=') !== false || strpos($url, 'logout') !== false|| strpos($url, 'login') !== false){
                $url = $_G['siteurl'];
            }
            if(strpos($url, 'logout') === false){
                dheader("Location: ".$_G['siteurl'].'plugin.php?id=xigua_login:login&backreferer='.urlencode($url));
            }
        }*/
        if(function_exists('seccheck')){
            list($seccodecheck) = seccheck('login');
            if(!empty($_GET['auth'])) {
                $dauth = authcode($_GET['auth'], 'DECODE', $_G['config']['security']['authkey']);
                list(,,,$secchecklogin2) = explode("\t", $dauth);
                if($secchecklogin2) {
                    $seccodecheck = true;
                }
            }
            $seccodestatus = !empty($_GET['lssubmit']) ? false : $seccodecheck;
            $invite = getinvite();
            if($seccodecheck) {
                $seccode = random(6, 1) + $seccode{0} * 1000000;
            }
        }

        $auth = '';
        $username = !empty($_G['cookie']['loginuser']) ? dhtmlspecialchars($_G['cookie']['loginuser']) : '';
        $please_inwechat = lang('plugin/xigua_login', 'please_inwechat');

        if(!empty($_GET['auth'])) {
            list($username, $password, $questionexist) = explode("\t", authcode($_GET['auth'], 'DECODE', $_G['config']['security']['authkey']));
            $username = dhtmlspecialchars($username);
            $auth = dhtmlspecialchars($_GET['auth']);
        }

        $cookietimecheck = !empty($_G['cookie']['cookietime']) || !empty($_GET['cookietime']) ? 'checked="checked"' : '';

        dsetcookie('xg_referer', $referer, 86400);
        $navtitle = lang('core', 'title_login');


        dsetcookie('widthauto', 1, 30);
        include template('xigua_login:member/login');
        dexit();
    }
    function currenturl_232($related = 0) {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $related ? $relate_url : $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }

    function logging_bottom_mobile(){
        global $_G;
        $config = $_G['cache']['plugin']['xigua_login'];
        if($config['hidebtn']){
            return false;
        }
        $referer = $_SERVER['HTTP_REFERER'];
        if(strpos($referer, 'http://') === false && strpos($referer, 'https://') === false){
            $referer = $_G['siteurl'] . $referer;
        }
        $param = urlencode(($referer));
        $wehaturl = $_G['siteurl'] . "plugin.php?id=xigua_login:login&backreferer=$param";

        $inwechat = strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger") !== false;
        if(!$inwechat){
            $please_inwechat = lang('plugin/xigua_login', 'please_inwechat');
            $wehaturl = "javascript:alert('$please_inwechat');";
        }else{
            $wehaturl = "javascript:window.location.href='$wehaturl'";
        }        if(!$config['bgcolor']){
            $config['bgcolor'] ='#f5f5f5';
        }
        if($config['radius']){
            $rds = intval($config['radius']).'px';
            $radius = ".form-control input, .form-control select,.btn{border-radius:$rds}";
        }
        if ($config['opacity']) {
            $opacity = $config['opacity']/100;
            $opacity = ".login_from{opacity:$opacity}";
        }
        if($config['logincolor']){
            $logincolor = ".btn-outline{background-color:$config[logincolor]}";
        }
        if($config['onekeycolor']){
            $onekeycolor = ".btn-orange{background-color:$config[onekeycolor]}";
        }

        if($config['regcolor']){
            $regcolor = ".reg_link, .reg_link a{font-size:14px;color:$config[regcolor]}";
        }

        $customstyle = "$radius$opacity$logincolor$onekeycolor$regcolor";

        $html = <<<HTML
<style>
.reg_link1{text-align: center;width:100%;margin-top:12px}
.reg_tp{background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAArwAAAABAQMAAAAGv++CAAAABlBMVEX////Z2dl4b8nnAAAAAXRSTlMAQObYZgAAABRJREFUeNpj+E8Y/GBABYxE6PkAAHcsR6Qt8qYTAAAAAElFTkSuQmCC) no-repeat center center;margin:0 15px}
.loginbtn1{display: -moz-box;display: -webkit-box;display:box;text-align: center;width:160px;margin:15px auto;}
.loginbtn1 div{-moz-box-flex: 1;-webkit-box-flex: 1;box-flex: 1;}
.loginbtn1 a {width:40px;height:40px;display: block;margin: 0 auto;border-radius: 50%;background-color: #55BC22;}
.loginbtn1 .btn_wechatlogin span{ line-height:24px; }
.loginbtn1 a img{width:100%;height:100%;display:block;border-radius:50px;}
.loginbtn1 a span{font-size: 20px;line-height:40px;color:#fff;}
$customstyle
</style>
<div class="reg_link1">
    <div class="reg_tp">$config[sanguide]</div>
    <div class="loginbtn1">
        <div class="btn_wechatlogin">
            <a href="$wehaturl"><img src="source/plugin/xigua_login/template/touch/member/wechat-08-535x535.png"></a>
            <span>$config[wechatword]</span>
        </div>
    </div>
</div>
HTML;

        return $html;
    }
}
class mobileplugin_xigua_login extends plugin_xigua_login{

    public function common(){
        global $_G;
        $inwchat = (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false);
        $config = $_G['cache']['plugin']['xigua_login'];
        if(
            !$_G['cookie']['widthauto'] &&
            $config['openautologin'] &&
            !$_G['uid'] &&
            $inwchat &&
            CURSCRIPT!='member' &&
            CURSCRIPT!='check' &&
            !$_G['inajax'] &&
            !defined('IN_MOBILE_API')
            &&!$_GET['version']
            &&!$_GET['statfrom']
        ){
            if($config['qrcode']){
                return;
            }
            if(strpos($_REQUEST['id'],'xigua_login')!==false){
                return;
            }

            $url = $this->currenturl_231();
            if(strpos($url, 'code=') !== false || strpos($url, 'logout') !== false|| strpos($url, 'login') !== false){
                $url = $_G['siteurl'];
            }
            if(strpos($url, 'logout') === false){
            dsetcookie('widthauto', 1, 30);
            dheader("Location: ".$_G['siteurl'].'plugin.php?id=xigua_login:login&backreferer='.urlencode($url));
            }
        }

        /*$authkey = $_G['config']['security']['authkey'];
        $cookie = substr(md5('wechatdd'.$_G['siteurl'].$config['appid']), 0, 8);
        $openid = !empty($_G['cookie'][$cookie]) ? authcode($_G['cookie'][$cookie], 'DECODE', $authkey) : '';
        $wechat_client = new WeChatClient($config['appid'], $config['appsecert']);
        $userinfo = $wechat_client->getUserInfoById($openid);
        if($userinfo && !$userinfo['subscribe']){
            dheader('Location: ');
        }*/
    }

    function currenturl_231($related = 0) {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
        return $related ? $relate_url : $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }

}

class mobileplugin_xigua_login_connect extends plugin_xigua_login{}
class plugin_xigua_login_connect extends plugin_xigua_login{}

class mobileplugin_xigua_login_plugin extends plugin_xigua_login
{
    public function wechat_callback(){
        if($_GET['code']){
            $_GET['receive'] = 'yes';
        }
        $this->login_callback();
    }
}

class plugin_xigua_login_plugin extends plugin_xigua_login
{
}