<?php
$config->installed       = true;
$config->debug           = false;
$config->requestType     = 'GET';
$config->db->host        = 'localhost';
$config->db->port        = '3306';
$config->db->name        = 'dev_ygclub_org';
$config->db->user        = 'ygclub';
$config->db->password    = 'yanphatok4';
$config->db->prefix      = 'zt_';
$config->webRoot         = getWebRoot();
$config->default->domain = 'dev.ygclub.org';
$config->default->lang   = 'zh-cn';
$config->mysqldump       = '';