<?php
$_CACHE['apps'] = array (
  1 => 
  array (
    'appid' => '1',
    'type' => 'DISCUZX',
    'name' => '阳光志愿者',
    'url' => 'http://www.ygclub.org/bbs',
    'ip' => '',
    'viewprourl' => '/space-uid-%s.html',
    'apifilename' => 'uc.php',
    'charset' => 'utf-8',
    'dbcharset' => 'utf8',
    'synlogin' => '1',
    'recvnote' => '1',
    'extra' => false,
    'tagtemplates' => '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
	<item id="template"><![CDATA[<a href="{url}" target="_blank">{subject}</a>]]></item>
	<item id="fields">
		<item id="subject"><![CDATA[标题]]></item>
		<item id="uid"><![CDATA[用户 ID]]></item>
		<item id="username"><![CDATA[发帖者]]></item>
		<item id="dateline"><![CDATA[日期]]></item>
		<item id="url"><![CDATA[主题地址]]></item>
	</item>
</root>',
    'allowips' => '',
  ),
  7 => 
  array (
    'appid' => '7',
    'type' => 'OTHER',
    'name' => 'Flexsns-Sky',
    'url' => 'http://www.ygclub.org/sky',
    'ip' => '',
    'viewprourl' => '',
    'apifilename' => 'uc.php',
    'charset' => 'utf-8',
    'dbcharset' => 'utf-8',
    'synlogin' => '1',
    'recvnote' => '1',
    'extra' => false,
    'tagtemplates' => '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
 <item id="template"><![CDATA[{content}[{dateline}]]]></item>
 <item id="fields">
 <item id="content"><![CDATA[天空数据内容]]></item>
 <item id="uid"><![CDATA[用户id]]></item>
 <item id="username"><![CDATA[用户名]]></item>
 <item id="dateline"><![CDATA[时间]]></item>
 <item id="url"><![CDATA[数据连接]]></item>
 </item>
</root>',
    'allowips' => '',
  ),
  8 => 
  array (
    'appid' => '8',
    'type' => 'OTHER',
    'name' => 'HDWIKI',
    'url' => 'http://www.ygclub.org/wiki',
    'ip' => '',
    'viewprourl' => '/bbs/space-uid-%s.html',
    'apifilename' => 'uc.php',
    'charset' => 'utf-8',
    'dbcharset' => 'utf-8',
    'synlogin' => '1',
    'recvnote' => '1',
    'extra' => false,
    'tagtemplates' => '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
 <item id="template"><![CDATA[]]></item>
</root>',
    'allowips' => '',
  ),
  11 => 
  array (
    'appid' => '11',
    'type' => 'OTHER',
    'name' => '签到',
    'url' => 'http://www.ygclub.org/qd',
    'ip' => '',
    'viewprourl' => '/bbs/space-uid-%s.html',
    'apifilename' => 'uc.php',
    'charset' => '',
    'dbcharset' => '',
    'synlogin' => '1',
    'recvnote' => '1',
    'extra' => false,
    'tagtemplates' => '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
 <item id="template"><![CDATA[]]></item>
</root>',
    'allowips' => '',
  ),
  12 => 
  array (
    'appid' => '12',
    'type' => 'UCHOME',
    'name' => 'mediawiki',
    'url' => 'http://www.ygclub.org/wiki/extensions/Auth_UC',
    'ip' => '',
    'viewprourl' => '',
    'apifilename' => 'uc.php',
    'charset' => '',
    'dbcharset' => '',
    'synlogin' => '1',
    'recvnote' => '1',
    'extra' => false,
    'tagtemplates' => '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
 <item id="template"><![CDATA[]]></item>
</root>',
    'allowips' => '',
  ),
);

?>