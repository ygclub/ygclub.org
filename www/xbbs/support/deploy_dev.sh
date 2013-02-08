#!/bin/bash


function copy_config_files {
    echo "################${FUNCNAME[0]} start################"

    cp ../config/config_global_default.php   ../config/config_global.php
    cp ../config/config_ucenter_default.php   ../config/config_ucenter.php
    cp ../uc_server/data/config_default.inc.php   ../uc_server/data/config.inc.php

    echo "################${FUNCNAME[0]} finished################"

}

function init_db {
    echo "################${FUNCNAME[0]} start################"

    mysqladmin -u root -p'root' password ''
    mysql -v -uroot < ../install/data/dev_ygclub.sql

    echo "################${FUNCNAME[0]} finished################"
}



function show_help {
	echo "Usage: deploy_dev.sh [COMMAND]"
	echo "COMMAND"
	echo "initdb: create database"
	echo "cfg: copy configuration file"
    echo "go: initdb & cfg"
}


function main {
	case $1 in
		initdb) init_db;;
		cfg) copy_config_files;;
		go) init_db && copy_config_files;;
		*) show_help && exit 1;;
	esac
}


main $@
