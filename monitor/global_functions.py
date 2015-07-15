#!/usr/bin/env python
#-*-coding:utf-8-*-

import MySQLdb
import string
import sys 
reload(sys) 
sys.setdefaultencoding('utf8')
import ConfigParser
import smtplib
import os
import time
import datetime

from email.message import Message
from email.MIMEText import MIMEText
from email.Header import Header

#test_flag=False
test_flag=True
def get_config(group,config_name):
    config = ConfigParser.ConfigParser()
    config.readfp(open('./conf/config.ini','rw'))
    config_value=config.get(group,config_name).strip(' ').strip('\'').strip('\"')
    return config_value


host = get_config('monitor_server','host')
port = get_config('monitor_server','port')
user = get_config('monitor_server','user')
passwd = get_config('monitor_server','passwd')
dbname = get_config('monitor_server','dbname')

def mysql_exec(sql,param):
    conn=MySQLdb.connect(host=host,user=user,passwd=passwd,port=int(port),connect_timeout=5,charset='utf8')
    conn.select_db(dbname)
    cursor = conn.cursor()
    if param <> '':
        cursor.execute(sql,param)
    else:
        cursor.execute(sql)
    conn.commit()
    cursor.close()
    conn.close()

def mysql_query(sql):
    conn=MySQLdb.connect(host=host,user=user,passwd=passwd,port=int(port),connect_timeout=5,charset='utf8')
    conn.select_db(dbname)
    cursor = conn.cursor()
    count=cursor.execute(sql)
    if count == 0 :
        result=0
    else:
        result=cursor.fetchall()
    return result
    cursor.close()
    conn.close()

def get_option(key):
    conn=MySQLdb.connect(host=host,user=user,passwd=passwd,port=int(port),connect_timeout=5,charset='utf8')
    conn.select_db(dbname)
    cursor = conn.cursor()
    sql="select value from options where name=+'"+key+"'"
    count=cursor.execute(sql)
    if count == 0 :
        result=0
    else:
        result=cursor.fetchone()
    return result[0]
    cursor.close()
    conn.close()

def check_string_empty(str):
    if str == "" or str == "null" or str == "None":
        return "---"
    else:
        return str

def check_integer_empty(str):
    if str == "" or str == "null" or str == "None":
        return "0"
    else:
        return str

def send_alarm(alarm_type,to_list,sub,tContent):
    content=tContent.encode('gbk')
    #你的短信告警接口
    if alarm_type==1:
        print("%s: send sms to %s: %s" % (time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()),to_list,tContent))
        #os.system("")
    #你的微信告警接口
    elif alarm_type==2:
        print("%s: send weixin to %s: %s" % (time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()),to_list,tContent))
        #os.system("")

    content_file='/tmp/redis_alarm_mail.txt'
    file_object = open(content_file, 'w')
    file_object.write(content)
    file_object.write("   ---redisMM %s"% (time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()),) )
    file_object.close()
    #你的邮件告警接口
    print("%s: send mail to %s: %s" % (time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()),to_list,tContent))
    #os.system("")
    return 1 

