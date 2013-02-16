Installation steps
==================

Requirements
------------

* php 5.44
* apache server
* mysql

0. Install AMP
------------------------------
install MAMP(for mac), or LAMP(for linux), or WAMP(for windows)


1. Config AMP & start servers
------------------------------
Config MAMP, point apache's document root to ygclub/www.
Clear mysql password for MAMP:
Remove the "-proot" string in the following files:
    <MAMP_PATH>/bin:
    quickCheckMysqlUpgrade.sh
    repairMysql.sh
    start.sh
    startMysql.sh
    stop.sh
    stopMysql.sh
    upgradeMysql.sh
Open <MAMP_PATH>/bin/phpMyAdmin/libraries/config.default.php, and change "$cfg['Servers'][$i]['nopassword']" to true
Open <MAMP_PATH>/bin/mamp/index.php, and change $link to  "@mysql_connect(':<MAMP_PATH>/tmp/mysql/mysql.sock', 'root', '');"

Start servers on MAMP

2. install database
------------------------------
Go to ggclub/www/xbbs/suport
Run: ./deploy_dev.sh initdb

3. rename configuration files:
------------------------------
Go to ggclub/www/xbbs/suport
Run: ./deploy_dev.sh cfg

4. run phpunit in command line:
-------------------------------
phpunit --debug .
phpunit will run all the test(will name *Test.php) in the current directory recursively.
