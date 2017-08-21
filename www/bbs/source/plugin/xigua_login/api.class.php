<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/17
 * Time: 12:22
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
class xigua_login
{
    public function profile_variables(& $variables)
    {
        global $_G;
        if($_GET['backreferer']){
            $_G['messageparam'] = array();
            $params = base64_decode($_GET['backreferer']);
            $variables['function'] = array_merge(
                array('XLR' => array('WSQ.location', array($params))),
                (array)$variables['function']
            );
        }
    }

    public function profile_authorInfo(){
        if($_GET['backreferer']){
            return '<div style="display:none"><wsqscript>XLR()</wsqscript></div>';
        }
        return '';
    }

    public function profile_extraInfo()
    {
        $referer = getcookie('xg_referer');
        $link = $referer ? $referer : dreferer();
        if(strpos($link, 'http://') === false){
            global $_G;
            $link = $_G['siteurl'] . $link;
        }

        $return[] = array(
            'name' => "<a href=\"$link\">".lang('plugin/xigua_login', 'back').'</a>',
            'link' => $link,
        );
        return $return;
    }
}