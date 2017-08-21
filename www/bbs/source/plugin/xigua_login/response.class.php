<?php
/**
 * Created by PhpStorm.
 * User: yzg
 * Date: 2016/7/18
 * Time: 10:43
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
include_once  DISCUZ_ROOT.'source/plugin/xigua_login/function.php';

class LoginResponse
{
    public static function post($url, $data) {
        if (!function_exists('curl_init')) {
            return false;
        }
        $header[]= "Content-type: text/xml";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);

        if (!$data) {
            error_log(curl_error($ch));
        }
        curl_close($ch);
        return $data;
    }

    public function receiveAllEnd()
    {
        $postdata = file_get_contents("php://input");

        global $_G;
        if (empty($_G['cache']['plugin'])) {
            loadcache('plugin');
        }
        $config = $_G['cache']['plugin']['xigua_login'];
        unset($_REQUEST['id']);
        unset($_REQUEST['module']);
        if($config['wechaturl'] && strpos($config['wechaturl'], 'id=552')===false){
            $wechat_api= $config['wechaturl'].(strpos($config['wechaturl'], '?')===false?'?':'&').http_build_query($_REQUEST);
            $ret = self::post($wechat_api, $postdata);
            echo $ret;
        }
    }

    public static function do_processs($openid, $code)
    {
        global $_G;
        if (empty($_G['cache']['plugin'])) {
            loadcache('plugin');
        }
        $config = $_G['cache']['plugin']['xigua_login'];
        if(!class_exists('WeChatClient')) {
            include_once DISCUZ_ROOT . 'source/plugin/wechat/wechat.lib.class.php';
        }
        include_once libfile('function/cache');
        $wechat_client = new WeChatClient($config['appid'], $config['appsecert']);
        $userinfo = $wechat_client->getUserInfoById($openid);
        $userinfo['nickname'] = self::filterEmoji12($userinfo['nickname']);
        foreach ($userinfo as $index => $item) {
            $userinfo[$index] = diconv($userinfo[$index], 'utf-8');
        }
        if($userinfo){
            if (is_numeric($code)&& $code>=110000 && $code <=999999){
                login_access($openid, $code, $userinfo, $config);
            }
        }
    }

    public static function subscribe($param)
    {
        list($data) = $param;
        $openid = $data['from'];
        if($openid){
            self::do_processs($openid, $data['key']);
        }
    }

    public static function scan($param)
    {
        self::subscribe($param);
    }

    public function text($param)
    {
        list($data) = $param;
        $content = diconv($data['content'], 'UTF-8');
        if($content==lang('plugin/xigua_login', 'login')){
            $content = '999999';
        }
        $openid = $data['from'];
        if($openid){
            self::do_processs($openid, $content);
        }
    }

    public function click($param)
    {
        list($data) = $param;
        $content = $data['key'];
        if(strtolower($content)=='xigua_login'){
            $content = '999998';
        }else if(substr($content, 0, 12)=='xigua_login|'){
            $GLOBALS['custom_url'] = substr($content, 12);
            if(strpos($GLOBALS['custom_url'] , 'http://')==false ||strpos($GLOBALS['custom_url'] , 'https://')==false){
                $GLOBALS['custom_url'] = '//'.$GLOBALS['custom_url'];
            }
            $content = '999998';
        }
        $openid = $data['from'];
        if($openid){
            self::do_processs($openid, $content);
        }
    }

    public static function filterEmoji12($str){
        $str = preg_replace_callback( '/./u', 'match421', $str);

        $str = diconv($str, 'utf-8', 'gbk');
        $str = diconv($str, 'gbk', 'utf-8');

        $str = self::safe_replace($str, 1);
        $str = str_replace(array(" ", "\t", "\n", "\r"), '',$str);
        return $str;
    }
    public static function safe_replace($string, $empty = 0) {
        $string = str_replace('%20','',$string);
        $string = str_replace('%27','',$string);
        $string = str_replace('%2527','',$string);
        $string = str_replace('*','',$string);
        $string = str_replace('"', ($empty ?'': '&quot;'),$string);
        $string = str_replace("'",'',$string);
        $string = str_replace('"','',$string);
        $string = str_replace(';','',$string);
        $string = str_replace('<',($empty ?'': '&lt;'),$string);
        $string = str_replace('>',($empty ?'': '&gt;'),$string);
        $string = str_replace("{",'',$string);
        $string = str_replace('}','',$string);
        $string = str_replace('\\','',$string);
        return $string;
    }
}