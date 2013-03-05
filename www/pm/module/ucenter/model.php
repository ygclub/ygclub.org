
<?php
class ucenterModel extends model
{
    
	var $dbconfig = '';
	var $db = '';
	var $tablepre = '';
	var $appdir = '';
    
    /*function __construct() {
		$this->loadModel('user');
	}*/

    function uc_login($username,$password)
    {
        require_once "uc_client/client.php";

        $uc->uid=0;
        list($uid, $username, $password, $email)=uc_user_login($username,$password,0);
        
        $uc->uid=$uid;
		$uc->username=$username;
		$uc->email=$email;
        
        return $uc; 
    }

    function uc_adduser($username,$password,$email)
    {
        require_once "uc_client/client.php";
        return uc_user_register($username,$password,$email);
    }

    function uc_updateuser($oldusername,$username,$password,$email)
    {
        require_once "uc_client/client.php";
       
       if($oldusername==$username)
       {
            return uc_user_edit($username,"",$password,$email,1);
       }
       else
       {
           // $uid=uc_user_register($oldusername);
            $uc=uc_get_user($oldusername);
            if($uc)
           {
                uc_user_merge($oldusername,$username,$uc[0],$password,$email);
           }
       }
    }

    function uc_deleteuser($username)
    {
        require_once "uc_client/client.php";
        $uc=uc_get_user($username);

        return uc_user_delete($uc[0]);
    }

    function uc_renuser()
    {

    }

    function uc_sysn_login($uid)
    {
        require_once "uc_client/client.php";

        return uc_user_synlogin($uid); 
    }
    
    function uc_syn_logout()
    {
        require_once "uc_client/client.php";

        return uc_user_synlogout();
    }
    
    ////////////////////////request fo uc note///////////////////////////////////////////////
	function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once 'uc_client/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function deleteuser($get, $post) {
		$uids = $get['ids'];

		!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
        
        $arrUids = explode(',', $uids);
        foreach ($arrUids as $id) {
            $arrTmp = uc_get_user(trim($id), TRUE);
            
            $arrUsernames[] = "'" . $arrTmp[1] . "'";
        }
        
        $usernames = implode(",", $arrUsernames);

        $this->loadModel('user');
        $this->user->deleteUser($usernames);
		return API_RETURN_SUCCEED;
	}

	function renameuser($get, $post) {
		$uid = $get['uid'];
		$usernameold = $get['oldusername'];
		$usernamenew = $get['newusername'];
		if(!API_RENAMEUSER) {
			return API_RETURN_FORBIDDEN;
		}
        $this->loadModel('user');
        $this->user->updateUserName($usernameold,$usernamenew);
		return API_RETURN_SUCCEED;
	}

	function gettag($get, $post) {
		$name = $get['id'];
		if(!API_GETTAG) {
			return API_RETURN_FORBIDDEN;
		}
		
		$return = array();
		return $this->_serialize($return, 1);
	}

	function synlogin($get, $post) {
        require_once "uc_client/client.php";

		$uid = $get['uid'];
		$username = $get['username'];

   		if(!API_SYNLOGIN) {
			return API_RETURN_FORBIDDEN;
		}

        $this->loadModel('user');
        $user=$this->user->getUser($username);

        $groups = $this->dao->select('*')->from(TABLE_USERGROUP)->where('account')->eq($username)->fetch();
        if(empty($groups))
        {
            $data->account = $username;
            $data->group = 10;
            $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
        }
       
        if(!$user){
             //如果用户不存在，则增加用户
            $ucc=uc_get_user($username);

            //add user to local database
            $user->account=$ucc[1];
            ///$user->password=$ucc->password;
            $user->realname=$ucc[1];
            $user->email=$ucc[2];
            
            $this->user->addUser($user);
        }

        if($user)
        {
            header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
            $this->session->set('rand', mt_rand(0, 10000));
            
            $user->rights = $this->user->authorize($user->account);
            $this->session->set('user', $user);

            $this->app->user = $this->session->user;

            $this->loadModel('action')->create('user', $user->id, 'login');
            _setcookie('sid', session_id());
        }
      
	}

	function synlogout($get, $post) {


		if(!API_SYNLOGOUT) {
			return API_RETURN_FORBIDDEN;
		}
        
        
		//note 同步登出 API 接口
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
         session_destroy();
        _setcookie('za', false);
        _setcookie('zp', false);
		_setcookie('sid', false);
	}

	function updatepw($get, $post) {


		if(!API_UPDATEPW) {
			return API_RETURN_FORBIDDEN;
		}
		$username = $get['username'];
		$password = $get['password'];

		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		if(!API_UPDATEBADWORDS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/badwords.php';
		$fp = fopen($cachefile, 'w');
		$data = array();
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$data['findpattern'][$k] = $v['findpattern'];
				$data['replace'][$k] = $v['replacement'];
			}
		}
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		if(!API_UPDATEHOSTS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/hosts.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post) {
		if(!API_UPDATEAPPS) {
			return API_RETURN_FORBIDDEN;
		}
		$UC_API = $post['UC_API'];

		//note 写 app 缓存文件
		$cachefile = $this->appdir.'./uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		//note 写配置文件
		if(is_writeable($this->appdir.'./config.php')) {
			$configfile = trim(file_get_contents($this->appdir.'./config.php'));
			$configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
			$configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
			if($fp = @fopen($this->appdir.'./config.php', 'w')) {
				@fwrite($fp, trim($configfile));
				@fclose($fp);
			}
		}
	
		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatecredit($get, $post) {
		if(!API_UPDATECREDIT) {
			return API_RETURN_FORBIDDEN;
		}
		$credit = $get['credit'];
		$amount = $get['amount'];
		$uid = $get['uid'];
		return API_RETURN_SUCCEED;
	}

	function getcredit($get, $post) {
		if(!API_GETCREDIT) {
			return API_RETURN_FORBIDDEN;
		}
	}

	function getcreditsettings($get, $post) {
		if(!API_GETCREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		$credits = array();
		return $this->_serialize($credits);
	}

	function updatecreditsettings($get, $post) {
		if(!API_UPDATECREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		return API_RETURN_SUCCEED;
	}



	/*ucenter 模块相关接口*/
	function savesetting($s)
	{
		/*$configfile = $this->appdir.'./config.inc.php';
		$fp = fopen($configfile, 'w');
		
		fwrite($fp, $s);
		fclose($fp);*/
	}
}
