<?php
/**
 * Created by PhpStorm.
 * User: yzg
 * Date: 2016/9/30
 * Time: 15:06
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);
include_once DISCUZ_ROOT. 'source/plugin/wechat/wechat.lib.class.php';
include_once libfile('function/cache');
$config = $_G['cache']['plugin']['xigua_login'];
$appid = trim($config['appid']);
$appsecert = trim($config['appsecert']);


$codeenc = intval($_GET['ode']);
if(!$codeenc){
    exit('codeenc is empty!');
}

$wechat_client = new WeChatClient($appid, $appsecert);
$token = $wechat_client->getAccessToken(1);
$openid = $tockeninfo['openid'];

$url     = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $token;
$data    =  "{\"expire_seconds\":2592000,\"action_name\":\"QR_SCENE\",\"action_info\":{\"scene\":{\"scene_id\":$codeenc}}}";
$jsonstr = ihttp_post23($url, $data);


$arrstr  = json_decode($jsonstr, true);
if($arrstr['errcode']){

    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecert}";
    $json = dfsockopen($url);
    if(!$json){
        $json = file_get_contents($url);
    }
    $access_data = json_decode($json, true);
    $access_token = $access_data['access_token'];

    if($access_token){
        savecache( 'wechatat_' . $_G['wechat']['setting']['wechat_appId'], array(
            'token' => $access_token,
            'expiration' => time() + 6200,
        ));
    }

    $url   = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $access_token;
    $jsonstr = ihttp_post23($url, $data);
}

//print_r($data);exit;
//print_r($jsonstr);exit;
if($arrstr['ticket']){
    dheader('location:https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($arrstr['ticket']));
}else{
    print_r($jsonstr);
    exit;
}




function ihttp_post23($url, $data) {
    if (!function_exists('curl_init')) {
        return '';
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    # curl_setopt( $ch, CURLOPT_HEADER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $data = curl_exec($ch);
    if (!$data) {
        error_log(curl_error($ch));
    }
    curl_close($ch);
    return $data;
}