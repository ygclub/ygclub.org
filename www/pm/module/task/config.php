<?php
$config->task = new stdclass();
$config->task->batchCreate = 10;

$config->task->create   = new stdclass();
$config->task->edit     = new stdclass();
$config->task->start    = new stdclass();
$config->task->finish   = new stdclass();
$config->task->activate = new stdclass();

$config->task->create->requiredFields      = 'name,type';
$config->task->edit->requiredFields        = $config->task->create->requiredFields;
$config->task->start->requiredFields       = 'estimate';
$config->task->finish->requiredFields      = 'consumed';
$config->task->activate->requiredFields    = 'left';

$config->task->editor = new stdclass();
$config->task->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->task->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');

$config->task->exportFields = '
    id, project, module, story,
    name, desc,
    type, pri,estStarted, realStarted, deadline, status,estimate, consumed, left,
    mailto,
    openedBy, openedDate, assignedTo, assignedDate, 
    finishedBy, finishedDate, canceledBy, canceledDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate,files
    ';
