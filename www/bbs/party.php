<?php
/* Power by http://www.sanchi.com.cn/ && QQ: 1175132 (神飞的梦) */

if (defined('IN_PARTY')){
	if (IN_PARTY==1){
		$sc_data = sc_perms($fid);
        $cpartyinfo = $db->fetch_first("select tid from {$tablepre}party where ctid='$tid'");
        if($cpartyinfo)
        {
            $cpartyinfo = $db->fetch_first("select tid, subject from {$tablepre}threads where tid='$cpartyinfo[tid]'");
        }
		$party = $db->fetch_first("select * from {$tablepre}party where tid='$tid'");
		if ($party){
			// 时间
			$party_date = array('1'=>'星期一','2'=>'星期二','3'=>'星期三','4'=>'星期四','5'=>'星期五','6'=>'星期六','7'=>'星期天');
			$party['showtime_orgin'] = $party['showtime'];
			$party['showtime'] = gmdate('Y-m-d H:i',$party['showtime']+3600*$_DSESSION['timeoffset'])." (".$party_date[gmdate('N',$party['showtime']+3600*$_DSESSION['timeoffset'])].")";
			$party['starttimefrom'] = gmdate('Y-m-d H:i',$party['starttimefrom']+3600*$_DSESSION['timeoffset'])." (".$party_date[gmdate('N',$party['starttimefrom']+3600*$_DSESSION['timeoffset'])].")";
			$party['starttimeto'] = gmdate('Y-m-d H:i',$party['starttimeto']+3600*$_DSESSION['timeoffset'])." (".$party_date[gmdate('N',$party['starttimeto']+3600*$_DSESSION['timeoffset'])].")";
			// 活动状态
			$party_img = $party['closed'] == 1 ? 'end' : 'on';
			// 性别
			$party_xb = array('0'=>'(不限性别)','1'=>'(仅限于男性)','2'=>'(仅限于女性)');
            $party_xb = $party_xb[$party['gender']];

            // 分工
            $ex_workers = empty($party['doworker']) ? "" : explode(',',$party['doworker']);
            
            // 备注
			//if (strstr($party['marks'],'|')){
				$ex_marks = explode('|',$party['marks']);
				for($mi = 0; $mi < count($ex_marks); $mi++){
					${"marksCount".$mi} = $db->result_first("select count(*) from {$tablepre}partyers where marks='$mi' and tid='$tid' and verified='4'");
					$passCountMarks[] = ${"marksCount".$mi};
				}
				if ($passCountMarks){
					foreach($passCountMarks as $k=>$v){
						$countMarksHtml .= $ex_marks[$k]."(<font color='red'><b>$v</b></font>)；&nbsp;&nbsp;";
					}
				}
			//}
            // 当前登录用户是否已报名 
			$verifiedArr = array('1'=> '等待确认','2'=> '取消申请','3'=> '拒绝申请','4'=> '已确认','5'=> '下次参加');
            $ckIn  = $db->fetch_first("select * from {$tablepre}partyers where tid='$tid' and uid='$discuz_uid'");
            
			// 报名部分
            $query = $db->query("select p.*,m.gender from {$tablepre}partyers p left join {$tablepre}members m on m.uid=p.uid where p.tid='$tid' order by p.pid desc");
			while ($rs = $db->fetch_array($query)){
				$partyers[] = $rs;
			}
			$allPartyers_count = $passPartyers_count = $nopassPartyers_count = $allFolloweder = $passFolloweder = 0;
			if ($partyers){
				$i = $j = 0;
				foreach($partyers as $v){
					if ($v['verified']==4){
						$passList[] = $v;
						$i++;
						if ($party['followed']){
							$passFolloweder += $v['followed'];
						}
					}else{
						$nopassList[] = $v;
						$j++;
					}
					if ($party['followed']){
                        $allFolloweder += $v['followed'];
					}
				}
				$allPartyers_count = count($partyers);
				$passPartyers_count = $i;
				$nopassPartyers_count = $j;
			}
			// 性别判断
			$myGender = $db->fetch_first("select gender from {$tablepre}members where uid='$discuz_uid'");
			$myGender = $myGender['gender'];
			// 自定义报表单部分
			if ($sc_data['signform']==1){
				if ($sc_data['signfield']){
					$sc_data['signfield'] = sc_order_fields($sc_data['signfield']);
				}
			}
			// 自定义选项表单部分
			if ($sc_data['optionform']==1){
				if ($sc_data['optionfield']){
					if ($party['config']){
						$SF_CONFIG_VALUES = unserialize($party['config']);
					}
					$sc_data['optionfield'] = sc_order_fields($sc_data['optionfield']);
				}
			}
			// 判断登录会员是否有参加的机会
			$joinPerm = 0;
			if ($discuz_uid && $party['closed']==0){
				if ($party['number']==0){
					$joinPerm = 1;
				}else{
					if ($party['number'] > $passPartyers_count + $passFolloweder){
						if ($party['gender']){
							if ($myGender==$party['gender']){
								$joinPerm = 1;
							}
						}else{
							if ($sc_data['bgroup']){
								if (in_array($groupid,$sc_data['bgroup'])){
									$joinPerm = 1;
								}
							}else{
								$joinPerm = 1;
							}
						}
					}
				}
			}
			// 管理权限
			$mPerm = $joined = 0;
            if ($party['uid']==$discuz_uid || $adminid==1 || $adminid==2 || $adminid==3){
                $mPerm = 1;
            }
			if ($party['uid']==$discuz_uid){
				$mPerm = $joined = 1;
			}else{
                $ckPerm = $db->fetch_first("select verified,usertask from {$tablepre}partyers where tid='$tid' and uid='$discuz_uid'");
                //$ckConvenePerm = $db->fetch_first("select uid from {$tablepre}party where tid='$tid' and uid='$discuz_uid'");
				//if ($ckConvenePerm['uid']>0){
				//	$mPerm = 1;
				//}
				if ($ckPerm['verified']==4){
					$joined = 1;
				}
			}
		}
	}
}else{
	require_once "include/common.inc.php";
	$thisrc = "party.php";
	$thisnb = array(1);
	$goBack = "javascript:history.go(-1);";
	$goUrl = "viewthread.php?tid=$tid";

	require_once DISCUZ_ROOT."./forumdata/cache/cache_forums.php";
    if($tid > 0)
		$party = $db->fetch_first("select * from {$tablepre}party where tid='$tid'");
	if (!$act){
		sc_admin();
		$forums = $subforums = array();
		foreach($_DCACHE['forums'] as $forum) {
			if(sc_forumperm($forum['viewperm'])) {
				if($forum['type'] == 'group') {
					$categories[] = $forum;
				} else {
					$forum['type'] == 'sub' ? $subforums[$forum['fup']][] = $forum : $forums[$forum['fup']][] = $forum;
				}
			 }
		}
		include template("party_index");
	}elseif($act=='set'){
		sc_admin();
		$confile = DISCUZ_ROOT."./forumdata/party/con_{$fid}_fig.php";
		if (file_exists($confile)){
			$fp = fopen($confile, 'r');
			$condata .= @fread($fp, filesize($confile));
			fclose($fp);
			$condata = unserialize($condata);
			$condata['optionfield'] = sc_order_fields($condata['optionfield']);
			$condata['signfield'] = sc_order_fields($condata['signfield']);
		}
		if ($step=='post'){
			$party['optionfield'] = sc_ck_fields($party['optionfield']);
			$party['signfield'] = sc_ck_fields($party['signfield']);
			$party = serialize($party);
			if ($fp = @fopen($confile,'wb')){
				fwrite($fp,$party);
				fclose($fp);
				showmessage("设置成功","$thisrc?act=set&fid=$fid");
			}else{
				exit('Can not write to cache files, please check directory ./forumdata/ and ./forumdata/party/ .');
			}
		}
		$forumname = $_DCACHE['forums'][$fid]['name'];
		require_once DISCUZ_ROOT."./forumdata/cache/cache_usergroups.php";
		$party_body_display = $condata && $condata['allowed']==1 ? "" : "none" ;
		include template('party_set');
	}elseif($act=='check'){
		// 因为不想动太多文件，在论坛帖子删除的时候不会同步删除这个活动的数据，所以在这里写了段清除的代码
		sc_admin();
		if ($page<1 || !$page) $page = 1;
		$perpage = 1000;
		$start = ($page-1)*$perpage;
		$end   = $page*$perpage;
		$S = $fid ? "where fid='$fid'" : "";
		$U = $fid ? "&fid=$fid" : "";
		$count = $db->result_first("select * from {$tablepre}party {$S}");
		$maxPage = ceil($count/$perpage);
		if ($page>$maxPage){
			showmessage("ok","javascript:window.close();");
		}
		$query = $db->query("select * from {$tablepre}party {$S} limit $start,$perpage");
		while ($rs = $db->fetch_array($query)){
			$ck = $db->fetch_first("select tid from {$tablepre}threads where tid='$rs[tid]'");
			if (!$ck['tid']){
				$tids[] = $rs['tid'];
			}
		}
		if ($tids){
			$tids = implode(',',$tids);
			//$db->query("delete from {$tablepre}party where tid in ($tids)");
			//$db->query("delete from {$tablepre}partyers where tid in ($tids)");
			showmessage("成功清理留存的 {$start} ~ {$end} 条无用信息","party.php?act=check{$U}&page=".($page+1));
		}
		exit;
	}elseif ($act=='complete'){
		if ($fid && $tid){
            $sc_data = sc_perms($fid);
			if ($sc_data['toperm']==1){
				$tinfo = $db->fetch_first("select subject from {$tablepre}threads where tid='$tid'");
				$party = $db->fetch_first("select * from {$tablepre}party where tid='$tid'");
				$party['showtime'] = $party['showtime'] ? gmdate('Y-m-d H:i',$party['showtime']+3600*$_DSESSION['timeoffset']) : "";
				$party['starttimefrom'] = $party['starttimefrom'] ? gmdate('Y-m-d H:i',$party['starttimefrom']+3600*$_DSESSION['timeoffset']) : "";
				$party['starttimeto'] = $party['starttimeto'] ? gmdate('Y-m-d H:i',$party['starttimeto']+3600*$_DSESSION['timeoffset']) : "";
				$party['number'] = $party['number'] ? $party['number'] : 0;
				$party['doworker'] = $party['doworker'] ? $party['doworker'] : $sc_data['doworker'];
				if ($sc_data['optionform']==1){
					if ($party['config']){
						$SF_CONFIG_VALUES = unserialize($party['config']);
					}
					$sc_data['optionfield'] = sc_order_fields($sc_data['optionfield']);
                }
				include template('party_complete');
            }
		}
	}elseif($act=="post"){
		// 验证权限
		if ($fid && $fid>0){
			$sc_data = sc_perms($fid);
			if ($sc_data['toperm']==1){
				// 活动时间
				if ($showtime){
					$showtime = strtotime($showtime);
					if ($showtime <= $timestamp && $party['closed'] != '1'){
						showmessage("活动时间小于等于当前时间",$goBack);
					}
				}else{
					showmessage("请输入活动时间开始的时间",$goBack);
				}
				// 报名时间范围
				if ($starttimefrom && $starttimeto){
					$starttimefrom = strtotime($starttimefrom);
					$starttimeto = strtotime($starttimeto);
					if ($starttimefrom >= $starttimeto){
						showmessage("活动报名的截止时间小于等于起始时间",$goBack);
					}
					if ($starttimeto >= $showtime){
						showmessage("活动报名的截止时间大于等于活动的开始时间",$goBack);
					}
				}else{
					showmessage("请输入活动报名时间",$goBack);
				}
				// 活动类型
				if (empty($class)){
					showmessage("请先设置下活动的分类。",$goBack);
				}
				// 性别要求
				$gender = $gender ? intval($gender) : 0;
				// 需要人数
				$number = $number ? intval($number) : 0;
				// 联系电话
				if (empty($phone)){
					showmessage("请输入活动组织者的联系电话",$goBack);
				}
				// 分工设置
				if ($sc_data['dowork']==1){
					if (empty($doworker)){
						showmessage("请先设置活动的分工",$goBack);
					}
				}
				// 自定义部分
				if ($sc_data['optionform']==1){
					if ($SFDC){
						$sc_data['optionfield'] = sc_order_fields($sc_data['optionfield']);
						foreach($sc_data['optionfield'] as $v){
							if ($v['must']=='on'){
								if (empty($SFDC[$v['field']])){
									showmessage("{$v[name]} 不能为空",$goBack);
								}
							}
						}
						$SFDC = serialize($SFDC);
					}
				}
				// 写入数据库
				$count = $db->result_first("select count(*) from {$tablepre}party where tid='$tid'");
				if ($count > 0){
					$db->query("update {$tablepre}party set picurl='$picurl',showtime='$showtime',starttimefrom='$starttimefrom',starttimeto='$starttimeto',class='$class',gender='$gender',number='$number',phone='$phone',marks='$marks',config='$SFDC',followed='$followed',doworker='$doworker',isjoin='$isjoin' where tid='$tid'");
				}else{
					$db->query("insert into {$tablepre}party (tid,fid,uid,picurl,showtime,starttimefrom,starttimeto,class,gender,number,phone,marks,config,followed,doworker,isold,isjoin) values ('$tid','$fid','$discuz_uid','$picurl','$showtime','$starttimefrom','$starttimeto','$class','$gender','$number','$phone','$marks','$SFDC','$followed','$doworker','0','$isjoin')");
					$db->query("insert into {$tablepre}partyers (tid,uid,username,phone,verified,dateline,message,usertask) values ('$tid','$discuz_uid','$discuz_user','$phone','4','$timestamp','欢迎大家来参加活动','召集人')");
				}
				showmessage("已完善相关资料的设置","viewthread.php?tid=$tid");
			}else{
				showmessage("你没有召集活动的权限",$goBack);
			}
		}
	}elseif($act=='party_sign'){
		if ($discuz_uid){
			if ($signupsubmit!='同意以上声明并确认报名'){
				showmessage("非法操作，如有问题，请联系管理员",$goUrl);
			}
			if ($tid > 0 && $fid > 0){
				$party = $db->fetch_first("select * from {$tablepre}party where tid='$tid'");
				if (!$party){
					showmessage("非法链接，如有问题，请联系管理员","index.php");
				}
				$ckIn  = $db->fetch_first("select * from {$tablepre}partyers where tid='$tid' and uid='$discuz_uid'");
				if ($ckIn){
					showmessage("你已报名，不能再次报名，谢谢合作。",$goUrl);
				}
				// 联系电话
				if (!$usermobile){
					showmessage("请输入联系电话",$goBack);
				}
				// 个人说明
				if (empty($usrexplain)){
					showmessage("请输入个人参加活动的说明",$goBack);
                }
                // 分工
				$usertask = $party['uid'] == $discuz_uid ? "召集人" : $usertask;
                if (!empty($party['doworker']) && empty($usertask)){
					showmessage("请选择分工",$goBack);
                }
				// 备注
				if ($marks && !is_numeric($marks)){
					showmessage("请选择备注选项",$goBack);
				}
				// 随行人员
				if ($party['followed']==1){
					if (!is_numeric($followed)){
						showmessage("请输入随行人员的数量",$goBack);
					}
				}
				// 自定义报名表单
				if (isset($SFDC)){
					$sc_data = sc_perms($fid);
					$SF_CONFIG_VALUES = $SFDC;
					$sc_data['signfield'] = sc_order_fields($sc_data['signfield']);
					foreach($sc_data['signfield'] as $v){
						if ($v['must']=='on'){
							if (empty($SFDC[$v['field']])){
								showmessage("{$v[name]}不能为空",$goBack);
							}
						}
					}
					$SFDC = serialize($SFDC);
				}
				// 保存数据
				$db->query("insert into {$tablepre}partyers (tid,uid,username,phone,verified,dateline,message,marks,usertask,followed,config) values ('$tid','$discuz_uid','$discuz_user','$usermobile','1','$timestamp','$usrexplain','$marks','$usertask','$followed','$SFDC')");
				$thread = $db->fetch_first("select subject from {$tablepre}threads where tid='$tid'");
				sendpm($party[uid], "{$thread[subject]}的活动通知", "你的好友：{$discuz_user}，已报名“{$thread[subject]}”的活动，正在等待你的审核。", 0);
				showmessage("已申请成功，请等待确认。",$goUrl);
			}
			exit;
		}else{
			showmessage("请先登录，然后再报名。",$goUrl);
		}
	}elseif($act=='list'){
		$tpp = 10;
		$maxpages = 1000;
		$page = isset($page) ? max(1, intval($page)) : 1;
		$page = $maxpages && $page > $maxpages ? 1 : $page;
		$start_limit = ($page - 1) * $tpp;
		if ($step=='noPassUsers' || $step=='PassUsers'){
			if ($tid > 0 && is_numeric($tid)){
				$party = $db->fetch_first("select * from {$tablepre}party where tid='$tid'");
				if ($party){
					$mPerm = 0;
					$verifiedArr = array('1'=> '等待确认','2'=> '取消申请','3'=> '拒绝申请','4'=> '已确认','5'=> '下次参加');
					$partyMarks  = $party['marks'] ? explode('|',$party['marks']) : "";
					$sql = $allpage == 1 ? "" : " limit $start_limit,$tpp";
					$sfv = $step == 'PassUsers' ? '=' : '!=' ;
					$hdr = $step == 'PassUsers' ? '已通过' : '暂未通过';
					$div = $step == 'PassUsers' ? 'partyPassusers' : 'partyNopassusers' ;
					$count = $db->result_first("select count(*) from {$tablepre}partyers where verified{$sfv}'4' and tid='$tid'");
					$query = $db->query("select p.*,m.gender from {$tablepre}partyers p left join {$tablepre}members m on m.uid=p.uid where p.tid='$tid' and p.verified{$sfv}'4' order by p.pid desc {$sql}");
					while ($rs = $db->fetch_array($query)){
						$rs['d1'] = gmdate('Y-m-d',$rs['dateline']+3600*$_DSESSION['timeoffset']);
						$rs['d2'] = gmdate('H:i',$rs['dateline']+3600*$_DSESSION['timeoffset']);
						$rs['v1'] = $verifiedArr[$rs['verified']];
						$rs['m1'] = $party['marks'] ? $partyMarks[$rs['marks']] : "";
						$list[] = $rs;
					}
					$multipage = multi($count,$tpp,$page,"party.php?tid=$tid&act=list&step=$step",$maxpages);
					if ($party['uid']==$discuz_uid || $adminid==1 || $adminid==2){
						$mPerm = 1;
					}else{
                        $joinEr = $db->fetch_first("select usertask from {$tablepre}partyers where tid='$tid' and uid='$discuz_uid'");
                        //$ckConvenePerm = $db->fetch_first("select uid from {$tablepre}party where tid='$tid' and uid='$discuz_uid'");
				        //if ($ckConvenePerm['uid']>0){
				        //	$mPerm = 1;
				        //}
					}
					include template('header_ajax');
					include template('party_list');
					include template('footer_ajax');
				}
			}
		}
	}elseif($act=='party_invite'){
		$mPerm = 0;
		if ($discuz_uid){
			if ($tid > 0 && $fid > 0){
				$party = $db->fetch_first("select * from {$tablepre}party where tid='$tid' and fid='$fid'");
				$thread = $db->fetch_first("select subject from {$tablepre}threads where tid='$tid' and fid='$fid'");
				if ($party['uid']==$discuz_uid || $adminid==1 || $adminid==2){
					$mPerm = 1;
				}else{
                    $joinEr = $db->fetch_first("select usertask from {$tablepre}partyers where tid='$tid' and uid='$discuz_uid'");
                    //$ckConvenePerm = $db->fetch_first("select uid from {$tablepre}party where tid='$tid' and uid='$discuz_uid'");
				    //if ($ckConvenePerm['uid']>0){
				    //	$mPerm = 1;
				    //}
				}
			}
		}
		if ($mPerm == 1){
			if (empty($username)){
				showmessage("请填写你要邀请或是要设置分工的会员名称",$goBack);
			}
			if ($username==$discuz_user){
				showmessage("天呐，你怎么可以邀请你自己呢？",$goBack);
			}
			$ckUser = $db->fetch_first("select uid from {$tablepre}members where username='$username'");
			if (!$ckUser['uid']){
				showmessage("你邀请的好友不存在于这个论坛中，请确认是否存在。",$goBack);
			}
			$ckJoiner = $db->fetch_first("select uid from {$tablepre}partyers where username='$username' and tid='$tid'");
			if ($invitesubmit=='点击邀请好友'){
				if ($ckJoiner){
					showmessage("你邀请的好友已报名，你快去通过他/她吧。",$goBack);
				}
				if ($party['marks']){
					if (!is_numeric($marks)){
						showmessage("请为邀请的好友设置备注选项",$goBack);
					}
				}
				$db->query("insert into {$tablepre}partyers (tid,uid,username,phone,verified,dateline,message,marks,usertask,followed,config) values ('$tid','$ckUser[uid]','$username','召集人邀请','4','$timestamp','{$discuz_user}邀请','$marks','$usertask','$followed','')");
				sendpm($ckUser['uid'], "{$thread[subject]}的活动通知", "你的好友：{$discuz_user}，邀请你参加：“{$thread[subject]}”的活动。", 0);
				showmessage("已邀请成功",$goUrl);
			}elseif ($invitesubmit=='点击设置分工'){
				if (!$ckJoiner['uid']){
					showmessage("你的好友还没有报名此活动，不能进行分工设置，请先邀请。",$goBack);
				}
				$exWorkers = explode(',',$party['doworker']);
				if (!in_array($usertask,$exWorkers)){
					showmessage("你输入的分工错误，请按照范围填写。",$goBack);
				}
				$db->query("update {$tablepre}partyers set verified='4',usertask='$usertask' where tid='$tid' and uid='$ckUser[uid]' and username='$username'");
				sendpm($ckUser['uid'], "{$thread[subject]}的活动通知", "你在参加的：“{$thread[subject]}”的活动中已被召集人指定为：{$usertask}，这个角色。", 0);
				showmessage("已成功设置分工",$goUrl);
			}
		}
	}elseif($act=='onoff'){
		$party = $db->fetch_first("select uid,closed from {$tablepre}party where tid='$tid'");
		if ($party){
			if ($party['uid']==$discuz_uid || $adminid==1 || $adminid==2){
				$partyStatus = $party['closed'] == 1 ? 0 : 1;
				$db->query("update {$tablepre}party set closed='$partyStatus' where tid='$tid'");
				showmessage("活动状态设置成功",$goUrl);
			}
		}
	}elseif($act=='operate'){
		if (in_array($for,array('wait','reply','accept','nexttime','edit'))){
			if ($tid>0 && $pid>0 && $discuz_uid && $fid>0){
				$party = $db->fetch_first("select a.uid,a.starttimeto,a.marks,a.doworker,a.followed,a.closed,e.uid as joinUid,e.phone,e.message,e.marks as emarks,e.followed as efollowed,e.reply,e.config,e.usertask,t.subject from {$tablepre}party a left join {$tablepre}partyers e on e.tid=a.tid left join {$tablepre}threads t on t.tid=a.tid where a.tid='$tid' and e.pid='$pid'");
				if ($party){
					if ($party['closed']==1){
						showmessage("此活动已结束，不能进行相关的操作",$goUrl);
					}
					$ckPerms = $db->fetch_first("select * from {$tablepre}partyers where tid='$tid' and uid='$discuz_uid'");
                    $mPerm = 0;
                    //$ckConvenePerm = $db->fetch_first("select uid from {$tablepre}party where tid='$tid' and uid='$discuz_uid'");
				    //if ($ckConvenePerm['uid']>0){
					//   $mPerm = 1;
				    //}

					if ($discuz_uid==$party['uid'] || $adminid==1 || $adminid==2){
						$mPerm = 1;
                    }
					$sc_data = sc_perms($fid);
					if ($for=='nexttime'){
						if ($discuz_uid==$party['joinUid'] && $_POST['partysubmit']=='确认下次再参加'){
							if ($sc_data['limittime']){
								if ($party['starttimeto']-$timestamp<=60*$sc_data['limittime']){
									showmessage("你已超过活动允许自由退出的时间，要记得去哦。",$goUrl);
								}
							}
							if ($discuz_uid==$party['uid']){
								showmessage("你为此活动的召集人，不允许退出活动！",$goUrl);
							}
							if (empty($message)){
								showmessage("请输入为什么要退出的理由",$goBack);
							}
							sc_coins($discuz_uid,1);
							$db->query("update {$tablepre}partyers set verified='5',message='$message' where tid='$tid' and pid='$pid' and uid='$discuz_uid'");
							showmessage("你已失去此次活动的机会，请下次三思而后行。",$goUrl);
						}
						include template('party_nexttime');
					}elseif($for=='edit'){
						if ($discuz_uid==$party['joinUid']){
                			//$usertask = $party['uid'] == $discuz_uid ? "召集人" : $usertask;
							if ($_POST['partysubmit']=='确认编辑'){
								if (!$usermobile) showmessage("请输入联系电话",$goBack);
								if (empty($usrexplain)) showmessage("请输入个人参加活动的说明",$goBack);
                                if (!is_numeric($marks)) showmessage("请选择备注选项",$goBack);
                                if (!empty($party['doworker']) && empty($usertask)){
                					showmessage("请选择分工",$goBack);
                                }
								if ($party['followed']==1){
									if (!is_numeric($followed)){
										showmessage("请输入随行人员的数量",$goBack);
									}
								}
								if (isset($SFDC)){
									$SF_CONFIG_VALUES = $SFDC;
									$sc_data['signfield'] = sc_order_fields($sc_data['signfield']);
									foreach($sc_data['signfield'] as $v){
										if ($v['must']=='on'){
											if (empty($SFDC[$v['field']])){
												showmessage("{$v[name]}不能为空",$goBack);
											}
										}
									}
									$SFDC = serialize($SFDC);
								}
								$db->query("update {$tablepre}partyers set phone='$usermobile',message='$usrexplain',marks='$marks',usertask='$usertask',followed='$followed',config='$SFDC' where tid='$tid' and uid='$discuz_uid' and pid='$pid'");
								showmessage("成功编辑个人说明。",$goUrl);
							}
							if ($sc_data['limittime']){
								if ($party['starttimeto']-$timestamp<=60*$sc_data['limittime']){
									showmessage("你已超过活动允许自由退出的时间，不能再次编辑。",$goUrl);
								}
							}
							if ($sc_data['signform']==1){
								if ($sc_data['signfield']){
									$SF_CONFIG_VALUES = unserialize($party['config']);
									$sc_data['signfield'] = sc_order_fields($sc_data['signfield']);
								}
							}
							$ex_marks = $party['marks'] ? explode('|',$party['marks']) : "";
                            $ex_workers = empty($party['doworker']) ? "" : explode(',',$party['doworker']);
							include template('party_edit');
						}
					}else{
						if ($mPerm==1){
							if ($for=='reply'){
								if ($_POST['partysubmit']=='确认回复'){
									if (empty($reply)){
										showmessage("请输入要回复的内容","party.php?act=operate&for=reply&tid=$tid&pid=$pid");
									}
									$reply = "by {$discuz_user}：".$reply;
									$db->query("update {$tablepre}partyers set reply='$reply' where tid='$tid' and pid='$pid'");
									if ($discuz_uid!=$party['joinUid']){
										sendpm($party['joinUid'], "{$party[subject]}的活动通知", "你在参加的：“{$party[subject]}”的活动中，召集人回复了你的参加活动的个人说明。", 0);
									}
									showmessage("回复成功",$goUrl);
								}
								include template('party_reply');
							}elseif($for=='wait'){
								if ($mPerm==1){
									if ($ckPerms['pid']==$pid){
										showmessage("你为此活动的召集人，不允许退出活动！",$goUrl);
									}
								}
								sc_coins($party['joinUid'],2);
								$db->query("update {$tablepre}partyers set verified='1' where tid='$tid' and pid='$pid'");
								if ($discuz_uid!=$party['joinUid']){
									sendpm($party['joinUid'], "{$party[subject]}的活动通知", "你在参加的：“{$party[subject]}”的活动中，召集人取消了你的活动申请。", 0);
								}
								showmessage("成功的取消了此朋友的活动请求。",$goUrl);
							}elseif($for=='accept'){
								sc_coins($party['joinUid'],1);
								$db->query("update {$tablepre}partyers set verified='4' where tid='$tid' and pid='$pid'");
								if ($discuz_uid!=$party['joinUid']){
									sendpm($party['joinUid'], "{$party[subject]}的活动通知", "你在参加的：“{$party[subject]}”的活动中，召集人通过了你的活动申请。", 0);
								}
								showmessage("成功的通过了此朋友的活动请求。",$goUrl);
							}
						}
					}
				}
			}
		}
	}elseif($act=='download' || $act=='print'){
		if ($discuz_uid){
			if ($tid>0){
				$party = $db->fetch_first("select a.*,t.subject from {$tablepre}party a left join {$tablepre}threads t on t.tid=a.tid where t.tid='$tid'");
				if ($party){
					$mPerm = 0;
					if ($party['uid']==$discuz_uid || $adminid==1 || $adminid==2){
						$mPerm = 1;
					}else{
                        $ckPerm = $db->fetch_first("select usertask from {$tablepre}partyers where tid='$tid' and uid='$discuz_uid'");
                        //$ckConvenePerm = $db->fetch_first("select uid from {$tablepre}party where tid='$tid' and uid='$discuz_uid'");
			            //if ($ckConvenePerm['uid']>0){
			            //	$mPerm = 1;
			            //}
					}
					if ($mPerm = 1){
						$sc_data = sc_perms($fid);
						$Fstring = $party['followed']==1 ? "(随行人数)" : "" ;
						$filename = $party['subject'];
						$doc = array(
							0 => array("用户名{$Fstring}",'联系电话','E-Mail', '个人说明','备注选项','分工'),
						);
						if ($sc_data['signform']==1){
							$sc_data['signfield'] = sc_order_fields($sc_data['signfield']);
                            foreach($sc_data['signfield'] as $v){
								array_push($doc[0],$v['name']);
								$fields[] = $v['field'];
							}
							$count = count($sc_data['signfield']);
							$last = count($doc[0])+$count;
						}
						array_push($doc[0],'与会签名');
						if ($party['marks']){
							$exMarks = explode('|',$party['marks']);
						}
						$i=0;
						$query = $db->query("select t.*,m.gender, m.email from {$tablepre}partyers t left join {$tablepre}members m on m.uid=t.uid where t.tid='$tid' and t.verified='4' order by t.dateline desc");
						while ($rs = $db->fetch_array($query)){
							$i++;
							$ts[0]	= $rs['username'].($party['followed']==1 ? "(+$rs[followed])" : "");
							$ts[1]	= $rs['phone'];
							$ts[2]	= $rs['email'];
							$ts[3]	= $rs['message'];
							$ts[4]	= $exMarks[$rs['marks']];
							$ts[5]	= $rs['usertask'];
							$rs['config'] = unserialize($rs['config']);
							if ($fields){
								$j=5;
								foreach($fields as $v){
									$j++;
									$ts[$j] = $rs['config'][$v];
								}
							}
							if ($rs['gender']==2){
								$mm[] = $rs['gender'];
							}elseif($rs['gender']==1){
								$gg[] = $rs['gender'];
							}else{
								$bm[] = $rs['gender'];
							}
							$ts[$last] = "";
							$doc[$i] = $ts;
						}
						if ($act=='download'){
							$xls = new PartyExcel;
                            $xls->addArray($doc);
                            $xls->generateXML($filename);
							exit;
						}elseif($act=='print'){
							$joinCount = count($doc)-1;
							include template('party_print');
						}
					}
				}
			}
		}
	}elseif($act=='sms' || $act=='pm'){
		if ($tid>0){
			$party = $db->fetch_first("select a.*,t.subject from {$tablepre}party a left join {$tablepre}threads t on t.tid=a.tid where a.tid='$tid'");
			if ($party){
				$mPerm = 0;
				if ($party['uid']==$discuz_uid || $adminid==1 || $adminid==2){
					$mPerm = 1;
				}else{
                    $ckPerm = $db->fetch_first("select usertask from {$tablepre}partyers where tid='$tid' and uid='$discuz_uid'");
                    //$ckConvenePerm = $db->fetch_first("select uid from {$tablepre}party where tid='$tid' and uid='$discuz_uid'");
    				//if ($ckConvenePerm['uid']>0){
    				//	$mPerm = 1;
    				//}
				}
			}
			if ($mPerm == 1){
				if (in_array($for,array('passed','nopassed'))){
					if ($_POST['partysubmit']=='发送短信'){
						if (empty($content)){
							showmessage("请输入短信内容",$goBack);
						}
						if (empty($applePhones)){
							showmessage("请选择要发送的对象",$goBack);
						}
						foreach($applePhones as $v){
							if (is_numeric($v)){
								$len = strlen($v);
								if (in_array($len,array('7','11'))){
									$imPhones[] = $v;
								}
							}
							if ($discuz_uid!=$v){
								$msgto[] = $v;
							}
						}
						if ($act=='sms'){
							if ($imPhones){
								// 得到手机列表，格式是：13765532842,7648434,15839430231
								$imPhones = implode(',',$imPhones);
								// 一般 sms 平台里有相关的使用代码，把代码放在这里即可。
								showmessage("成功群发手机短信",$goUrl);
							}
						}elseif($act=='pm'){
							if ($msgto){
								foreach($msgto as $v){
									if (is_numeric($v)){
										sendpm($v, "{$party[subject]}的活动通知", $content, 0);
									}
								}
								showmessage("成功群发站内短信",$goUrl);
							}
						}
						exit;
					}
					$verifiedArr = array('1'=> '等待确认','2'=> '取消申请','3'=> '拒绝申请','4'=> '已确认','5'=> '下次参加');
					$sfv = $for == 'passed' ? '=' : '!=' ;
					$hdr = $for == 'passed' ? '已通过' : '暂未通过';
					$count = $db->result_first("select count(*) from {$tablepre}partyers where verified{$sfv}'4' and tid='$tid'");
					$query = $db->query("select p.*,m.gender from {$tablepre}partyers p left join {$tablepre}members m on m.uid=p.uid where p.tid='$tid' and p.verified{$sfv}'4' order by p.pid desc");
					while ($rs = $db->fetch_array($query)){
						$list[] = $rs;
					}
					include template('party_smsAndpm');
				}
			}
		}
	}elseif($act=='javascript'){
		/*
			调用格式	<script src="party.php?act=javascript&fid=1,2&tid=4,5&closed=2&num=10&len=50&start=0&tpl=party_js"></script>

			fid			版块fid的值，多个请用逗号隔开。
			tid			指定调用活动帖子tid的值，当存在这个值时，优先使用，上面的 fid 则无效。
			closed		当 closed == 2 时，调用所有活动，为 1 时调用已结束的活动，为 0 时调用正在进行中的活动。
			num			调用几条活动帖子
			len			活动标题的长度
			start		调用数据的起始位置
			tpl			调用的模板文件，不同的模板可以显示不同的效果。

			// 特别功能：这个可以当列表，在别的页面使用，可以使用分页功能，方便大家调用。
			// 特别说明：如果想调用活动下面的报名情况的话，可以看下活动内容页面里的报名人员的调用，是AJAX的，这里我就不写了。
		*/
		$H = 0;
		if ($tid){
			$S .= " and a.tid in ($tid)";
			$H = 1;
		}else{
			if ($fid){
				$S .= " and t.fid in ($fid)";
				$H = 1;
			}
		}
		if (in_array($closed,array('0','1'))){
			$S .= " and a.closed='$closed'";
		}
		if ($H==1 && $num > 0 && $tpl && is_numeric($start)){
			$party_xb = array('0'=>'(不限性别)','1'=>'(仅限于男性)','2'=>'(仅限于女性)');
			$query = $db->query("select a.*,t.fid as forumid,t.subject,t.views,t.replies from {$tablepre}party a left join {$tablepre}threads t on t.tid=a.tid where t.dateline>0 {$S} order by t.dateline desc limit $start,$num");
			while ($rs = $db->fetch_array($query)){
				$rs['title'] = $len ? cutstr($rs['subject'],$len,' ...') : $rs['subject'];
				$rs['countAll'] = $db->result_first("select count(*) from {$tablepre}partyers where tid='$rs[tid]'");
				$rs['countPass'] = $db->result_first("select count(*) from {$tablepre}partyers where tid='$rs[tid]' and verified='4'");
				$rs['countFollowedAll'] = $db->result_first("select sum(followed) from {$tablepre}partyers where tid='$rs[tid]'");
				$rs['countFollowedPass'] = $db->result_first("select sum(followed) from {$tablepre}partyers where tid='$rs[tid]' and verified='4'");
				$rs['showtime'] = $rs['showtime'] ? gmdate('Y-m-d H:i',$rs['showtime']+3600*$_DSESSION['timeoffset']) : "";
				$rs['starttimefrom'] = $rs['starttimefrom'] ? gmdate('Y-m-d H:i',$rs['starttimefrom']+3600*$_DSESSION['timeoffset']) : "";
				$rs['starttimeto'] = $rs['starttimeto'] ? gmdate('Y-m-d H:i',$rs['starttimeto']+3600*$_DSESSION['timeoffset']) : "";
				$rs['status'] = $rs['closed'] == 1 ? "活动已结束" : "活动正在进行中" ;
				$rs['xinbie'] = $party_xb[$rs['gender']];
				$rs['num'] = $rs['num'] == 0 ? "不限人数" : "{$rs[num]}";
				$list[] = $rs;
			}
			include template($tpl);
		}
    }elseif($act=='checkin'){
        // 签到统计
        if ($tid > 0 && is_numeric($tid)){
            $party = $db->fetch_first("select * from {$tablepre}party where tid='$tid'");
            if ($party && $discuz_uid > 0){
    	        $thread = $db->fetch_first("select subject from {$tablepre}threads where tid='$tid'");
                $party['subject'] = $thread['subject'];

                if($party['showtime'] > time())
                {
                    $notshow = true;
                }
                else{
                    $mPerm = 0;
			        $sc_data = sc_perms($fid);
			        if ($sc_data['toperm']==1){
				    	$mPerm = 1;
                    }
                    $ex_workers = empty($party['doworker']) ? array('') : array_merge(array('','召集人') , explode(',',$party['doworker']));
                    if ($step=='post'){
			            $sc_data = sc_perms($fid);
                        if ($sc_data['toperm']==1){
                            foreach($checkin as $uid => $ck)
                            {
			                    $db->query("update {$tablepre}partyers set usertask='{$usertask_list[$uid]}', checkin='$ck', updatetime='". time() ."' where uid='$uid' and tid='$tid'");
                            }
                            showmessage("设置成功","$thisrc?act=checkin&tid=$tid");
                        }
                    }
                    $checkAttr = array('0'=>'待确认', '1'=>'已参加', '2'=>'未参加');
                    $query = $db->query("select p.*,m.gender from {$tablepre}partyers p left join {$tablepre}members m on m.uid=p.uid where p.tid='$tid' and p.verified='4' order by p.checkin asc, p.pid desc");
                    $checkin_count_list = array('0'=>0, '1'=>0, '2'=>0);
                    $total_count = 0;
				    while ($rs = $db->fetch_array($query)){
				    	$rs['d1'] = gmdate('Y-m-d',$rs['dateline']+3600*$_DSESSION['timeoffset']);
				    	$rs['d2'] = gmdate('H:i',$rs['dateline']+3600*$_DSESSION['timeoffset']);
				    	$rs['v1'] = $verifiedArr[$rs['verified']];
                        $rs['m1'] = $party['marks'] ? $partyMarks[$rs['marks']] : "";
                        $rs['checkin_txt'] = $checkAttr[$rs['checkin']];
                        $rs['config'] = unserialize($rs['config']);
                        $checkin_count_list[$rs['checkin']] ++;
                        $total_count ++;
				    	$list[] = $rs;
				    }
                }
                if($step=='list' && $party['class'] == '阳光公益活动')
                {
                    $other_task = '其他分工';
                    $main_count = 0;
                    $assis_count = 0;
                    $audit_count = 0;
                    $sum_list = array($other_task=>array());
                    foreach($list as $key => $value)
                    {
                        if($value['checkin'] == 1)
                        {
                            if($value['usertask'] == '')
                            {
                                if(preg_match('/主讲/', $value['message']))
                                {
                                    $value['usertask'] = '主讲人';
                                }
                                else if(preg_match('/助教/', $value['message']))
                                {
                                    $value['usertask'] = '助教';
                                }
                                else if(preg_match('/旁听/', $value['message']))
                                {
                                    $value['usertask'] = '旁听';
                                }
                                else
                                {
                                    $value['usertask'] = '旁听';
                                }
                            }
                            if(is_array($value['config']['课程']))
                            {
                                foreach($value['config']['课程'] as $lesson)
                                {
                                    $sum_list[$lesson][$value['usertask']]['detail'][$value['uid']] = $value;
                                }
                            }
                            else
                            {
                                    $sum_list[$other_task][$other_task]['detail'][$value['uid']] = $value;
                            }
                        }
                    }
                    foreach($sum_list as $lesson => $value)
                    {
                        foreach($value as $usertask => $detail)
                        {
                            foreach($detail['detail'] as $user_id => $user_info)
                            {
                                $temp_sum[] = '<a href="space.php?uid=' . $user_info['uid'] . '" target="_blank">' . $user_info['username'] . '</a>';
                            }
                            $sum_list[$lesson][$usertask]['sum'] = join(', ', $temp_sum);
                            unset($temp_sum);
                        }
                    }
                    $temp = $sum_list[$other_task];
                    unset($sum_list[$other_task]);
                    $sum_list[$other_task] = $temp;
                    include template('party_checkin_list');
                }
                else
                {
                    include template('party_checkin');
                }
			}
        }
    }
    elseif($act=='relec'){
        $sc_data = sc_perms($fid);
	    if ($sc_data['toperm']==1){
	   	    $tinfo = $db->fetch_first("select subject from {$tablepre}threads where tid='$tid'");
            $party = $db->fetch_first("select * from {$tablepre}party where tid='$tid'");
            if ($step == 'post'){
                $ctinfo = $db->fetch_first("select subject from {$tablepre}threads where tid='$ctid'");
                if(empty($ctinfo))
                {
                    showmessage("帖子中没有与您输入ID相符的记录","$thisrc?act=relec&tid=$tid");
                }
                else
                {
			        $db->query("update {$tablepre}party set ctid='{$ctid}' where tid='{$tid}'");
				    showmessage("关联总结帖成功","$thisrc?act=relec&tid=$tid");
                }
            }
            if($party['ctid'] == 0)
            {
                 $rcinfo = array();
                 $ctitle_patten = $tinfo['subject'];
                 $ctitle_patten = str_replace('【活动召集】','',$ctitle_patten);
                 $ctitle_patten = str_replace('召集','',$ctitle_patten);
                 $ctitle_patten = trim($ctitle_patten);
                 $ctitle_patten = str_replace(' ', '', $ctitle_patten);
                 $rcinfo = $db->fetch_first("select tid, subject from {$tablepre}threads where subject like '%{$ctitle_patten}%' and subject like '%总结%'");
                 if(empty($rcinfo))
                 {
                     $location = explode('学校', $ctitle_patten);
                     if(count($location) == 2)
                     {
                        $rcinfo = $db->fetch_first("select tid, subject from {$tablepre}threads where subject like '%{$reg[1]}%' and subject like '%{$location[0]}%' and subject like '%总结%'");
                     }
                     else
                     {
                        $location = explode('社区', $ctitle_patten);
                        if(count($location) == 2)
                        {
                            $rcinfo = $db->fetch_first("select tid, subject from {$tablepre}threads where subject like '%{$reg[1]}%' and subject like '%{$location[0]}%' and subject like '%总结%'");
                        }
                     }
                 }
                 if(empty($rcinfo))
                 {
                     if(preg_match('/([\d-.]*)(.*)/', $ctitle_patten, $reg))
                     {
                         $reg[1] = trim($reg[1]);
                         $reg[2] = trim($reg[2]);
                         $rcinfo = $db->fetch_first("select tid, subject from {$tablepre}threads where subject like '%{$reg[1]}%' and subject like '%$reg[2]%' and subject like '%总结%'");
                    }
                 }
            }
            else
            {
	   	        $ctinfo = $db->fetch_first("select subject from {$tablepre}threads where tid='$party[ctid]'");
            }
            include template('party_relec');
        }
    }
}

// 函数部分
// 权限分析
function sc_perms($fid){
	global $groupid,$forum,$discuz_user,$adminid;

	$confile = DISCUZ_ROOT."./forumdata/party/con_{$fid}_fig.php";

	if (file_exists($confile)){
		$fp = fopen($confile, 'r');
		$condata .= @fread($fp, filesize($confile));
		fclose($fp);
		$condata = unserialize($condata);
		$toperm = 0;
		if ($condata['allowed']==1){
			if ($adminid==1 || $adminid==2){
				$toperm = 1;
			}else{
				switch($condata['perms']){
					case 'everyone':
						$toperm = 1;
					break;
					case 'group':
						$toperm = $condata['group'] && in_array($groupid,$condata['group']) ? 1 : 0;
					break;
					case 'moderator':
						$toperm = $forum['ismoderator'] == 1 ? 1 : 0;
					break;
					case 'member':
						$condata['member'] = explode(',',$condata['member']);
						$toperm = $condata['member'] && in_array($discuz_user,$condata['member']) ? 1 : 0;
					break;
				}
			}
		}
		if ($toperm==1){
			$condata['toperm'] = 1;
		}
		return $condata;
	}
}

// 论坛版块是否加密
function sc_forumperm($viewperm) {
	return (empty($viewperm) || ($viewperm && strstr($viewperm, "\t7\t")));
}

// 检查是否为活动管理员
function sc_admin(){
	global $discuz_uid,$thisnb,$adminid;

	if ($adminid!=1){
		if (!in_array($discuz_uid,$thisnb)){
			showmessage("你没有权限使用召集功能的后台","index.php");
		}
    }
    return true;
}

// 自定义部分队列
function sc_ck_fields($a){
	$b = $a['field'];
	foreach($b as $c=>$d){
		if ($d){
			$e['field'][]		= $d;
			$e['name'][]		= $a['name'][$c];
			$e['type'][]		= $a['type'][$c];
			$e['default'][]		= $a['default'][$c];
			$e['bold'][]		= $a['bold'][$c];
			$e['color'][]		= $a['color'][$c];
			$e['must'][]		= $a['must'][$c];
			$e['order'][]		= $a['order'][$c];
		}
	}
	return $e;
}

// 自定义部分排序
function sc_order_fields($a){
	global $SF_CONFIG_VALUES;
	$b = $a['field'];
	if ($b){
		foreach($b as $c=>$d){
			if ($d){
				$o = $a['order'][$c];
				$e[$o]['field']		= $d;
				$e[$o]['name']		= $a['name'][$c];
				$e[$o]['type']		= $a['type'][$c];
				$e[$o]['default']	= $a['default'][$c];
				$e[$o]['bold']		= $a['bold'][$c];
				$e[$o]['color']		= $a['color'][$c];
				$e[$o]['must']		= $a['must'][$c];
				$e[$o]['order']		= $a['order'][$c];
				$e[$o]['html']		= sc_form($d,$a['type'][$c],$a['default'][$c],$SF_CONFIG_VALUES[$d]);
				$e[$o]['value']		= is_array($SF_CONFIG_VALUES[$d]) ? implode(',',$SF_CONFIG_VALUES[$d]) : nl2br($SF_CONFIG_VALUES[$d]) ;
			}
		}
		ksort($e,SORT_NUMERIC);
	}
	return $e;
}

// 表单部分
function sc_form($field,$type,$default,$value){
	$html = '';
	$value = $value ? $value : $default;
	switch ($type){
		case 'text':
			$html = "<input name='SFDC[$field]' class='txt' type='text' value=\"$value\"/>";
		break;
		case 'textarea':
			$html = "<textarea name='SFDC[$field]' rows='6' cols='80'>$value</textarea>";
		break;
		case 'radio':
			if (strstr($default,'|')){
				$list = explode("|",$default);
				foreach($list as $v){
					$checked = $v==$value ? "checked" : "";
					$html .= "<input type='radio' name='SFDC[$field]' $checked value='$v'/> $v&nbsp;";
				}
			}else{
				$html = "<font color=red>请重新设置，多个请用 | 分开。</font>";
			}
		break;
		case 'checkbox':
			if (strstr($default,"|")){
				$list = explode("|",$default);
				foreach($list as $v){
					$checked = is_array($value) && in_array($v,$value) ? "checked" : "";
					$html .= "<input type='checkbox' name='SFDC[$field][]' $checked value='$v'/> $v&nbsp;";
				}
			}else{
				$html = "<font color=red>请重新设置，多个请用 | 分开。</font>";
			}
		break;
	}
	return $html;
}

// excel 用于下载打印活动名单
class PartyExcel{
	var $header = "<?xml version=\"1.0\" encoding=\"utf-8\"?\>
<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"
 xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
 xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"
 xmlns:html=\"http://www.w3.org/TR/REC-html40\">";
	var $footer = "</Workbook>";
	var $lines = array ();
	var $worksheet_title = "Table1";

	function addRow($array){
		$cells = '';
		foreach($array as $k=>$v){
			if (is_numeric($v)){
				if (substr($v,0,1) == 0){
					$cells .= "<Cell><Data ss:Type=\"String\">$v</Data></Cell>\n";
				}else{
					$cells .= "<Cell><Data ss:Type=\"Number\">$v</Data></Cell>\n";
				}
			}else{
				$cells .= "<Cell><Data ss:Type=\"String\">$v</Data></Cell>\n";
			}
		}
		$this->lines[] = "<Row>\n{$cells}</Row>\n";
	}

	function addArray($array){
		foreach($array as $k=>$v){
			$this->addRow($v);
		}
	}

    function setWorksheetTitle ($title) {
        $title = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $title);
        $title = substr ($title, 0, 31);
        $this->worksheet_title = $title;
    }

    function generateXML ($filename) {
        
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        $encoded_filename = urlencode($filename);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
        	header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
        } else if (preg_match("/Firefox/", $ua)) {
        	header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '.xls"');
        } else {
        	header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        }

        //header("Content-Disposition: attachment; filename=\"" . $filename . ".xls\"");
        echo stripslashes ($this->header);
        echo "\n<Worksheet ss:Name=\"" . $this->worksheet_title . "\">\n<Table>\n";
        echo "<Column ss:Index=\"1\" ss:AutoFitWidth=\"0\" ss:Width=\"110\"/>\n";
        echo implode ("\n", $this->lines);
        echo "</Table>\n</Worksheet>\n";
        echo $this->footer;
    }
}

function sc_coins($uid,$a=1){ // 1 +, 2 -
	global $sc_data,$db,$tablepre;

	if ($sc_data['coins']){
		foreach($sc_data['coins'] as $k=>$v){
			if ($v!=0){
				if (strstr($v,'-')){
					$v = intval($v);
					$b = $a == 1 ? "-" : "+";
					$coins[] = "extcredits{$k}=extcredits{$k}{$b}{$v}";
				}else{
					$v = intval($v);
					$b = $a == 1 ? "+" : "-";
					$coins[] = "extcredits{$k}=extcredits{$k}{$b}{$v}";
				}
			}
		}
		if ($coins){
			$coins = implode(',',$coins);
			$db->query("update {$tablepre}members set $coins where uid='$uid'");
		}
	}
}

?>
