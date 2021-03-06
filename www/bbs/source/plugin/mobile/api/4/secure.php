<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: secure.php 34428 2014-04-25 09:09:34Z nemohou $
 */
//note secure(验证安全) @ Discuz! X2.5

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

include_once 'misc.php';

class mobile_api {

	//note 程序模块执行前需要运行的代码
	function common() {
		global $_G;
		if(!empty($_GET['force'])) {
			$_G['setting']['seccodedata']['rule'][$_GET['type']]['allow'] = 1;
		}
		list($seccodecheck, $secqaacheck) = seccheck($_GET['type']);
		$sechash = random(8);
		if($seccodecheck || $secqaacheck) {
			$variable = array('sechash' => $sechash);
			if($seccodecheck) {
				$variable['seccode'] = $_G['siteurl'].'api/mobile/index.php?module=seccodehtml&sechash='.$sechash.'&version=4';
			}
			if($secqaacheck) {
				$variable['secqaa'] = make_secqaa();
			}
		}
		mobile_core::result(mobile_core::variable($variable));
	}

	//note 程序模板输出前运行的代码
	function output() {}

}

?>