<?php
$config->webapp = new stdClass();
$config->webapp->url       = 'http://www.zentao.net';
$config->webapp->apiRoot   = $config->webapp->url . '/webapp-';

$config->webapp->create = new stdClass();
$config->webapp->create->requiredFields = 'name,url,target';
