#!/usr/bin/env python
#-*-coding:utf-8-*-
import os
import subprocess
import time
import datetime
import global_logs
from datetime import *
import global_functions as func
import global_time as func_time

log_level="debug"
log_config=global_logs.CLogConfig(level=log_level)
log_name="./log/alarm_redis.log"
log_config._path="%s.%s"%(log_name,func_time.today())
log=global_logs.initLog(log_config,logmodule="alarm")
if log == None:
    print "init data back log error!"

def getTimeInterVal(t_convergent_type,t_alarm_interval,t_alarm_num,t_alarm_strike_time):
	if t_convergent_type==0:
		return t_alarm_strike_time+t_alarm_interval*t_alarm_num
        elif t_convergent_type==1:
		return t_alarm_strike_time+t_alarm_interval*(t_alarm_num*(t_alarm_num+1))/2
        elif t_convergent_type==2:
		return int(t_alarm_strike_time+t_alarm_interval*(2**t_alarm_num-1))
        elif t_convergent_type==3:       
		return int(t_alarm_strike_time+t_alarm_interval*(t_alarm_interval**t_alarm_num-1)/(t_alarm_interval-1))
        else:
		#convergent_type error! not in (0 1 2 3)
		return 0

def get_alarm_redis_server_status():
    sql="select a.application_id,a.server_id,a.create_time,a.connect,a.info from redis_server_status a, servers b where a.server_id=b.id and b.send_mail='1' and b.is_delete=0 and b.status=1;"
    result=func.mysql_query(sql)
    if result <> 0:
        for line in result:
            application_id=line[0]
            server_id=line[1]
            create_time=line[2]
            connect=line[3]
            info=line[4]
            if connect != "success":
                sql="insert into alarm(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail) values(%s,%s,%s,%s,%s,%s,%s,%s,%s);"
                message="ERROR:Redis连接失败 "+info
                param=(application_id,server_id,create_time,'redis','connect',connect,'error',message,'1')
                func.mysql_exec(sql,param)
            else:
                pass
    else:
       log.error("ERROR:exe sql failed, %s"%(sql))
       pass


def get_alarm_redis_run_status():
    sql="select a.application_id,a.server_id,a.create_time,a.max_clients,a.connected_clients,ifnull(round(100*a.connected_clients/a.max_clients,2),0),c.threshold_connections from redis_run_status a,redis_server_status b,servers c where a.server_id=b.server_id and a.server_id=c.id and b.connect='success' and c.send_mail='1' and c.is_delete=0 and c.status=1 and c.alarm_connections=1;"
    result=func.mysql_query(sql)
    if result <> 0:
        for line in result:
            application_id=line[0]
            server_id=line[1]
            create_time=line[2]
            max_clients=line[3]
            connected_clients=line[4]
            connection_use_rate=line[5]
            threshold_connections=line[6]

            if int(connection_use_rate)>=int(threshold_connections):
                sql="insert into alarm(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail) values(%s,%s,%s,%s,%s,%s,%s,%s,%s);"
                message="WARN:Redis连接数过多,已用数"+str(connected_clients)+",使用率" + str(connection_use_rate) + "%>=" + threshold_connections +"%"
                param=(application_id,server_id,create_time,'redis','connections',connected_clients,'warning',message,'1') 
                func.mysql_exec(sql,param)
    else:
       pass

def get_alarm_redis_resource_status():
    sql="select a.application_id,a.server_id,a.create_time,a.max_memory,a.used_memory,ifnull(round(100*a.used_memory/a.max_memory,2),0),c.threshold_used_memory from redis_resource_status a,redis_server_status b,servers c where a.server_id=b.server_id and a.server_id=c.id and b.connect='success' and c.send_mail='1' and c.is_delete=0 and c.status=1 and c.alarm_used_memory=1;"
    result=func.mysql_query(sql)
    if result <> 0:
        for line in result:
            application_id=line[0]
            server_id=line[1]
            create_time=line[2]
            max_memory=line[3]
            used_memory=line[4]
            memory_use_rate=line[5]
            threshold_used_memory=line[6]

            if int(memory_use_rate)>=int(threshold_used_memory):
                sql="insert into alarm(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail) values(%s,%s,%s,%s,%s,%s,%s,%s,%s);"
                message="WARN:Redis内存使用过多,已用"+str(int(used_memory/1024/1024))+"M,使用率" + str(memory_use_rate) + "%>=" + threshold_used_memory +"%"
                param=(application_id,server_id,create_time,'redis','memory',used_memory,'warning',message,'1') 
                func.mysql_exec(sql,param)
    else:
       pass


def get_alarm_mysql_slowquerys():
    sql="select b.application_id,a.server_id,a.modify_time,(ts_cnt_sum-ts_cnt_sum_last) as ts_cnt,if((ts_cnt_sum-ts_cnt_sum_last)>0,(Query_time_sum-Query_time_sum_last)/(ts_cnt_sum-ts_cnt_sum_last),'0') as query_time_avg,b.send_mail,b.alarm_slow_querys,b.threshold_slow_querys from mysql_slow_query_status a,servers b where a.server_id=b.id and b.send_mail='1';"
    result=func.mysql_query(sql)
    if result <> 0:
	for line in result:
	    application_id=line[0]
	    server_id=line[1]
	    create_time=line[2]
	    ts_cnt=line[3]
	    query_time_avg=line[4]
	    send_mail=line[5]
	    alarm_slow_querys=line[6]
	    threshold_slow_querys=line[7]

	    if int(alarm_slow_querys)==1:
		if int(ts_cnt)>=int(threshold_slow_querys):
		    sql="insert into alarm(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail) values(%s,%s,%s,%s,%s,%s,%s,%s,%s);"
		    message="WARN:慢查询语句过多 " + str(ts_cnt) + ">=" + str(threshold_slow_querys)
		    param=(application_id,server_id,create_time,'mysql','slow_query',ts_cnt,'warning',message,send_mail)
		    func.mysql_exec(sql,param)
    else:
	pass

def get_alarm_redis_replication():
    sql="select a.application_id,a.server_id,a.create_time,a.master_link_status,a.delay,c.alarm_repl_delay,c.threshold_repl_delay from redis_replication a,redis_server_status b,servers c where a.server_id=b.server_id and a.server_id=c.id and a.is_slave=1 and b.connect='success' and c.send_mail='1' and c.is_delete=0 and c.status=1 and c.alarm_repl_status=1;"
    result=func.mysql_query(sql)
    if result <> 0:
        for line in result:
            application_id=line[0]
            server_id=line[1]
            create_time=line[2]
            master_link_status=line[3]
            delay=line[4]
            alarm_repl_delay=line[5]
            threshold_repl_delay=line[6]

            if master_link_status!="up":
               sql="insert into alarm(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail) values(%s,%s,%s,%s,%s,%s,%s,%s,%s);"
               message="ERROR:Redis复制停止 master_link_status:"+master_link_status
               param=(application_id,server_id,create_time,'redis','replication',master_link_status,'error',message,'1')
               func.mysql_exec(sql,param)
            else:
                if (alarm_repl_delay=="1") and (int(delay)>=int(threshold_repl_delay)):
                    sql="insert into alarm(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail) values(%s,%s,%s,%s,%s,%s,%s,%s,%s);"
                    message="WARN:Redis复制延迟过大 "+delay+">="+threshold_repl_delay
                    param=(application_id,server_id,create_time,'redis','delay',delay,'warning',message,'1')
                    func.mysql_exec(sql,param)   
    else:
       pass

def get_alarm_last_status():
	sql="delete from alarm_last_status where server_id not in (select distinct server_id from alarm);"
	func.mysql_exec(sql,'')
	
	sql="update alarm_last_status set connect='',status_connections='',status_used_memory='',status_replication_relay='';"
	func.mysql_exec(sql,'')
	
	#insert new warn
	sql="insert into alarm_last_status(application_id,server_id) select distinct application_id,server_id from alarm where server_id not in (select distinct server_id from alarm_last_status);"
	func.mysql_exec(sql,'')
	
	#old warn, update
	sql="select application_id,server_id,alarm_type,alarm_value,level,message,send_mail from alarm where server_id in (select distinct server_id from alarm_last_status);"
	result=func.mysql_query(sql)
	if result <> 0:
		for line in result:
			update_sql="update alarm_last_status set "
			application_id=line[0]
			server_id=line[1]
			alarm_type=line[2]
			alarm_value=line[3]
			level=line[4]
			message=line[5]
			send_mail=line[6]
			if alarm_type=="connect":
				update_sql=update_sql + "connect=\"" + message + "\","
			elif alarm_type=="connections":
				update_sql=update_sql + "connections='" + alarm_value + "',status_connections='" + message + "',"
			elif alarm_type=="memory":
				update_sql=update_sql + "used_memory='" + alarm_value + "',status_used_memory='" + message + "',"
			elif alarm_type=="delay":
				update_sql=update_sql + "replication_relay='" + alarm_value + "',status_replication_relay='" + message + "',"
			elif alarm_type=="replication":
				update_sql=update_sql + "replication_relay='-1',status_replication_relay='" + message + "',"

			update_sql=update_sql + "send_alarm='" + str(send_mail) + "' where application_id='" + str(application_id) + "' and server_id='" + str(server_id) + "';"
			log.debug("update_sql:%s"%(update_sql))
			func.mysql_exec(update_sql,'')
	
	#should not appear, send alarm to me
	sql="select application_id,server_id,alarm_type,alarm_value,level,message,send_mail from alarm where server_id not in (select distinct server_id from alarm_last_status);"
	result=func.mysql_query(sql)
	if result <> 0:
		mailto_list = func.get_option('mail_to_list')
		mail_subject="redis monitor error"
		mail_content="this should not appear:"+sql
		func.send_alarm(0,mailto_list,mail_subject,mail_content.encode('utf-8'))

def pingServerCall(server):
    rr=True
    fnull = open(os.devnull, 'w')
    result = subprocess.call('ping '+server+' -c 2', shell = True, stdout = fnull, stderr = fnull)
    if result:
	rr=False
        #print '服务器%s ping fail' % server
    else:
	rr=True
        #print '服务器%s ping ok' % server
    fnull.close()
    return rr
	
def send_warn():
    #server_id not delete???
    sql="select alarm_last_status.application_id,app.display_name application,alarm_last_status.server_id,servers.host,servers.port,alarm_last_status.modify_time,servers.alarm_interval,servers.alarm_type,servers.converge_type,servers.alarm_person,servers.send_mail,alarm_last_status.connect,alarm_last_status.status_connections,alarm_last_status.status_used_memory,alarm_last_status.status_binlog_count,alarm_last_status.status_innodb_space_free,alarm_last_status.status_replication_relay,alarm_last_status.alarm_num,alarm_last_status.time_error_continue,alarm_last_status.slow_querys,alarm_last_status.status_slow_querys,alarm_last_status.error_log,alarm_last_status.status_error_log from alarm_last_status left join servers on alarm_last_status.server_id=servers.id left join application app on servers.application_id=app.id;"
    result=func.mysql_query(sql)
    if result <> 0:
	domain="www.oa.com"
	if(pingServerCall(domain)==False):
            #系统告警接收人
	    mailto_list = func.get_option('mail_to_list')
            mail_subject="redis monitor error"
            mail_content="ping failed:"+domain +",do not send warn..."
            func.send_alarm(0,mailto_list,mail_subject,mail_content.encode('utf-8'))
	    return None
        #全局配置--发送告警
        send_alarm_mail = func.get_option('send_alarm_mail')
        #全局配置--报警检测
	frequency_alarm = func.get_option('frequency_alarm')
        #mail_to_list = func.get_option('mail_to_list')
        #mailto_list=mail_to_list.split(';')
        for line in result:
            application_id=line[0]
            application=line[1]
            server_id=line[2]
            host=line[3]
            port=line[4]
            create_time=line[5]
            alarm_interval=line[6]
            alarm_type=line[7]
            converge_type=line[8]
            alarm_person=line[9]
            send_alarm=line[10]
            connect=line[11]
	    status_connections=line[12]
    	    status_used_memory=line[13]
    	    status_binlog_count=line[14]
    	    status_innodb_space_free=line[15]
    	    status_replication_relay=line[16]
    	    alarm_num=line[17]
    	    time_error_continue=line[18]
	    slow_querys=line[19]
	    status_slow_querys=line[20]
	    error_log=line[21]
	    status_error_log=line[22]
    	    		
    	    warn_content=application+'-'+host+':'+port
    	    eStr="ERROR:"
    	    wStr="WARN:"
    	    if (eStr in connect) or (wStr in connect):
    	    	warn_content=warn_content+' '+connect
    	    else:
    	    	if (eStr in status_connections) or (wStr in status_connections):
    	    		warn_content=warn_content+' '+status_connections
    	    	if (eStr in status_used_memory) or (wStr in status_used_memory):
    	    		warn_content=warn_content+' '+status_used_memory
    	    	if (eStr in status_binlog_count) or (wStr in status_binlog_count):
    	    		warn_content=warn_content+' '+status_binlog_count
    	    	if (eStr in status_innodb_space_free) or (wStr in status_innodb_space_free):
    	    		warn_content=warn_content+' '+status_innodb_space_free
    	    	if (eStr in status_replication_relay) or (wStr in status_replication_relay):
    	    		warn_content=warn_content+' '+status_replication_relay
    	    	if (eStr in status_slow_querys) or (wStr in status_slow_querys):
    	    		warn_content=warn_content+' '+status_slow_querys
    	    	if (eStr in status_error_log) or (wStr in status_error_log):
    	    		warn_content=warn_content+' '+status_error_log
    	    warn_content=warn_content+' ['+create_time.strftime('%Y-%m-%d %H:%M:%S')+']'
            #warn_content=warn_content.encode('utf-8')
            #print send_alarm_mail
            #print send_alarm
            #print warn_content
	    #print("WARN:%s  send alarm:%s  alarm:%s" %(warn_content,send_alarm_mail,send_alarm))
    	    mailto_list=alarm_person
    	    mail_subject="REDIS WARNING"
    	    
	    send_mail_status=0
    	    if send_alarm_mail=='1':
    	    	if send_alarm==1:
    	    		next_alarm_time=getTimeInterVal(converge_type,alarm_interval,alarm_num,0);
			if(func.test_flag):
			    	log.debug("time_error_continue:%s next_alarm_time:%s converge_type:%s alarm_interval:%s alarm_num:%s"%(str(time_error_continue),str(next_alarm_time),converge_type,str(alarm_interval),str(alarm_num)))
    	    		if time_error_continue >= next_alarm_time:
    	    			result=func.send_alarm(alarm_type,mailto_list,mail_subject,warn_content.encode('utf-8'))
    	    			if result:
    	    				send_mail_status=1
    	    			else:
    	    				send_mail_status=0
    	    			sql="update alarm_last_status set alarm_num=alarm_num+1 where application_id='"+str(application_id)+"' and server_id='"+str(server_id)+"'"
    	    			func.mysql_exec(sql,'')
				log.warning("send alarm:alarm_type:%s  mailto_list:%s mail_subject:%s  warn_content:%s"%(str(alarm_type),mailto_list,mail_subject,warn_content.encode('utf-8')))
			else:
				sql="delete from alarm  where application_id='"+str(application_id)+"' and server_id='"+str(server_id)+"'"
				func.mysql_exec(sql,'')
    	    	else:
    	    		send_mail_status=0
    	    else:
    	    	send_mail_status=0
    	    			
    	    sql="update alarm_last_status set time_error_continue=time_error_continue+"+str(frequency_alarm)+" where application_id='"+str(application_id)+"' and server_id='"+str(server_id)+"'"
	    func.mysql_exec(sql,'')
	    #param=(str(application_id),str(server_id))
            if send_mail_status==1:
		sql="insert into alarm_history(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail,send_mail_status) select application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail,1 from alarm where application_id='"+str(application_id)+"' and server_id='"+str(server_id)+"';"
		func.mysql_exec(sql,'')
            elif send_mail_status==0:
		sql="insert into alarm_history(application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail,send_mail_status) select application_id,server_id,create_time,db_type,alarm_type,alarm_value,level,message,send_mail,0 from alarm where application_id='"+str(application_id)+"' and server_id='"+str(server_id)+"';"
		func.mysql_exec(sql,'')
            sql="delete from alarm where application_id='"+str(application_id)+"' and server_id='"+str(server_id)+"';"
	    func.mysql_exec(sql,'')
    else:
        pass

def main():
    log.info("check alarm info begin...")
    get_alarm_redis_server_status()
    get_alarm_redis_run_status()
    get_alarm_redis_resource_status()
    get_alarm_redis_replication()
    #get_alarm_redis_slowquerys()
    get_alarm_last_status()
    send_warn()
    log.info("check alarm info end...")

if __name__ == '__main__':
    main()
