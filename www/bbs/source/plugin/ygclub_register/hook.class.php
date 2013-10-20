<?php
if(!defined('IN_DISCUZ')) {exit('Access Denied');}

class plugin_ygclub_register{}

class plugin_ygclub_register_member extends plugin_ygclub_register{

function register_top(){
    if(ereg('answer/index.php', $_SERVER['HTTP_REFERER']) && $_GET['from'] == 'answer')
        return '';
    elseif(submitcheck('regsubmit'))
    {
        return '';
    }
    else
    {
        header('location:http://www.ygclub.org/bbs/thread-4734-1-1.html');
    }
}
}
?>
