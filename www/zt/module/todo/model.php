<?php
/**
 * The model file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: model.php 3839 2012-12-18 07:46:26Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class todoModel extends model
{
    const DAY_IN_FUTURE = 20300101;

    /**
     * Create a todo.
     * 
     * @param  date   $date 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function create($date, $account)
    {
        $todo = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->add('idvalue', 0)
            ->specialChars('type,name')
            ->cleanInt('date, pri, begin, end, private')
            ->setIF($this->post->type != 'custom', 'name', '')
            ->setIF($this->post->type == 'bug'  and $this->post->bug,  'idvalue', $this->post->bug)
            ->setIF($this->post->type == 'task' and $this->post->task, 'idvalue', $this->post->task)
            ->setIF($this->post->date == false,  'date', '2030-01-01')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end',   '2400')
            ->remove('bug, task')
            ->get();
        $this->dao->insert(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF($todo->type == 'custom', $this->config->todo->create->requiredFields, 'notempty')
            ->checkIF($todo->type == 'bug'  and $todo->idvalue == 0, 'idvalue', 'notempty')
            ->checkIF($todo->type == 'task' and $todo->idvalue == 0, 'idvalue', 'notempty')
            ->exec();
        return $this->dao->lastInsertID();
    }

    /**
     * Create batch todo
     * 
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        $todos = fixer::input('post')->cleanInt('date')->get();
        for($i = 0; $i < $this->config->todo->batchCreate; $i++)
        {
            if($todos->names[$i] != '' || isset($todos->bugs[$i + 1]) || isset($todos->tasks[$i + 1]))
            {
                $todo->account = $this->app->user->account;
                if($this->post->date == false)
                {
                    $todo->date    = '2030-01-01';
                }
                else
                {
                    $todo->date    = $this->post->date;
                }
                $todo->type    = $todos->types[$i];
                $todo->pri     = $todos->pris[$i];
                $todo->name    = isset($todos->names[$i]) ? $todos->names[$i] : '';
                $todo->desc    = $todos->descs[$i];
                $todo->begin   = $todos->begins[$i];
                $todo->end     = $todos->ends[$i];
                $todo->status  = "wait";
                $todo->private = 0;
                $todo->idvalue = 0;
                if($todo->type == 'bug')  $todo->idvalue = isset($todos->bugs[$i + 1]) ? $todos->bugs[$i + 1] : 0;
                if($todo->type == 'task') $todo->idvalue = isset($todos->tasks[$i + 1]) ? $todos->tasks[$i + 1] : 0;

                $this->dao->insert(TABLE_TODO)->data($todo)->autoCheck()->exec();
                if(dao::isError()) 
                {
                    echo js::error(dao::getError());
                    die(js::reload('parent'));
                }
            }
            else
            {
                unset($todos->types[$i]);
                unset($todos->pris[$i]);
                unset($todos->names[$i]);
                unset($todos->descs[$i]);
                unset($todos->begins[$i]);
                unset($todos->ends[$i]);
            }
        }
    }

    /**
     * update a todo.
     * 
     * @param  int    $todoID 
     * @access public
     * @return void
     */
    public function update($todoID)
    {
        $oldTodo = $this->getById($todoID);
        if($oldTodo->type != 'custom') $oldTodo->name = '';
        $todo = fixer::input('post')
            ->cleanInt('date, pri, begin, end, private')
            ->specialChars('type,name')
            ->setIF($this->post->type  != 'custom', 'name', '')
            ->setIF($this->post->date  == false, 'date', '2030-01-01')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end', '2400')
            ->setDefault('private', 0)
            ->get();
        $this->dao->update(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF($todo->type == 'custom', $this->config->todo->edit->requiredFields, 'notempty')->where('id')->eq($todoID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldTodo, $todo);
    }

    /**
     * Batch update todos.
     * 
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $todos      = array();
        $allChanges = array();
        $todoIDList = $this->post->todoIDList ? $this->post->todoIDList : array();

        /* Adjust whether the post data is complete, if not, remove the last element of $todoIDList. */
        if($this->session->showSuhosinInfo) array_pop($taskIDList);

        if(!empty($todoIDList))
        {
            /* Initialize todos from the post data. */
            foreach($todoIDList as $todoID)
            {
                $todo->date  = $this->post->dates[$todoID];
                $todo->type  = $this->post->types[$todoID];
                $todo->pri   = $this->post->pris[$todoID];
                $todo->name  = $todo->type == 'custom' ? htmlspecialchars($this->post->names[$todoID]) : '';
                $todo->begin = $this->post->begins[$todoID];
                $todo->end   = $this->post->ends[$todoID];
                if($todo->type == 'task') $todo->idvalue = isset($this->post->tasks[$todoID]) ? $this->post->tasks[$todoID] : 0;
                if($todo->type == 'bug')  $todo->idvalue = isset($this->post->bugs[$todoID]) ? $this->post->bugs[$todoID] : 0;

                $todos[$todoID] = $todo;
                unset($todo);
            }

            foreach($todos as $todoID => $todo)
            {
                $oldTodo = $this->getById($todoID);
                if($oldTodo->type != 'custom') $oldTodo->name = '';
                $this->dao->update(TABLE_TODO)->data($todo)
                    ->autoCheck()
                    ->checkIF($todo->type == 'custom', $this->config->todo->edit->requiredFields, 'notempty')               
                    ->checkIF($todo->type == 'bug', 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'task', 'idvalue', 'notempty')
                    ->where('id')->eq($todoID)
                    ->exec();

                if(!dao::isError()) 
                {
                    $allChanges[$todoID] = common::createChanges($oldTodo, $todo);
                }
                else
                {
                    die(js::error('todo#' . $todoID . dao::getError(true)));
                }
            }
        }

        return $allChanges;
    }

    /**
     * Change the status of a todo.
     * 
     * @param  string $todoID 
     * @param  string $status 
     * @access public
     * @return void
     */
    public function mark($todoID, $status)
    {
        $status = ($status == 'done') ? 'wait' : 'done';
        $this->dao->update(TABLE_TODO)->set('status')->eq($status)->where('id')->eq((int)$todoID)->exec();
        $this->loadModel('action')->create('todo', $todoID, 'marked', '', $status);
        return;
    }

    /**
     * Get info of a todo.
     * 
     * @param  int    $todoID 
     * @param  bool   $setImgSize
     * @access public
     * @return object|bool
     */
    public function getById($todoID, $setImgSize = false)
    {
        $todo = $this->dao->findById((int)$todoID)->from(TABLE_TODO)->fetch();
        if(!$todo) return false;
        if($setImgSize) $todo->desc = $this->loadModel('file')->setImgSize($todo->desc);
        if($todo->type == 'task') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
        if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
        $todo->date = str_replace('-', '', $todo->date);
        return $todo;
    }

    /**
     * Get todo list of a user.
     * 
     * @param  date   $date 
     * @param  string $account 
     * @param  string $status   all|today|thisweek|lastweek|before, or a date.
     * @param  int    $limit    
     * @access public
     * @return void
     */
    public function getList($date = 'today', $account = '', $status = 'all', $limit = 0, $pager = null)
    {
        $todos = array();
        if($date == 'today') 
        {
            $begin = $this->today();
            $end   = $begin;
        }
        elseif($date == 'yesterday') 
        {
            $begin = $this->yesterday();
            $end   = $begin;
        }
        elseif($date == 'thisweek')
        {
            extract($this->getThisWeek());
        }
        elseif($date == 'lastweek')
        {
            extract($this->getLastWeek());
        }
        elseif($date == 'thismonth')
        {
            extract($this->getThisMonth());
        }
        elseif($date == 'lastmonth')
        {
            extract($this->getLastMonth());
        }
        elseif($date == 'thisseason')
        {
            extract($this->getThisSeason());
        }
        elseif($date == 'thisyear')
        {
            extract($this->getThisYear());
        }
        elseif($date == 'future')
        {
            $begin = '2030-01-01';
            $end   = $begin;
        }
        elseif($date == 'all')
        {
            $begin = '1970-01-01';
            $end   = '2109-01-01';
        }
        elseif($date == 'before')
        {
            $begin = '1970-01-01';
            $end   = $this->yesterday();
        }
        else
        {
            $begin = $end = $date;
        }

        if($account == '')   $account = $this->app->user->account;

        $stmt = $this->dao->select('*')->from(TABLE_TODO)
            ->where('account')->eq($account)
            ->andWhere("date >= '$begin'")
            ->andWhere("date <= '$end'")
            ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->ne('done')->fi()
            ->orderBy('date, status, begin')
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager)
            ->query();
        
        /* Set session. */
        $sql = explode('WHERE', $this->dao->get());
        $sql = explode('ORDER', $sql[1]);
        $this->session->set('todoReportCondition', $sql[0]);

        while($todo = $stmt->fetch())
        {
            if($todo->type == 'task') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
            $todo->begin = $this->formatTime($todo->begin);
            $todo->end   = $this->formatTime($todo->end);

            /* If is private, change the title to private. */
            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;
            $todos[] = $todo;
        }
        return $todos;
    }

    /**
     * Build date list, for selection use.
     * 
     * @param  int    $before 
     * @param  int    $after 
     * @access public
     * @return void
     */
    public function buildDateList($before = 7, $after = 7)
    {
        $today = strtotime($this->today());
        $delta = 60 * 60 * 24;
        $dates = array();
        $weekList     = range(1, 7);
        $weekDateList = explode(',', $this->lang->todo->weekDateList);
        for($i = -1 * $before; $i <= $after; $i ++)
        {
            $time   = $today + $i * $delta;
            $label  = date(DT_DATE1, $time);
            if($i == 0)
            {
                $label .= " ({$this->lang->todo->today})";
            }
            else
            {
                if($this->cookie->lang == 'zh-cn' or $this->cookie->lang == 'zh-tw')
                {
                    $label .= str_replace($weekList, $weekDateList, date(" ({$this->lang->todo->week}N)", $time));
                }
                else
                {
                    $label .= date($this->lang->todo->week, $time);
                }
            }
            $date   = date(DT_DATE2, $time);
            $dates[$date] = $label;
        }
        $dates[self::DAY_IN_FUTURE] = $this->lang->todo->dayInFuture;
        return $dates;
    }

    /**
     * Build hour time list.
     * 
     * @param  int $begin 
     * @param  int $end 
     * @param  int $delta 
     * @access public
     * @return array
     */
    public function buildTimeList($begin, $end, $delta)
    {
        $times = array();
        for($hour = $begin; $hour <= $end; $hour ++)
        {
            for($minutes = 0; $minutes < 60; $minutes += $delta)
            {
                $time  = sprintf('%02d%02d', $hour, $minutes);
                $label = sprintf('%02d:%02d', $hour, $minutes);
                $times[$time] = $label;
            }
        }
        return $times;
    }

    /**
     * Get today.
     * 
     * @access public
     * @return date
     */
    public function today()
    {
        return date(DT_DATE2, time());
    }

    /**
     * Get yesterday 
     * 
     * @access public
     * @return date
     */
    public function yesterday()
    {
        return date(DT_DATE1, strtotime('yesterday'));
    }

    /**
     * Get tomorrow.
     * 
     * @access public
     * @return date
     */
    public function tomorrow()
    {
        return date(DT_DATE1, strtotime('tomorrow'));
    }

    /**
     * Get the day before yesterday.
     * 
     * @access public
     * @return date
     */
    public function twoDaysAgo()
    {
        return date(DT_DATE1, strtotime('-2 days'));
    }

    /**
     * Get now time period.
     * 
     * @param  int    $delta 
     * @access public
     * @return string the current time period, like 0915
     */
    public function now($delta = 10)
    {
        $range  = range($delta, 60 - $delta, $delta);
        $hour   = date('H', time());
        $minute = date('i', time());

        if($minute > 60 - $delta)
        {
            $hour += 1;
            $minute = 00;
        }
        else
        {
            for($i = 0; $i < $delta; $i ++)
            {
                if(in_array($minute + $i, $range))
                {
                    $minute = $minute + $i;
                    break;
                }
            }
        }

        return sprintf('%02d%02d', $hour, $minute);
    }

    /**
     * Format time 0915 to 09:15
     * 
     * @param  string $time 
     * @access public
     * @return string
     */
    public function formatTime($time)
    {
        if(strlen($time) != 4 or $time == '2400') return '';
        return substr($time, 0, 2) . ':' . substr($time, 2, 2);
    }

    /**
     * Get the begin and end date of this week.
     * 
     * @access public
     * @return array
     */
    public function getThisWeek()
    {
        $baseTime = $this->getMiddleOfThisWeek();
        $begin = date(DT_DATE1, strtotime('last monday', $baseTime)) . ' 00:00:00';
        $end   = date(DT_DATE1, strtotime('next sunday', $baseTime)) . ' 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get the begin and end date of last week.
     * 
     * @access public
     * @return array
     */
    public function getLastWeek()
    {
        $baseTime = $this->getMiddleOfLastWeek();
        $begin = date(DT_DATE1, strtotime('last monday', $baseTime)) . ' 00:00:00';
        $end   = date(DT_DATE1, strtotime('next sunday', $baseTime)) . ' 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get the time at the middle of this week.
     * 
     * If today in week is 1, move it one day in future. Else is 7, move it back one day. To keep the time geted in this week.
     *
     * @access public
     * @return time
     */
    public function getMiddleOfThisWeek()
    {
        $baseTime = time();
        $weekDay  = date('N');
        if($weekDay == 1) $baseTime = time() + 86400;
        if($weekDay == 7) $baseTime = time() - 86400;
        return $baseTime;
    }

    /**
     * Get middle of last week 
     * 
     * @access public
     * @return time
     */
    public function getMiddleOfLastWeek()
    {
        $baseTime = time();
        $weekDay  = date('N');
        $baseTime = time() - 86400 * 7;
        if($weekDay == 1) $baseTime = time() - 86400 * 4;  // Make sure is last thursday.
        if($weekDay == 7) $baseTime = time() - 86400 * 10; // Make sure is last thursday.
        return $baseTime;
    }

    /**
     * Get this month begin and end time
     * 
     * @access public
     * @return array
     */
    public function getThisMonth()
    {
        $begin = date('Y-m') . '-01 00:00:00';
        $end   = date('Y-m', strtotime('next month')) . '-00 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get last month begin and end time
     * 
     * @access public
     * @return array
     */
    public function getLastMonth()
    {
        $begin = date('Y-m', strtotime('last month')) . '-01 00:00:00';
        $end   = date('Y-m', strtotime('this month')) . '-00 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    public function getThisSeason()
    {
        $year  = date("Y-");
        $month = date("n");
        if($month % 3)
        {
            $seasonBegin = $month - ($month % 3) + 1;
            $seasonEnd   = $seasonBegin + 3;
        }
        else
        {
            $seasonEnd   = $month + 1;
            $seasonBegin = $seasonEnd - 3;
        }

        if(strlen($seasonBegin) == 1) $seasonBegin = "0$seasonBegin";
        if(strlen($seasonEnd) == 1)   $seasonEnd   = "0$seasonEnd";
        $begin = $year . $seasonBegin;
        $end   = $year . $seasonEnd;

        return array('begin' => $begin, 'end' => $end);
    }

    public function getThisYear()
    {
        $begin = date(DT_DATE1, strtotime('1/1 this year'));
        $end   = date(DT_DATE1, strtotime('1/1 next year -1 day'));  
        return array('begin' => $begin, 'end' => $end);
    }
}



