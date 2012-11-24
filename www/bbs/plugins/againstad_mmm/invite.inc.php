<?php

define('CURSCRIPT', 'againstad_mmm');
require_once './include/common.inc.php';

if($discuz_uid){showmessage("对不起，本页面仅供游客使用。",'index.php');}
	if($action == 'buy') {
       $amount = intval($amount);
		$buyinvitecredit = $inviteprice ;
		$maxinviteday=1;
				$invitecode = substr(md5($discuz_uid.$timestamp.random(6)), 0, 10).random(6);
				$expiration = $timestamp + $maxinviteday * 86400;
				$db->query("INSERT INTO {$tablepre}invites (uid, dateline, expiration, inviteip, invitecode) VALUES ('$timestamp', '$timestamp', '$expiration', '0.0.0.0', '$invitecode')", 'UNBUFFERED');			
			showmessage("恭喜，邀请码免费获取成功，下面将为您自动转到注册页面。","register.php?invitecode=$invitecode");
			
		}else{include template('againstad_mmm:invite');}
?>