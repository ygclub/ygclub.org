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
Start servers on MAMP

2. install database
------------------------------
Go to ggclub/www/xbbs/suport
Run: ./deploy_dev.sh initdb

3. rename configuration files:
------------------------------
Go to ggclub/www/xbbs/suport
Run: ./deploy_dev.sh cfg