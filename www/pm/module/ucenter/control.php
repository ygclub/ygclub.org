<?php
/**
 * The control file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: control.php 2203 2011-10-22 05:11:49Z wwccss $
 * @link        http://www.zentao.net
 */

define('IN_DISCUZ', TRUE);

define('UC_CLIENT_VERSION', '1.5.0');	//note UCenter 版本标识
define('UC_CLIENT_RELEASE', '20081031');

define('API_DELETEUSER', 1);		//note 用户删除 API 接口开关
define('API_RENAMEUSER', 1);		//note 用户改名 API 接口开关
define('API_GETTAG', 1);		//note 获取标签 API 接口开关
define('API_SYNLOGIN', 1);		//note 同步登录 API 接口开关
define('API_SYNLOGOUT', 1);		//note 同步登出 API 接口开关
define('API_UPDATEPW', 1);		//note 更改用户密码 开关
define('API_UPDATEBADWORDS', 1);	//note 更新关键字列表 开关
define('API_UPDATEHOSTS', 1);		//note 更新域名解析缓存 开关
define('API_UPDATEAPPS', 1);		//note 更新应用列表 开关
define('API_UPDATECLIENT', 1);		//note 更新客户端缓存 开关
define('API_UPDATECREDIT', 1);		//note 更新用户积分 开关
define('API_GETCREDITSETTINGS', 1);	//note 向 UCenter 提供积分设置 开关
define('API_GETCREDIT', 1);		//note 获取用户的某项积分 开关
define('API_UPDATECREDITSETTINGS', 1);	//note 更新应用积分设置 开关

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

define('DISCUZ_ROOT', '../');

//note 使用该函数前需要 
    function _setcookie($var, $value, $life = 0, $prefix = 1) {
        global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
        setcookie(($prefix ? $cookiepre : '').$var, $value,
            $life ? $timestamp + $life : 0, $cookiepath,
            $cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
    }

    function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;

        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                    return '';
                }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }

    }

    function _stripslashes($string) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = _stripslashes($val);
            }
        } else {
            $string = stripslashes($string);
        }
        return $string;
    }

class ucenter extends control
{
    /**
     * Index page of admin module. Locate to action's trash page.
     * 
     * @access public
     * @return void
     */
    public function uc()
    {
        //note 普通的 http 通知方式
        if(!defined('IN_UC')) {
            error_reporting(1);
            set_magic_quotes_runtime(0);

            defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

            $_DCACHE = $get = $post = array();

            $code = @$_GET['code'];
  
            parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
            if(MAGIC_QUOTES_GPC) {
                $get = _stripslashes($get);
            }
            
            $timestamp = time();
            if($timestamp - $get['time'] > 3600) {
                exit('Authracation has expiried');
            }
            if(empty($get)) {
                exit('Invalid Request');
            }
            
           $action = $get['action'];
			
           require_once 'uc_client/lib/xml.class.php';
           $post = xml_unserialize(file_get_contents('php://input'));
            
            if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {
                exit($this->ucenter->$get['action']($get, $post));
            } else {
                exit(API_RETURN_FAILED);
            }

        //note include 通知方式
        } else {

            /*require_once  'include/db_mysql.class.php';
            $GLOBALS['db'] = new dbstuff;
            $GLOBALS['db']->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect, true, $dbcharset);
            $GLOBALS['tablepre'] = $tablepre;
            unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);*/
        }
    }

    public function set()
    {
		//$this->view->ucenterConfig = $this->app->loadConfig('ucenter')->ucenter;
        $this->display();
    }

	public function index()
	{
		$this->display();
	}
	
	public function synclogin()
	{
		$referer="";
		$uid="";
		if($this->session->referer)
			$referer=$this->session->referer;
		$this->view->uid=0;

		if($this->get->hide=='1')
		{
			if($this->session->uid)
				$uid=$this->session->uid;
			
			if(empty($uid))
			{
				$user=$this->session->user;
				$uc=$this->ucenter->uc_login($user->account,$user->password);

				$uid=$uc->uid;
			}
			
			$this->view->uid=$uid;
		}

		$loginLink = $this->createLink('user', 'login');
        $denyLink  = $this->createLink('user', 'deny');
		
		$link=$this->createLink($this->config->default->module);
		if($referer and 
		   strpos($referer, $loginLink) === false and 
		   strpos($referer, $denyLink)  === false 
		)
		{
			/* Get the module and method of the referer. */
			if($this->config->requestType == 'PATH_INFO')
			{
				$path = substr($this->post->referer, strrpos($this->post->referer, '/') + 1);
				$path = rtrim($path, '.html');
				list($module, $method) = explode($this->config->requestFix, $path);
			}
			else
			{
				$url   = html_entity_decode($this->post->referer);
				$param = substr($url, strrpos($url, '?') + 1);
				list($module, $method) = explode('&', $param);
				$module = str_replace('m=', '', $module);
				$method = str_replace('f=', '', $method);
			}
			
			if(common::hasPriv($module, $method))
			{
				//die(js::locate($this->post->referer, 'parent'));
				//die(js::locate($referer, 'parent'));
				$link=$referer;
			}
		}
		
		//$this->view->synjs=$synjs;
		$this->view->linkurl=$link;
		$this->display();
	}


    public function save()
    {
		if(!empty($_POST))
        {
			$configPath = dirname(__FILE__);
			/*$config = <<<EOT
<?php
\$config->ucenter->connect         = "{$this->post->connect}";
\$config->ucenter->dbhost         = "{$this->post->dbhost}";
\$config->ucenter->dbuser         = "{$this->post->dbuser}";
\$config->ucenter->dbpw         = "{$this->post->dbpw}";
\$config->ucenter->dbname         = "{$this->post->dbname}";
\$config->ucenter->dbcharset         = "{$this->post->dbcharset}";
\$config->ucenter->dbtablepre         = "{$this->post->dbtablepre}";
\$config->ucenter->dbconnect         = "{$this->post->dbconnect}";
\$config->ucenter->key         = "{$this->post->key}";
\$config->ucenter->api         = "{$this->post->api}";
\$config->ucenter->charset         = "{$this->post->charset}";
\$config->ucenter->ip         = "{$this->post->ip}";
\$config->ucenter->appid         = "{$this->post->appid}";
\$config->ucenter->ppp         = "{$this->post->ppp}";
EOT;
*/
			
			$configstr = "<?php\r\n";
			$configstr .= "define('".$this->lang->ucenter->connect . "','".$this->post->connect."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->dbhost . "','".$this->post->dbhost."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->dbuser . "','".$this->post->dbuser."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->dbpw . "','".$this->post->dbpw."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->dbname . "','".$this->post->dbname."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->dbcharset . "','".$this->post->dbcharset."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->dbtablepre . "','".$this->post->dbtablepre."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->dbconnect . "','".$this->post->dbconnect."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->key . "','".$this->post->key."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->api . "','".$this->post->api."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->charset . "','".$this->post->charset."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->ip . "','".$this->post->ip."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->appid . "','".$this->post->appid."');\r\n";
			$configstr .= "define('".$this->lang->ucenter->ppp . "','".$this->post->ppp."');\r\n";
			if(is_writable($configPath))
            {
				
                if(file_put_contents($configPath . '\config.php', $configstr))
                {
					//$this->ucenter->savesetting($configstr);
					
					echo js::confirm($this->lang->ucenter->confirmSave,$this->createLink('ucenter', 'set'));
				}
				else
				{
					$this->view->configstr=$configstr;
					$this->view->configPath=$configPath;
					$this->display();
				}
			}
			else
			{
				$this->view->configstr=$configstr;
				$this->view->configPath=$configPath;
				$this->display();
			}
		}
    }

	public function exportuser()
	{
		if(!empty($_POST))
        {
			$users=$this->post->members;
			$msg="";
			$this->loadModel('user');
			for($i=0;$i<count($users);$i++)
			{
				$user=$this->user->getUser($users[$i]);
				if($user)
				{
					$uid=$this->ucenter->uc_adduser($user->account,$user->password,$user->email);

					$msg.="<li>导出用户<span style=\"color:blue;\">".$user->account."</span>";

					if($uid <= 0) {
						if($uid == -1) {
							$msg.= ':用户名不合法';
						} elseif($uid == -2) {
							$msg.=  ':包含要允许注册的词语';
						} elseif($uid == -3) {
							$msg.=  ':用户名已经存在';
						} elseif($uid == -4) {
							$msg.=  ':Email 格式有误';
						} elseif($uid == -5) {
							$msg.=  ':Email 不允许注册';
						} elseif($uid == -6) {
							$msg.=  ':该 Email 已经被注册';
						} else {
							$msg.=  ':未知错误';
						}
					} else {
						$msg.=  ':注册成功';
					}
				 $msg.="</li>";
				}

				
			}			

			echo js::execute("window.parent.output('".$msg."');");
		}
		else
		{
			$this->loadModel('user');
			$this->view->Users= $this->user->getPairs('noclosed|noempty|noletter');
		}


		$this->display();
	}
}
