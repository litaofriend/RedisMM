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
#import sys;
#reload(sys);
#print sys.getdefaultencoding();
#sys.setdefaultencoding('utf-8');

def send_backup_alarm(alarm_flag,mailto_list,warn_content,application_id,server_id,db_name,current_date,log):
    alarm_type=0
    mail_subject="REDIS BACKUP WARN"
    create_time=time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
    db_type="redis"
    alarm_type="backup"
    alarm_value="fail"
    level="warning"
    if alarm_flag== "y":
        result=func.send_alarm(alarm_type,mailto_list,mail_subject,warn_content.encode('utf-8'))
        if result:
                send_mail_status=1
        else:
                send_mail_status=0
        log.error("send alarm:alarm_type:%s  mailto_list:%s mail_subject:%s  warn_content:%s"%(str(alarm_type),mailto_list,mail_subject,warn_content.encode('utf-8')))
        #print("send alarm:alarm_type:%s  mailto_list:%s mail_subject:%s  warn_content:%s"%(str(alarm_type),mailto_list,mail_subject,warn_content.encode('utf-8')))
        sql="insert into alarm_history(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail,send_mail_status) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)" 
        param=(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,warn_content,'1',send_mail_status)
        func.mysql_exec(sql,param)
    sql="replace into redis_coldback_info(date,server_id,db_name,file_name,suc_flag) values(%s,%s,%s,%s,%s)"
    param=(current_date,server_id,db_name,'---','n')
    func.mysql_exec(sql,param)

def back_aof_file(server_id,display_name,port,IP,ssh_port,ssh_user,ssh_passwd,back_IP,back_ssh_port,back_ssh_user,back_ssh_passwd,aof_enabled,aof_last_bgrewrite_status,aof_last_write_status,aof_current_size,dir,back_path,current_date,alarm_flag,charge_person,application_id,log):
    send_alarm_flag=False
    mail_content="RedisBackup_"+IP+"_"+port+"("+display_name+"):"
    if aof_enabled == "0":
        mail_content=mail_content+"aof save not config. "
        send_alarm_flag=True
    if aof_last_bgrewrite_status != "ok" or aof_last_write_status != "ok":
        mail_content=mail_content+"aof_last_bgrewrite_status:"+aof_last_bgrewrite_status+",aof_last_write_status:"+aof_last_write_status+". "
        send_alarm_flag=True
    #check file size
    aof_file=dir+"/"+"appendonly.aof"
    tCmd="/usr/bin/expect exec_user_cmd.exp "+IP+" "+ssh_port+" "+ssh_user+" '"+ssh_passwd+"' 'du -b "+aof_file+"' 60"
    log.debug("tCmd:%s"%(tCmd))
    (status, output) = commands.getstatusoutput(tCmd)
    log.debug("output:%s"%(output))
    if output.find(aof_file) >=0 and output.find("No such file or directory") <0 and output.find("cannot access") <0:
        lines=output.splitlines()
        for line in lines:
            if re.search(aof_file,line):
                file_size=line.split('\t')[0]
                if int(file_size)>0:
                    #back file
                    remote_aof_file_name=IP+"_"+port+"_"+"appendonly.aof"+"."+current_date
                    remote_aof_file=back_path+"/"+remote_aof_file_name
                    tCmd="/usr/bin/expect exec_scp_cmd.exp "+IP+" "+ssh_port+" "+ssh_user+" '"+ssh_passwd+"' '"+aof_file+"' "+back_IP+" "+back_ssh_port+" "+back_ssh_user+" '"+back_ssh_passwd+"' '"+remote_aof_file+"' 300"
                    log.debug("tCmd:%s"%(tCmd))
                    (status2, output2) = commands.getstatusoutput(tCmd)
                    log.debug("output:%s"%(output2))
                    if int(status2)!=0:
                        log.debug("exe error!%s" %(tCmd))
                    #check remote file
                    tCmd="/usr/bin/expect exec_user_cmd.exp "+back_IP+" "+back_ssh_port+" "+back_ssh_user+" '"+back_ssh_passwd+"' 'du -b "+remote_aof_file+"' 60"
                    log.debug("tCmd:%s"%(tCmd))
                    (status3, output3) = commands.getstatusoutput(tCmd)
                    log.debug("output:%s"%(output3))
                    if output3.find(remote_aof_file) >=0:
                        lines=output3.splitlines()
                        for line in lines:
                            if re.search(remote_aof_file,line):
                                file_size=line.split('\t')[0]
                                if int(file_size)>0:
                                    #replace info to redis_coldback_info
                                    sql="replace into redis_coldback_info(date,server_id,db_name,file_name,db_size,suc_flag) values(%s,%s,%s,%s,%s,%s)"
                                    param=(current_date,server_id,'aof',remote_aof_file_name,file_size,'y')
                                    func.mysql_exec(sql,param)
                                else:
                                    #remote file size = 0
                                    sql="replace into redis_coldback_info(date,server_id,db_name,file_name,db_size,suc_flag) values(%s,%s,%s,%s,%s,%s)"
                                    param=(current_date,server_id,'aof',remote_aof_file_name,file_size,'n')
                                    func.mysql_exec(sql,param)
                                    mail_content=mail_content+"size of remote aof file<=0,"+remote_aof_file_name+". "
                                    send_alarm_flag=True
                else:
                    #file size = 0
                    mail_content=mail_content+"size of aof file<=0,"+aof_file+". "
                    send_alarm_flag=True
    else:
        #file not found
        mail_content=mail_content+"not found aof file:"+aof_file+". "
        send_alarm_flag=True
    #print("mail_content:%s"%(mail_content))
    if send_alarm_flag == True:
        send_backup_alarm(alarm_flag,charge_person,mail_content,application_id,server_id,'aof',current_date,log)

def back_rdb_file(server_id,display_name,port,IP,ssh_port,ssh_user,ssh_passwd,back_IP,back_ssh_port,back_ssh_user,back_ssh_passwd,rdb_enabled,rdb_last_bgsave_status,dir,rdb_dbfilename,back_path,current_date,alarm_flag,charge_person,application_id,log):
    send_alarm_flag=False
    mail_content="RedisBackup_"+IP+"_"+port+"("+display_name+"):"
    #if rdb_enabled == "0":
    #    mail_content=mail_content+"rdb save not config. "
    #    send_alarm_flag=True
    if rdb_last_bgsave_status != "ok":
        mail_content=mail_content+"rdb_last_bgsave_status:"+rdb_last_bgsave_status+". "
        send_alarm_flag=True
    #check file size
    rdb_file=dir+"/"+rdb_dbfilename
    tCmd="/usr/bin/expect exec_user_cmd.exp "+IP+" "+ssh_port+" "+ssh_user+" '"+ssh_passwd+"' 'du -b "+rdb_file+"' 60"
    log.debug("tCmd:%s"%(tCmd))
    (status, output) = commands.getstatusoutput(tCmd)
    log.debug("output:%s"%(output))
    if output.find(rdb_file) >=0 and output.find("No such file or directory") <0 and output.find("cannot access") <0:
        lines=output.splitlines()
        for line in lines:
            if re.search(rdb_file,line):
                file_size=line.split('\t')[0]
                if int(file_size)>0:
                    #back file
                    remote_rdb_file_name=IP+"_"+port+"_"+rdb_dbfilename+"."+current_date
                    remote_rdb_file=back_path+remote_rdb_file_name
                    tCmd="/usr/bin/expect exec_scp_cmd.exp "+IP+" "+ssh_port+" "+ssh_user+" '"+ssh_passwd+"' '"+rdb_file+"' "+back_IP+" "+back_ssh_port+" "+back_ssh_user+" '"+back_ssh_passwd+"' '"+remote_rdb_file+"' 300"
                    log.debug("tCmd:%s"%(tCmd))
                    (status2, output2) = commands.getstatusoutput(tCmd)
                    log.debug("output:%s"%(output2))
                    if int(status2)!=0:
                        log.error("exe error!%s" %(tCmd))
                    #check remote file
                    tCmd="/usr/bin/expect exec_user_cmd.exp "+back_IP+" "+back_ssh_port+" "+back_ssh_user+" '"+back_ssh_passwd+"' 'du -b "+remote_rdb_file+"' 60"
                    log.debug("tCmd:%s"%(tCmd))
                    (status3, output3) = commands.getstatusoutput(tCmd)
                    log.debug("output:%s"%(output3))
                    if output3.find(remote_rdb_file) >=0:
                        lines=output3.splitlines()
                        for line in lines:
                            if re.search(remote_rdb_file,line):
                                file_size=line.split('\t')[0]
                                if int(file_size)>0:
                                    #replace info to redis_coldback_info
                                    sql="replace into redis_coldback_info(date,server_id,db_name,file_name,db_size,suc_flag) values(%s,%s,%s,%s,%s,%s)"
                                    param=(current_date,server_id,'rdb',remote_rdb_file_name,file_size,'y')
                                    func.mysql_exec(sql,param)
                                else:
                                    #remote file size = 0
                                    sql="replace into redis_coldback_info(date,server_id,db_name,file_name,db_size,suc_flag) values(%s,%s,%s,%s,%s,%s)"
                                    param=(current_date,server_id,'rdb',remote_rdb_file_name,file_size,'n')
                                    func.mysql_exec(sql,param)
                                    mail_content=mail_content+"size of remote rdb file<=0,"+remote_rdb_file_name+". "
                                    send_alarm_flag=True
                else:
                    #file size = 0
                    mail_content=mail_content+"size of rdb file<=0,"+rdb_file+". "
                    send_alarm_flag=True
    else:
        #file not found
        mail_content=mail_content+"not found rdb file:"+rdb_file+". "
        send_alarm_flag=True
    #print("mail_content:%s"%(mail_content))
    if send_alarm_flag == True:
        send_backup_alarm(alarm_flag,charge_person,mail_content,application_id,server_id,'rdb',current_date,log)

def backup_data(log):
    current_minute=time.strftime('%H:%M', time.localtime())
    current_date=time.strftime('%Y-%m-%d', time.localtime())
    #test
    #sql="delete from redis_coldback_info where server_id='3';"
    #func.mysql_exec(sql,'')
    sql="select server_id,IP,ssh_port,ssh_user,ssh_passwd,back_IP,back_ssh_port,back_ssh_user,back_ssh_passwd,back_path,db_name,back_cycle,save_number,alarm_flag,charge_person from redis_coldback_config where back_flag='y' and back_time<='"+current_minute+"' and server_id not in (select distinct server_id from redis_coldback_info where suc_flag='y' and date='"+current_date+"');"
    servers=func.mysql_query(sql)
    #print sql
    #frequency_monitor = func.get_option('frequency_monitor')
    if servers:
	 log.info("need backup servers: %s" %(servers,));
         #print("%s: check_mysql_status controller started..." % (time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()),));
         plist = []
         for row in servers:
             server_id=str(row[0])
             IP=row[1]
             ssh_port=row[2]
             ssh_user=row[3]
             ssh_passwd=row[4]
             back_IP=row[5]
             back_ssh_port=row[6]
             back_ssh_user=row[7]
             back_ssh_passwd=row[8]
             back_path=row[9]
             db_name=row[10]
             back_cycle=int(row[11])
             save_number=row[12]
             alarm_flag=row[13]
             charge_person=row[14]
             #check if need to back
             if back_cycle > 1:
                 back_date=str(func_time.get_day_of_day(back_cycle*-1))
                 sql="select count(1) as num from redis_coldback_info where server_id='"+server_id+"' and  suc_flag='y' and date>='"+back_date+"'"
                 result=func.mysql_query(sql)
                 if result:
                     num=result[0]
                     if num >=1:
                         continue
             #get host:port of server
             mail_content="RedisBackup:"
             sql="select a.display_name,b.host,b.port,b.application_id from application a,servers b where a.id=b.application_id and b.id='"+server_id+"';"
             #sql="set names utf8;select a.display_name,b.host,b.port,b.application_id from application a,servers b where a.id=b.application_id and b.id='"+server_id+"';"
             result=func.mysql_query(sql)
             if result:
                 display_name=result[0][0]
                 ##host equal with IP
                 host=result[0][1]
                 port=result[0][2]
                 application_id=result[0][3]
             else:
                 mail_content=mail_content+"not found server_id:"+server_id
                 #send warn to system charge person
                 application_id=0
                 send_backup_alarm(alarm_flag,charge_person,mail_content,application_id,server_id,db_name,current_date,log)
                 continue

             mail_content="RedisBackup_"+IP+"_"+port+"("+display_name+"):"
             #get redis dir
             sql="select dir from redis_server_status_history where server_id='"+server_id+"' order by id desc limit 1;"
             result=func.mysql_query(sql)
             if result and result[0][0]!="---":
                 dir=result[0][0]
                 #print("dir:%s"%(dir))
             else:
                 mail_content=mail_content+"redis dir not found"
                 #send warn
                 send_backup_alarm(alarm_flag,charge_person,mail_content,application_id,server_id,db_name,current_date,log)
                 continue

             #get config of rdb/aof
             sql="select rdb_enabled,rdb_dbfilename,rdb_last_bgsave_status,aof_enabled,aof_last_bgrewrite_status,aof_last_write_status,aof_current_size from redis_persistence_status_history where server_id='"+server_id+"' order by YmdHi desc limit 1;"
             result=func.mysql_query(sql)
             if result:
                 rdb_enabled=result[0][0]
                 rdb_dbfilename=result[0][1]
                 rdb_last_bgsave_status=result[0][2]
                 aof_enabled=result[0][3]
                 aof_last_bgrewrite_status=result[0][4]
                 aof_last_write_status=result[0][5]
                 aof_current_size=result[0][6]
             else:
                 mail_content=mail_content+"redis persistence info not found"
                 #send warn
                 send_backup_alarm(alarm_flag,charge_person,mail_content,application_id,server_id,db_name,current_date)
                 continue

             #check passwd
             if ssh_passwd == "":
                 log.error("passwd not found,IP:%s:"%(IP))
                 continue    
             if back_ssh_passwd == "": 
                 log.error("passwd not found,IP:%s"%(back_IP))
                 continue

             #back data
             if db_name.find("rdb") >=0:
                  back_rdb_file(server_id,display_name,port,IP,ssh_port,ssh_user,ssh_passwd,back_IP,back_ssh_port,back_ssh_user,back_ssh_passwd,rdb_enabled,rdb_last_bgsave_status,dir,rdb_dbfilename,back_path,current_date,alarm_flag,charge_person,application_id,log)

             if db_name.find("aof") >=0:
                  back_aof_file(server_id,display_name,port,IP,ssh_port,ssh_user,ssh_passwd,back_IP,back_ssh_port,back_ssh_user,back_ssh_passwd,aof_enabled,aof_last_bgrewrite_status,aof_last_write_status,aof_current_size,dir,back_path,current_date,alarm_flag,charge_person,application_id,log)
                 

             #delete old data work in another program  --- clear_old_data.py

         #print("%s: check_mysql_status controller finished..." % (time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()),))

def delete_old_data(log):
    sql="select count(1),a.server_id,a.back_path,a.save_number,a.back_IP,a.back_ssh_port,a.back_ssh_user,a.back_ssh_passwd,b.db_name  from redis_coldback_config a,redis_coldback_info b where a.server_id=b.server_id  and b.suc_flag='y' and b.del_flag ='n' group by b.server_id,b.db_name;"
    result=func.mysql_query(sql)
    if result:
        for row in result:
            count=int(row[0])
            server_id=str(row[1])
            back_path=row[2]
            save_number=int(row[3])
            back_IP=row[4]
            back_ssh_port=row[5]
            back_ssh_user=row[6]
            back_ssh_passwd=row[7]
            db_name=row[8]
            if count > save_number:
                del_num=count-save_number
                sql="select file_name from redis_coldback_info where server_id='"+server_id+"' and db_name='"+db_name+"' and del_flag='n' order by date limit "+str(del_num)+";"
                file_name_del=func.mysql_query(sql)
                if file_name_del:
                    if back_ssh_passwd == "":
                        log.error("delete old data:passwd not found,server_id:%s,back_IP:%s"%(server_id,back_IP))
                        continue
                    for file in file_name_del:
                        file_name=file[0]
                        remote_file=back_path+"/"+file_name
                        tCmd="/usr/bin/expect exec_user_cmd.exp "+back_IP+" "+back_ssh_port+" "+back_ssh_user+" '"+back_ssh_passwd+"' 'rm -f "+remote_file+";ls -l "+remote_file+";' 120"
                        log.debug("tCmd:%s"%(tCmd))
                        (status, output) = commands.getstatusoutput(tCmd)
                        log.debug("output:%s"%(output))
                        if output.find(remote_file)>=0 and (output.find("No such file or directory")>=0 or output.find("cannot access")>=0):
                            sql="update redis_coldback_info set del_flag='y' where server_id='"+server_id+"' and db_name='"+db_name+"' and file_name='"+file_name+"';"
                            log.debug("sql:%s"%(sql))
                            func.mysql_exec(sql,'')
    return

def main():
    log_level="debug"
    log_config=global_logs.CLogConfig(level=log_level)
    log_name="./log/redis_data_back.log"
    log_config._path="%s.%s"%(log_name,func_time.today())
    log=global_logs.initLog(log_config,logmodule="back")
    if log == None:
        print "init data back log error!"

    log.info("backup data begin...")
    backup_data(log)
    log.info("delete old backup data")
    delete_old_data(log)
    log.info("backup data end...")

if __name__=='__main__':
    main()
