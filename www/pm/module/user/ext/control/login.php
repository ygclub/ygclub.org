<?php
include "../../control.php";

class myuser extends user
{
    public $referer;

    public function login($referer = '', $from = '')
    {
        $this->setReferer($referer);
		
        $loginLink = $this->createLink('user', 'login');
        $denyLink  = $this->createLink('user', 'deny');
        $logoutlink=$this->createLink('user','logout');
		
		$syncloginlink=$this->createLink('ucenter','synclogin');

        /* If user is logon, back to the rerferer. */
        if($this->user->isLogon())
        {
            if(strpos($this->referer, $loginLink) === false and 
               strpos($this->referer, $denyLink)  === false and 
			   strpos($this->referer, $logoutlink)  === false and
               strpos($this->referer, $this->app->company->pms) !== false
            )
            {
				if(common::hasPriv('ucenter', 'synclogin'))
				{
					die(js::locate($syncloginlink,'parent'));
				}
				else
				{
					 $this->locate($this->referer);
				}
            }
            else
            {
				$this->session->set('referer',$this->createLink($this->config->default->module));
				$syncloginlink=$this->createLink('ucenter','synclogin');

				if(common::hasPriv('ucenter', 'synclogin'))
				{
					die(js::locate($syncloginlink,'parent'));
				}
				else
				{
					$this->locate($this->createLink($this->config->default->module));
				}
            }
        }
        
        
        /* Passed account and password by post or get. */
        if(!empty($_POST) or (isset($_GET['account']) and isset($_GET['password'])))
        {			
            $account  = '';
            $password = '';
            if($this->post->account)  $account  = $this->post->account;
            if($this->get->account)   $account  = $this->get->account;
            if($this->post->password) $password = $this->post->password;
            if($this->get->password)  $password = $this->get->password;
		
			//先同步登录ucenter
            $this->loadModel('ucenter');
			$uc=$this->ucenter->uc_login($account,$password);
			$user="";

			if($uc->uid>0)
			{//登陆成功，同步登陆至其它应用

                $groups = $this->dao->select('*')->from(TABLE_USERGROUP)->where('account')->eq($account)->fetch();
                if(empty($groups))
                {
                    $data->account = $account;
                    $data->group = 10;
                    $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
                }

				$this->session->set('uid',$uc->uid);
				//判断是否存在该用户记录
				$thisuser=$this->user->getUser($account);
				if($thisuser)
				{//如果存在则登录，并验证密码是否一致
					$user=$this->user->identify($account,$password);
					if($user)
					{//密码一致，则跳过进行下一步
					}
					else
					{//密码不一致，则更新密码至pms数据库
						$this->user->updateUserPassword($account,$password);
						$user=$this->user->identify($account,$password);
					}
				}
				else
				{//如果用户不存在，则增加当前用户记录至pms数据库，
				 //这是为了防止当前用户在其它ucenter外接应用中增加，且未在此外接应用中登陆，导至用户未同步至本地数据库而进行的操作
				  $user->account=$account;
				  $user->realname=$account;
				  $user->password=$password;
				  $user->email=$uc->email;

				  $this->user->addUser($user);
				}
			}
			else
			{
				//echo js::alert('UCenter登录失败');
				//不管登录ucenter成不成功，都
				$user = $this->user->identify($account, $password);
				if($user)
				{
					//如果在本地登录成功，但在UCenter中登录失败，则考虑用户是否存在，若存在，则同步密码
					//uc_updateuser函数将自动判断用户是否存在，不存在，则不会增加会户
					$this->ucenter->uc_updateuser($account,$account,$password,$user->email);
				}
			}

            if($user)
            {
                $this->loadModel('ucenter');
		   
                /* Authorize him and save to session. */
                $user->rights = $this->user->authorize($account);
				$user->groups = $this->user->getGroups($account);

                $this->session->set('user', $user);
                $this->app->user = $this->session->user;
                $this->loadModel('action')->create('user', $user->id, 'login');
                
                /* Keep login. */
                if($this->post->keepLogin) $this->user->keepLogin($user);
				if(common::hasPriv('ucenter', 'synclogin'))
				{
					die(js::locate($syncloginlink,'parent'));
				}
				else
				{
					  if($this->post->referer and 
					   strpos($this->post->referer, $loginLink) === false and 
					   strpos($this->post->referer, $denyLink)  === false 
					)
					{
						if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'success')));

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
							die(js::locate($this->post->referer, 'parent'));
						}
						else
						{
							die(js::locate($this->createLink($this->config->default->module), 'parent'));
						}
					}
					else
					{
						die(js::locate($this->createLink($this->config->default->module), 'parent'));
					}
				}
            }
            else
            {
                if($this->app->getViewType() == 'json') die(json_encode(array('status' => 'failed')));
                die(js::error($this->lang->user->loginFailed));
            }
        }
        else
        {
            $demoUsers = $this->user->getPairs('nodeleted, noletter');
            array_shift($demoUsers);
            array_shift($demoUsers);
            array_pop($demoUsers);
            $this->view->showDemoUsers = $this->dao->select('value')->from(TABLE_CONFIG)->where('`key`')->eq('showDemoUsers')->fetch();
            $this->view->demoUsers = $demoUsers;

            $header['title'] = $this->lang->user->login;
            $this->view->header    = $header;
            $this->view->referer   = $this->referer;
            $this->view->s         = $this->loadModel('setting')->getItem('system', 'global', 'sn',0);
            $this->view->keepLogin = $this->cookie->keepLogin ? $this->cookie->keepLogin : 'off';
            $this->display();
        }
    }

}
