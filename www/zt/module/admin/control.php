<?php
/**
 * The control file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: control.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class admin extends control
{
    /**
     * Index page.
     * @access public
     * @return void
     */
    public function index()
    {
        $community = zget($this->config->global, 'community');
        if(!$community or $community == 'na')
        {
            $this->view->bind    = false;
            $this->view->account = false;
            $this->view->ignore  = $community == 'na';
        }
        else
        {
            $this->view->bind    = true;
            $this->view->account = $community;
            $this->view->ignore  = false;
        }

		$this->app->loadLang('misc');

        $this->view->title = $this->lang->admin->common;
		$this->display();
    }

	/**
	 * Ignore notice of register and bind.
	 * 
	 * @access public
	 * @return void
	 */
	public function ignore()
	{
		$this->loadModel('setting')->setItem('system.common.global.community', 'na');
		die(js::locate(inlink('index'), 'parent'));
	}

	/**
	 * Register zentao.
	 * 
	 * @access public
	 * @return void
	 */
	public function register()
	{
		if($_POST)
		{
			$response = $this->admin->registerByAPI();
			if($response == 'success') 
			{
				$this->loadModel('setting')->setItem('system.common.global.community', $this->post->account);
				echo js::alert($this->lang->admin->register->success);
				die(js::locate(inlink('index'), 'parent'));
			}
			die($response);
		}
		$this->view->register = $this->admin->getRegisterInfo();
		$this->view->sn       = $this->config->global->sn;
		$this->display();
	}

	/**
	 * Bind zentao.
	 * 
	 * @access public
	 * @return void
	 */
	public function bind()
	{
		if($_POST)
		{
			$response = $this->admin->bindByAPI();	
			if($response == 'success') 
			{
				$this->loadModel('setting')->setItem('system.common.global.community', $this->post->account);
				echo js::alert($this->lang->admin->bind->success);
				die(js::locate(inlink('index'), 'parent'));
			}
			die($response);
		}
		$this->view->sn = $this->config->global->sn;
		$this->display();
	}

    /**
     * Check all tables.
     * 
     * @access public
     * @return void
     */
    public function checkDB()
    {
        $tables = $this->dbh->query('SHOW TABLES')->fetchAll();
        foreach($tables as $table)
        {
            $tableName = current((array)$table);
            $result = $this->dbh->query("REPAIR TABLE $tableName")->fetch();
            echo "Repairing TABLE: " . $result->Table . "\t" . $result->Msg_type . ":" . $result->Msg_text . "\n";
        }
    }

    /**
     * Rename table for from windows to linux.
     * 
     * @access public
     * @return void
     */
    public function win2Unix()
    {
        $renameTables = array(
            'zt_casestep'        => 'zt_caseStep'       ,
            'zt_doclib'          => 'zt_docLib'         ,
            'zt_grouppriv'       => 'zt_groupPriv'      ,
            'zt_productplan'     => 'zt_productPlan'    ,
            'zt_projectproduct'  => 'zt_projectProduct' ,
            'zt_projectstory'    => 'zt_projectStory'   ,
            'zt_relationoftasks' => 'zt_relationOfTasks',
            'zt_storyspec'       => 'zt_storySpec'      ,
            'zt_taskestimate'    => 'zt_taskEstimate'   ,
            'zt_testresult'      => 'zt_testResult'     ,
            'zt_testrun'         => 'zt_testRun'        ,
            'zt_testtask'        => 'zt_testTask'       ,
            'zt_usercontact'     => 'zt_userContact'    ,
            'zt_usergroup'       => 'zt_userGroup'      ,
            'zt_userquery'       => 'zt_userQuery'      ,
            'zt_usertpl'         => 'zt_userTPL'        
        );

        $existTables = $this->dbh->query('SHOW TABLES')->fetchAll();
        foreach($existTables as $key => $table) $existTables[$key] = current((array)$table);
        $existTables = array_flip($existTables);

        foreach($renameTables as $oldTable => $newTable)
        {
            if(isset($existTables[$newTable]))
            {
                echo "Has existed table '$newTable'\n";
            }
            elseif(!isset($existTables[$oldTable]))
            {
                echo "No found table '$oldTable'\n";
            }
            else
            {
                $this->dbh->query("RENAME TABLE `$oldTable` TO `$newTable`");
                echo "RENAME TABLE `$oldTable` TO `$newTable`\n";
            }
        }
        echo "Finish!\n";
    }

    /**
     * Confirm clear data.
     * 
     * @param  string $confirm ''|no|yes
     * @access public
     * @return void
     */
    public function clearData($confirm = '')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->admin->confirmClearData, inlink('clearData', "confirm=yes")));
        }
        elseif($confirm == 'yes')
        {
            $result = $this->admin->clearData();

            if(!$result) die(js::alert($this->lang->admin->clearDataFailed));

            js::alert($this->lang->admin->clearDataSuccessfully);
            die(js::locate(inLink('index'), 'parent'));
        }

        $this->display();
    }

    /**
     * Set flow.
     * 
     * @access public
     * @return void
     */
    public function setFlow()
    {
        if($_POST)
        {
            $this->loadModel('setting')->setItem('system.common.global.flow', $this->post->flow, 0);
            if($this->post->flow != 'full') die(js::locate($this->createLink('extension', 'install', "extension={$this->config->admin->flow2Ext[$this->post->flow]}"), 'parent'));
            die(js::reload( 'parent.parent'));
        }
        $this->display();
    }
}
