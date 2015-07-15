#!/usr/bin/env python
#coding:utf-8
import os
import sys
import string
import time
import datetime
import MySQLdb
import redis
import traceback
import commands
import re
import global_logs
import global_functions as func
import global_time as func_time
from multiprocessing import Process;

log_level="debug"
log_config=global_logs.CLogConfig(level=log_level)
log_name="./log/redis_history_clear.log"
log_config._path="%s.%s"%(log_name,func_time.today())
log=global_logs.initLog(log_config,logmodule="clear")
if log == None:
    print "init data back log error!"

def clear_history_data():
    history_save_days =int(func.get_option('history_save_days'))
    if history_save_days >= 1:
        old_date=str(func_time.get_day_of_day(history_save_days*-1))
        sql="delete from redis_coldback_info where date <'"+old_date+"'"
        log.debug("sql:%s"%(sql))
        func.mysql_exec(sql,'')
        sql="delete from alarm_history where left(create_time,10)<'"+old_date+"'"
        log.debug("sql:%s"%(sql))
        func.mysql_exec(sql,'')
        sql="delete from redis_keyspace_history where left(create_time,10)<'"+old_date+"'"
        log.debug("sql:%s"%(sql))
        func.mysql_exec(sql,'')
        sql="delete from redis_persistence_status_history where left(create_time,10)<'"+old_date+"'"
        log.debug("sql:%s"%(sql))
        func.mysql_exec(sql,'')
        sql="delete from redis_replication_history where left(create_time,10)<'"+old_date+"'"
        log.debug("sql:%s"%(sql))
        func.mysql_exec(sql,'')
        sql="delete from redis_resource_status_history where left(create_time,10)<'"+old_date+"'"
        log.debug("sql:%s"%(sql))
        func.mysql_exec(sql,'')
        sql="delete from redis_run_status_history where left(create_time,10)<'"+old_date+"'"
        log.debug("sql:%s"%(sql))
        func.mysql_exec(sql,'')
        sql="delete from redis_server_status_history where left(create_time,10)<'"+old_date+"'"
        log.debug("sql:%s"%(sql))
        func.mysql_exec(sql,'')
        tCmd="rm -f ./log/*"+old_date
        log.debug("tCmd:%s"%(tCmd))
        os.system(tCmd)
    else:
        log.error("get history_save_days from options error!")

def main():
    log.info("clear history data begin...")
    clear_history_data()
    log.info("clear history data end...")

if __name__=='__main__':
    main()

