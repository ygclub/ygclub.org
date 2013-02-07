Installation steps
==================

Requirements
------------


0. download PhpStorm
------------------------------


1. create project using PhpStorm
------------------------------


2. set up web application for debug
------------------------------

2.1 sett up debug configuration；

click Edit Configurations

    configure defaults->PHP Build-in Web Server:
        host:localhost
        port: 8888
        document root：ygclub/www
        check router script； <MAMP_PATH>/conf/php5.4.4/php.ini

    configure defaults->Php Web Appliation:
        add a server:
            name: ygclub
            host:localhost
            port:8888
            debugger:Xdebug
        start url:/

    config defaults->Php Remote Debug:
        select server: ygclub

    Add a new configuration:
        click + sign
        select PHP Web Application from option list
        server: ygclub
        start URL: /xbbs/index.php

    click Apply & OK

Select PHP Interpreter:
    open project setting->PHP
    select interpreter: php5.4.4, if none existed yet, create one:
        name: php5.4.4
        PHP home: <MAMP_PATH>/bin/php/php5.4.4/bin
        Debugger； Xdebug

2.1 set up Xdebug：

    integrate Xdebug with MAMP:
        edit file: "<MAMP_PATH>/bin/php/php5.4.4/conf/php.ini"
            add the following lines to Xdebug section:
                zend_extension="/Applications/MAMP/bin/php/php5.4.4/lib/php/extensions/no-debug-non-zts-20100525/xdebug.so"
                xdebug.remote_enable=1
                xdebug.remote_host="localhost"
                xdebug.remote_port=9000
                xdebug.profiler_enable=1


3. set up PhpUnit
------------------------------
3.1 Installation

MAMP already has Pear installed. It's used to install PhpUnit.
Commands:
pear config-set auto_discover 1
pear install pear.phpunit.de/PHPUnit

3.2 Configuration
goto: PhpStorm -> Settings -> PHP -> Interpreter,
Add pear path to 'Include Path'.
