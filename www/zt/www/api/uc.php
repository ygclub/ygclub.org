<?php

error_reporting(1);

/* Start output buffer. */
ob_start();

/* Load the framework. */
include '../../framework/router.class.php';
include '../../framework/control.class.php';
include '../../framework/model.class.php';
include '../../framework/helper.class.php';

$startTime = getTime();

$app = router::createApp('pms', dirname(dirname(dirname(__FILE__))));
$code=$_GET['code'];

$_GET['m']='ucenter';
$_GET['f']='uc';

$common = $app->loadCommon();
$app->parseRequest();
//$common->checkPriv();
$app->loadModule();
ob_end_flush();
