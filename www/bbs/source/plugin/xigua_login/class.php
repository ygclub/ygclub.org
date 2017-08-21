<?php
/**
 * Created by PhpStorm.
 * User: yzg
 * Date: 2017/4/13
 * Time: 15:44
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class uploadUcAvatar1 {

    /**
     * 上传至uc头像
     */
    public static function upload($uid, $localFile) {

        global $_G;
        if(!$uid || !$localFile) {
            return false;
        }

        list($width, $height, $type, $attr) = getimagesize($localFile);
        if(!$width) {
            return false;
        }

        if($width < 10 || $height < 10 || $type == 4) {
            return false;
        }

        $imageType = array(1 => '.gif', 2 => '.jpg', 3 => '.png');
        $fileType = $imageType[$type];
        if(!$fileType) {
            $fileType = '.jpg';
        }
        $avatarPath = $_G['setting']['attachdir'];
        $tmpAvatar = $avatarPath.'./temp/upload'.$uid.$fileType;
        file_exists($tmpAvatar) && @unlink($tmpAvatar);
        file_put_contents($tmpAvatar, file_get_contents($localFile));

        if(!is_file($tmpAvatar)) {
            return false;
        }

        $tmpAvatarBig = './temp/upload'.$uid.'big'.$fileType;
        $tmpAvatarMiddle = './temp/upload'.$uid.'middle'.$fileType;
        $tmpAvatarSmall = './temp/upload'.$uid.'small'.$fileType;

        $image = new image;
        if($image->Thumb($tmpAvatar, $tmpAvatarBig, 200, 250, 1) <= 0) {
            $tmpAvatarBig = str_replace($avatarPath, '', $tmpAvatar);
        }
        if($image->Thumb($tmpAvatar, $tmpAvatarMiddle, 120, 120, 1) <= 0) {
            $tmpAvatarMiddle = str_replace($avatarPath, '', $tmpAvatar);
        }
        if($image->Thumb($tmpAvatar, $tmpAvatarSmall, 48, 48, 2) <= 0) {
            $tmpAvatarSmall = str_replace($avatarPath, '', $tmpAvatar);
        }

        $tmpAvatarBig = $avatarPath.$tmpAvatarBig;
        $tmpAvatarMiddle = $avatarPath.$tmpAvatarMiddle;
        $tmpAvatarSmall = $avatarPath.$tmpAvatarSmall;

        $avatar1 = self::byte2hex(file_get_contents($tmpAvatarBig));
        $avatar2 = self::byte2hex(file_get_contents($tmpAvatarMiddle));
        $avatar3 = self::byte2hex(file_get_contents($tmpAvatarSmall));

        $extra = '&avatar1='.$avatar1.'&avatar2='.$avatar2.'&avatar3='.$avatar3;
        $result = self::uc_api_post_ex('user', 'rectavatar', array('uid' => $uid), $extra);

        if(!$avatar3 || !$result){
            $avatartype = '';
            $bigavatarfile = DISCUZ_ROOT.'./uc_server/data/avatar/'.self::get_avatar($uid, 'big', $avatartype);
            $middleavatarfile = DISCUZ_ROOT.'./uc_server/data/avatar/'.self::get_avatar($uid, 'middle', $avatartype);
            $smallavatarfile = DISCUZ_ROOT.'./uc_server/data/avatar/'.self::get_avatar($uid, 'small', $avatartype);
            file_put_contents($bigavatarfile, file_get_contents($tmpAvatarBig));
            file_put_contents($middleavatarfile, file_get_contents($tmpAvatarMiddle));
            file_put_contents($smallavatarfile, file_get_contents($tmpAvatarSmall));

        }


        @unlink($tmpAvatar);
        @unlink($tmpAvatarBig);
        @unlink($tmpAvatarMiddle);
        @unlink($tmpAvatarSmall);

        return true;
    }
    function get_avatar($uid, $size = 'big', $type = '') {
        $size = in_array($size, array('big', 'middle', 'small')) ? $size : 'big';
        $uid = abs(intval($uid));
        $uid = sprintf("%09d", $uid);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $typeadd = $type == 'real' ? '_real' : '';
        return  $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
    }
    public static function byte2hex($string) {
        $buffer = '';
        $value = unpack('H*', $string);
        $value = str_split($value[1], 2);
        $b = '';
        foreach($value as $k => $v) {
            $b .= strtoupper($v);
        }

        return $b;
    }

    public static function uc_api_post_ex($module, $action, $arg = array(), $extra = '') {
        $s = $sep = '';
        foreach($arg as $k => $v) {
            $k = urlencode($k);
            if(is_array($v)) {
                $s2 = $sep2 = '';
                foreach($v as $k2 => $v2) {
                    $k2 = urlencode($k2);
                    $s2 .= "$sep2{$k}[$k2]=".urlencode(uc_stripslashes($v2));
                    $sep2 = '&';
                }
                $s .= $sep.$s2;
            } else {
                $s .= "$sep$k=".urlencode(uc_stripslashes($v));
            }
            $sep = '&';
        }
        $postdata = uc_api_requestdata($module, $action, $s, $extra);
        return uc_fopen2(UC_API.'/index.php', 500000, $postdata, '', TRUE, UC_IP, 20);
    }
}


function xg_syncAvatar($uid, $avatar) {

    if(!$uid || !$avatar) {
        return false;
    }

    if(!$content = dfsockopen($avatar)) {
        return false;
    }

    $tmpFile = DISCUZ_ROOT.'./data/avatar/'.TIMESTAMP.random(6);
    file_put_contents($tmpFile, $content);

    if(!is_file($tmpFile)) {
        return false;
    }

    $result = uploadUcAvatar1::upload($uid, $tmpFile);
    unlink($tmpFile);

    C::t('common_member')->update($uid, array('avatarstatus'=>'1'));

    return $result;
}