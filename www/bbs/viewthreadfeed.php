<?php
/*
  Copyright 2008 Google Inc.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
 */

/**
 * The page to generate the feed for photo viewer.
 */

if (!defined('CURSCRIPT')) {
    define('CURSCRIPT', 'viewthreadfeed');
}
define('SQL_ADD_THREAD', ' t.dateline, t.special, t.lastpost AS lastthreadpost,');
define('CONTENT_TYPE', 'Content-Type: application/rss+xml; charset=UTF-8');
require_once './include/common.inc.php';
require_once DISCUZ_ROOT.'./include/forum.func.php';

$page = max($page, 1);

require_once DISCUZ_ROOT.'./include/discuzcode.func.php';

$start_timestamp = date("YmdHis").substr(microtime(), 2, 3);

$discuz_action = 3;

$query = $db->query("SELECT * FROM {$tablepre}threads t WHERE tid='$tid' AND displayorder>='0'");

$thread = $db->fetch_array($query);

if (!$thread) {
    exit('No thread found.');
}

if (empty($forum['allowview'])) {
    if (!$forum['viewperm'] && !$readaccess) {
        exit('No permission.');
    } elseif($forum['viewperm'] && !forumperm($forum['viewperm'])) {
        exit('No permission.');
    }
}

if ($forum['password'] && $forum['password'] != $_DCOOKIE['fidpw'.$fid]) {
    exit('No permission');
}

if ($thread['readperm'] && $thread['readperm'] > $readaccess && !$forum['ismoderator'] &&
    $thread['authorid'] != $discuz_uid) {
    exit('No permission');
}

if ($thread['price'] > 0 && $thread['special'] == 0) {
    if ($maxchargespan && $timestamp - $thread['dateline'] >= $maxchargespan * 3600) {
        $db->query("UPDATE {$tablepre}threads SET price='0' WHERE tid='$tid'");
        $thread['price'] = 0;
    } else {
        if (!$discuz_uid) {
            exit('No permission.');
        } elseif (!$forum['ismoderator'] && $thread['authorid'] != $discuz_uid) {
            $query = $db->query("SELECT tid FROM {$tablepre}paymentlog WHERE tid='$tid' AND uid='$discuz_uid'");
            if (!$db->num_rows($query)) {
                // Note: threadpay.inc.php will check this condition, but it exits using "showmessage".  We do the check before
                // the inclusion and exit with "exit".
                if (!isset($extcredits[$creditstrans])) {
                    exit('credits_transaction_disabled');
                }
                require_once DISCUZ_ROOT.'./include/threadpay.inc.php';
                $threadpay = TRUE;
            }
        }
    }
}

$forum['modrecommend'] = $forum['modrecommend'] ? unserialize($forum['modrecommend']) : array();
$raterange = $modratelimit && $adminid == 3 && !$forum['ismoderator'] ? array() : $raterange;
$extra = rawurlencode($extra);

$allowgetattach = !empty($forum['allowgetattach']) || ($allowgetattach && !$forum['getattachperm']) || forumperm($forum['getattachperm']);

$postlist = $attachtags = $attachlist = array();
$attachpids = $announcepm = 0;

if ($tid) {

    $thisgid = $forum['type'] == 'forum' ? $forum['fup'] : $_DCACHE['forums'][$forum['fup']]['fup'];

    $pmlist = array();
    if ($_DCACHE['pmlist']) {
        $readapmids = !empty($_DCOOKIE['readapmid']) ? explode('D', $_DCOOKIE['readapmid']) : array();
        foreach ($_DCACHE['pmlist'] as $pm) {
            if ($discuz_uid && (empty($pm['groups']) || in_array($groupid, $pm['groups']))) {
                if (!in_array($pm['pmid'], $readapmids)) {
                    $pm['announce'] = TRUE;
                    $pmlist[] = $pm;
                    $announcepm++;
                }
            }
        }
    }

    $supe_pushstatusadd = '';
    if ($supe['status'] && $supe_allowpushthread && $forum['supe_pushsetting']['status'] == 3) {
        if (($thread['views'] && $forum['supe_pushsetting']['filter']['views'] && $thread['views'] >= intval($forum['supe_pushsetting']['filter']['views'])) ||
            ($thread['replies'] && $forum['supe_pushsetting']['filter']['replies'] && $thread['replies'] >= intval($forum['supe_pushsetting']['filter']['replies'])) ||
            ($thread['digest'] && $forum['supe_pushsetting']['filter']['digest'] && $thread['digest'] >= intval($forum['supe_pushsetting']['filter']['digest'])) ||
            ($thread['displayorder'] && $forum['supe_pushsetting']['filter']['displayorder'] && $thread['displayorder'] >= intval($forum['supe_pushsetting']['filter']['displayorder']))) {
            if ($thread['supe_pushstatus'] == 0) {
                $supe_pushstatusadd = ", supe_pushstatus='3'";
            }
        } elseif ($thread['supe_pushstatus'] == 3) {
            $supe_pushstatusadd = ", supe_pushstatus='0'";
        }
    }

    viewthreadfeed_updateviews();
    @extract($_DCACHE['custominfo']);

    $onlyauthoradd = '';
    $authorid = intval($authorid);
    if ($authorid) {
        $query = $db->query("SELECT COUNT(*) FROM {$tablepre}posts WHERE tid='$tid' AND invisible='0' AND authorid='$authorid'");
        $thread['replies'] = $db->result($query, 0) - 1;
        if ($thread['replies'] < 0) {
            exit('Undefined action');
        }
        $onlyauthoradd = "AND p.authorid='$authorid'";
    };

    // $ppp - threads per page
    $ppp = $forum['threadcaches'] && !$discuz_uid ? $_DCACHE['settings']['postperpage'] : $ppp;
    $totalpage = ceil(($thread['replies'] + 1) / $ppp);
    if ($page > $totalpage) {
        $page = $totalpage;
    }
    $pagebydesc = $page > 50 && $page > ($totalpage / 2) ? TRUE : FALSE;

    if ($pagebydesc) {
        $firstpagesize = ($thread['replies'] + 1) % $ppp;
        $ppp2 = $page == $totalpage && $firstpagesize ? $firstpagesize : $ppp;
        $realpage = $totalpage - $page + 1;
        $start_limit = max(0, ($realpage - 2) * $ppp + $firstpagesize);
        $numpost = ($page - 1) * $ppp;
        $pageadd =  "ORDER BY dateline DESC LIMIT $start_limit, $ppp2";
    } else {
        $start_limit = $numpost = ($page - 1) * $ppp;
        if($start_limit > $thread['replies']) {
            $start_limit = $numpost = 0;
            $page = 1;
        }
        $pageadd =  "ORDER BY dateline LIMIT $start_limit, $ppp";
    }

    $query = $db->query("SELECT p.*, m.username
  FROM {$tablepre}posts p
  LEFT JOIN {$tablepre}members m ON m.uid=p.authorid
  WHERE p.tid='$tid' AND p.invisible='0' $onlyauthoradd $pageadd");

    while ($post = $db->fetch_array($query)) {
        $postlist[$post['pid']] = viewthreadfeed_procpost($post);
    }
    if ($pagebydesc) {
        $postlist = array_reverse($postlist, TRUE);
    }

    if($attachpids) {
        require_once DISCUZ_ROOT.'./include/attachment.func.php';
        parseattach($attachpids, $attachtags, $postlist, $showimages);
    }

    if (empty($postlist)) {
        exit('Undefined Action');
    } else {
        $seodescription = current($postlist);
        $seodescription = cutstr(htmlspecialchars(strip_tags($seodescription['message'])), 150);
    }
    $pages = list_pages($thread['replies'] + 1, $ppp, $page, $boardurl."viewthreadfeed.php?tid=$tid");

    $last_post = date('r', $thread['lastpost']);
    $build_date = date('r');
    $viewthread_link = $boardurl."viewthread.php?tid=$tid&page=$page";

    // The feed is a rss+xml.
    header(CONTENT_TYPE);

    $rsscontent=
'<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:forum="http://schemas.google.com/photos/forum/2008">
  <channel>
    <title>'.dhtmlspecialchars(iconv($charset, 'utf-8', $thread[subject])).'</title>
    <link>'.dhtmlspecialchars(sanitizeUrl($viewthread_link)).'</link>
    <description></description>
    <pubDate>'.$last_post.'</pubDate>
    <lastBuildDate>'.$build_date.'</lastBuildDate>';

    // Generate pagination tags.
    $rsscontent.= '
    <forum:pagination>';
    $rsscontent.= '<forum:current page="'.$page.'">'.$pages[$page].'</forum:current>';
    if ($page > 1) {
        $prev_page = $page - 1;
        $rsscontent.= '<forum:previous page="'.$prev_page.'">'.$pages[$prev_page].'</forum:previous>';
    }
    if (isset($pages[$page + 1])) {
        $next_page = $page + 1;
        $rsscontent.= '<forum:next page="'.$next_page.'">'.$pages[$next_page].'</forum:next>';
    }
    $rsscontent.= '</forum:pagination>';
    
    foreach ($postlist as $post) {
        $rsscontent.= '
    <item>
      <title>'.dhtmlspecialchars(iconv($charset, 'utf-8', $thread['subject'])).'</title>
      <link></link>
      <description></description>
      <author>'.dhtmlspecialchars(iconv($charset, 'utf-8', $post['author'])).'</author>
      <pubDate>'.$post['date'].'</pubDate>
      <guid></guid>
      <forum:post>';
        foreach ($post['feedmessage'] as $message) {
            if ($message['type'] == 'text') {
                $rsscontent.= '<forum:paragraph>'.dhtmlspecialchars(iconv($charset, 'utf-8', $message['value'])).'</forum:paragraph>';
            } else if ($message['type'] == 'image') {
                $rsscontent.= '<forum:image>'.dhtmlspecialchars(sanitizeUrl($message['value'])).'</forum:image>';
            }
        }
        // Generate image from the post's attachment.
        foreach ($post['attachments'] as $attach) {
            if (!$attach['unpayed'] && $attach['attachimg']) {
                $attachimg = viewthreadfeed_absolute_attach_url($attach['url'])
                . $attach['attachment'];
                $rsscontent.= '<forum:image>'.dhtmlspecialchars($attachimg).'</forum:image>';
            }
        }
        $rsscontent.= '
      </forum:post>
    </item>';
    }
    $rsscontent.= '
  </channel>
</rss>';   	
    echo $rsscontent;
} else {
    exit('Invalid Argument');
}

function viewthreadfeed_updateviews() {
    global $delayviewcount, $supe_pushstatusadd, $timestamp, $tablepre, $tid, $db, $adminid;

    if(($delayviewcount == 1 || $delayviewcount == 3) && !$supe_pushstatusadd) {
        $logfile = './forumdata/cache/cache_threadviews.log';
        if(substr($timestamp, -2) == '00') {
            require_once DISCUZ_ROOT.'./include/misc.func.php';
            updateviews('threads', 'tid', 'views', $logfile);
        }
        if(@$fp = fopen(DISCUZ_ROOT.$logfile, 'a')) {
            fwrite($fp, "$tid\n");
            fclose($fp);
        } elseif($adminid == 1) {
            die('view_log_invalid');
        }
    } else {
        $db->query("UPDATE LOW_PRIORITY {$tablepre}threads SET views=views+1 $supe_pushstatusadd WHERE tid='$tid'", 'UNBUFFERED');
    }
    unset($supe_pushstatusadd);
}

function viewthreadfeed_procpost($post, $special = 0) {
    global $_DCACHE, $newpostanchor, $numpost, $thisbg, $postcount, $ratelogpids, $onlineauthors, $lastvisit, $thread,
    $attachpids, $attachtags, $forum, $dateformat, $timeformat, $timeoffset, $userstatusby, $allowgetattach,
    $allowpaytoauthor, $ratelogrecord, $showimages, $forum, $discuz_uid, $showavatars, $pagebydesc, $ppp2,
    $firstpid, $videoopen;

    $post['count'] = $postcount++;

    if ($pagebydesc) {
        $post['number'] = $numpost + $ppp2--;
    } else {
        $post['number'] = ++$numpost;
    }

    $post['dbdateline'] = $post['dateline'];
    $post['date'] = date('r', $post['dateline']);
    $post['dateline'] = gmdate("$dateformat $timeformat", $post['dateline'] + $timeoffset * 3600);
    $post['groupid'] = $_DCACHE['usergroups'][$post['groupid']] ? $post['groupid'] : 7;

    if ($post['username']) {
        $onlineauthors[] = $post['authorid'];
        $post['usernameenc'] = rawurlencode($post['username']);
        !$special && $post['groupid'] = getgroupid($post['authorid'], $_DCACHE['usergroups'][$post['groupid']], $post);
        $post['readaccess'] = $_DCACHE['usergroups'][$post['groupid']]['readaccess'];
        if ($userstatusby == 1 || $_DCACHE['usergroups'][$post['groupid']]['byrank'] == 0) {
            $post['authortitle'] = $_DCACHE['usergroups'][$post['groupid']]['grouptitle'];
            $post['stars'] = $_DCACHE['usergroups'][$post['groupid']]['stars'];
        }  elseif ($userstatusby == 2) {
            foreach ($_DCACHE['ranks'] as $rank) {
                if ($post['posts'] > $rank['postshigher']) {
                    $post['authortitle'] = $rank['ranktitle'];
                    $post['stars'] = $rank['stars'];
                    break;
                }
            }
        }

    } else {
        if (!$post['authorid']) {
            $post['useip'] = substr($post['useip'], 0, strrpos($post['useip'], '.')).'.x';
        }
    }
    $post['attachments'] = array();
    if ($post['attachment']) {
        if ($allowgetattach) {
            $attachpids .= ",$post[pid]";
            $post['attachment'] = 0;
            if (preg_match_all("/\[attach\](\d+)\[\/attach\]/i", $post['message'], $matchaids)) {
                $attachtags[$post['pid']] = $matchaids[1];
            }
        } else {
            $post['message'] = preg_replace("/\[attach\](\d+)\[\/attach\]/i", '', $post['message']);
        }
    }

    $ratelogpids .= ($ratelogrecord && $post['ratetimes']) ? ','.$post['pid'] : '';
    $forum['allowbbcode'] = $forum['allowbbcode'] ? ($_DCACHE['usergroups'][$post['groupid']]['allowcusbbcode'] ? 2 : 1) : 0;
    $post['ratings'] = karmaimg($post['rate'], $post['ratetimes']);
    $post['rawmessage'] = $post['message'];
    $post['feedmessage'] = viewthreadfeed_format_message($post['rawmessage'], $post['bbcodeoff']);

    $post['signature'] = $post['usesig'] ? $post['signature'] : '';
    $post['first'] && $firstpid = $post['pid'];
    return $post;
}


function viewthreadfeed_format_message($message, $bbcodeoff) {
    if (empty($bbcodeoff)) {
        $message = viewthreadfeed_stripbbcode($message);
    }
    $ret = array();
    $offset = 0;
    $imgtag = viewthreadfeed_bbcode_regex('img');
    while (preg_match($imgtag, $message, $matches, PREG_OFFSET_CAPTURE, $offset)) {
        // Text before [img] tag.
        if ($offset < $matches[0][1]) {
            $ret[] = array(
        'value' => trim(substr($message, $offset, $matches[0][1] - $offset)),
        'type' => 'text');
        }
        // The [img] url.
        $ret[] = array(
      'value' => trim($matches[2][0]),
      'type' => 'image');
        $offset = $matches[0][1] + strlen($matches[0][0]);
    }
    // Text after [img] tag.
    if ($offset < strlen($message)) {
        $ret[] = array(
      'value' => substr($message, $offset),
      'type' => 'text');
    }
    return $ret;
}

/**
 * Removes the bbcodes inside the message.
 * @staticvar array $viewthreadfeed_discuzcodes is an array containing the regex of bbcode tags to strip.
 * @param string $message the message to strip.
 * @return string the message with bbcode stripped.
 * @access private
 */
function viewthreadfeed_stripbbcode($message) {
    static $viewthreadfeed_discuzcodes;
    if (empty($viewthreadfeed_discuzcodes)) {
        // The regex for the bbcode block to be removed.
        $viewthreadfeed_discuzcodes['BlockSearchArray'] = array(
        viewthreadfeed_bbcode_regex('quote'),
        viewthreadfeed_bbcode_regex('hide'),
        viewthreadfeed_bbcode_regex('media'),
        viewthreadfeed_bbcode_regex('flash'),
        viewthreadfeed_bbcode_regex('attach'),
        );
        // The regex for the bbcode to be removed
        $viewthreadfeed_discuzcodes['RemoveSearchArray'] = array(
        viewthreadfeed_begin_bbcode_regex('b'), viewthreadfeed_end_bbcode_regex('b'),
        viewthreadfeed_begin_bbcode_regex('i'), viewthreadfeed_end_bbcode_regex('i'),
        viewthreadfeed_begin_bbcode_regex('u'), viewthreadfeed_end_bbcode_regex('u'),
        viewthreadfeed_begin_bbcode_regex('color'), viewthreadfeed_end_bbcode_regex('color'),
        viewthreadfeed_begin_bbcode_regex('font'), viewthreadfeed_end_bbcode_regex('font'),
        viewthreadfeed_begin_bbcode_regex('align'), viewthreadfeed_end_bbcode_regex('align'),
        viewthreadfeed_begin_bbcode_regex('url'), viewthreadfeed_end_bbcode_regex('url'),
        viewthreadfeed_begin_bbcode_regex('email'), viewthreadfeed_end_bbcode_regex('email'),
        viewthreadfeed_begin_bbcode_regex('code'), viewthreadfeed_end_bbcode_regex('code'),
        viewthreadfeed_begin_bbcode_regex('list'), viewthreadfeed_end_bbcode_regex('list'),
        viewthreadfeed_begin_bbcode_regex('qq'), viewthreadfeed_end_bbcode_regex('qq'),
        viewthreadfeed_begin_bbcode_regex('sup'), viewthreadfeed_end_bbcode_regex('sup'),
        viewthreadfeed_begin_bbcode_regex('sub'), viewthreadfeed_end_bbcode_regex('sub'),
        viewthreadfeed_begin_bbcode_regex('size'), viewthreadfeed_end_bbcode_regex('size'),
        );
    }

    $message = preg_replace($viewthreadfeed_discuzcodes['BlockSearchArray'], "\n", $message);
    $message = preg_replace($viewthreadfeed_discuzcodes['RemoveSearchArray'], '', $message);
    return $message;
}

function viewthreadfeed_begin_bbcode_regex($code) {
    return "/\[$code(=[^\]]*)?\]/is";
}

function viewthreadfeed_end_bbcode_regex($code) {
    return "/\[\/$code\]/is";
}

function viewthreadfeed_bbcode_regex($code) {
    return "/\s*\[$code(=[^\]]*)?\](.*?)\[\/$code\]\s*/is";
}

function sanitizeUrl($url) {
    static $PROTOCOL_WHITELIST = array('http', 'https');
    static $QUOTED_STRING = '/("|\')(.*?)("|\')/';
    $url = preg_replace($QUOTED_STRING, '$2', $url);

    $protocol = parse_url($url, PHP_URL_SCHEME);
    $protocol = strtolower($protocol);

    if (!$protocol) {
        return 'http://'.$url;
    }

    if (in_array($protocol, $PROTOCOL_WHITELIST)) {
        return $url;
    }
    return '';
}

function list_pages($num, $perpage, $curpage, $mpurl) {
    $pages = array();
    $num_pages = @ceil($num / $perpage);
    $mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
    for ($i = 1; $i <= $num_pages; ++$i) {
        $pages[$i] = $mpurl . "page=$i";
    }
    return $pages;
}

function viewthreadfeed_absolute_attach_url($attachurl) {
    global $boardurl;
    if (preg_match("/^((https?|ftps?):\/\/|www\.)/i", $attachurl)) {
        $ret = $attachurl;
    } else {
        $ret = $boardurl.$attachurl;
    }
    if ($ret[strlen($ret) - 1] != '/') {
        $ret .= '/';
    }
    return $ret;
}
