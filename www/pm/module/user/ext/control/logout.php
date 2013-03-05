<?php
include "../../control.php";

class myuser extends user
{
    public function logout($referer = 0)
    {
        $this->loadModel('action')->create('user', $this->app->user->id, 'logout');

        

        session_destroy();
        setcookie('za', false);
        setcookie('zp', false);
		//这里开始同步ucenter logout
        $this->loadModel('ucenter');
        $synlogout=$this->ucenter->uc_syn_logout();
	    echo $synlogout;

        $vars = !empty($referer) ? "referer=$referer" : '';

        //$this->locate($this->createLink('user', 'login', $vars));
        die(js::locate($this->createLink('user', 'login', $vars)));

        exit;
    }
}