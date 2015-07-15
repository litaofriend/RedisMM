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
import global_logs
import global_functions as func
import global_time as func_time
from multiprocessing import Process;

def check_redis_status(host,port,passwd,server_id,application_id,frequency_monitor,log):
    #print host
    redis_client = redis.StrictRedis(host=host, port=port, db=0, password=passwd,socket_timeout=3)
    try:
        redis_info=redis_client.info()
        current_config=redis_client.config_get()
        current_time=time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
        check_redis_server_status(server_id,application_id,redis_info,current_config,current_time,log)

        check_redis_persistence_status(server_id,application_id,redis_info,current_config,current_time,log)

        check_redis_resource_status(server_id,application_id,redis_info,current_config,current_time,log)

        check_redis_run_status(server_id,application_id,redis_info,current_config,current_time,frequency_monitor,log)

        check_redis_keyspace(server_id,application_id,redis_info,current_time,log)

        check_redis_replication(server_id,application_id,redis_info,current_time,log)
    except Exception,e:
        redis_client = redis.StrictRedis(host=host, port=port, db=0, password=passwd,socket_timeout=3)
        try:
            redis_info=redis_client.info()
            current_config=redis_client.config_get()
        except Exception,e:
            connect="fail"
            redis_version="---"
            redis_mode="---"
            multiplexing_api="---"
            process_id="0"
            run_id="---"
            uptime="0"
            config_file="---"
            current_time=time.strftime('%Y-%m-%d %H:%M:%S', time.localtime())
            info=e.args[0]
            #print("info:%s"%(e.args[0]))
            sql="insert into redis_server_status(server_id,application_id,connect,redis_version,redis_mode,multiplexing_api,process_id,run_id,uptime,config_file,info,create_time) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
            param=(server_id,application_id,connect,redis_version,redis_mode,multiplexing_api,process_id,run_id,uptime,config_file,info,current_time)
            func.mysql_exec(sql,param)
            log.error("ERROR: Get redis into failed,host:%s port:%s passwd:%s error:%s" % (host,port,passwd,traceback.format_exc()))
            return
    
def check_redis_server_status(server_id,application_id,redis_info,current_config,current_time,log):
    try:
        connect="success"
        dir=current_config.get("dir","---")
        redis_version=redis_info.get("redis_version","---")
        redis_mode=redis_info.get("redis_mode","---")
        multiplexing_api=redis_info.get("multiplexing_api","---")
        process_id=redis_info.get("process_id","0")
        run_id=redis_info.get("run_id","---")
        uptime=redis_info.get("uptime_in_seconds","0")
        config_file=redis_info.get("config_file","---")
        sql="insert into redis_server_status(server_id,application_id,connect,redis_version,redis_mode,multiplexing_api,process_id,run_id,uptime,config_file,dir,create_time) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        param=(server_id,application_id,connect,redis_version,redis_mode,multiplexing_api,process_id,run_id,uptime,config_file,dir,current_time)
        func.mysql_exec(sql,param)
    except Exception,e:
        log.error("ERROR: check_redis_server_status failed %s" % (traceback.format_exc()))
        return
            
def check_redis_persistence_status(server_id,application_id,redis_info,current_config,current_time,log):
    try:
        tmp_rdb_enabled=current_config.get("save","---")
        if tmp_rdb_enabled=="---" or tmp_rdb_enabled=="":
            rdb_enabled="0"
        else:
            rdb_enabled="1"
        rdb_dbfilename=current_config.get("dbfilename","---")
        rdb_changes_since_last_save=redis_info.get("rdb_changes_since_last_save","0")
        rdb_last_save_time=redis_info.get("rdb_last_save_time","0")
        rdb_last_bgsave_status=redis_info.get("rdb_last_bgsave_status","---")
        rdb_last_bgsave_time_sec=redis_info.get("rdb_last_bgsave_time_sec","0")
        aof_enabled=redis_info.get("aof_enabled","---")
        aof_last_rewrite_time_sec=redis_info.get("aof_last_rewrite_time_sec","0")
        aof_last_bgrewrite_status=redis_info.get("aof_last_bgrewrite_status","---")
        aof_last_write_status=redis_info.get("aof_last_write_status","---")
        if int(aof_enabled)==0 or aof_enabled=="---":
            aof_current_size="0"
        else:
            aof_current_size=redis_info.get("aof_current_size","0")
        sql="insert into redis_persistence_status(server_id,application_id,rdb_enabled,rdb_dbfilename,rdb_changes_since_last_save,rdb_last_save_time,rdb_last_bgsave_status,rdb_last_bgsave_time_sec,aof_enabled,aof_last_rewrite_time_sec,aof_last_bgrewrite_status,aof_last_write_status,aof_current_size,create_time) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        param=(server_id,application_id,rdb_enabled,rdb_dbfilename,rdb_changes_since_last_save,rdb_last_save_time,rdb_last_bgsave_status,rdb_last_bgsave_time_sec,aof_enabled,aof_last_rewrite_time_sec,aof_last_bgrewrite_status,aof_last_write_status,aof_current_size,current_time)
        func.mysql_exec(sql,param)
    except Exception,e:
        log.error("ERROR: check_redis_persistence_status failed %s" % (traceback.format_exc()))
        return

def check_redis_resource_status(server_id,application_id,redis_info,current_config,current_time,log):
    try:
        max_memory=current_config.get("maxmemory","0")
        used_memory=redis_info.get("used_memory","0")
        used_memory_rss=redis_info.get("used_memory_rss","0")
        used_memory_peak=redis_info.get("used_memory_peak","0")
        mem_fragmentation_ratio=redis_info.get("mem_fragmentation_ratio","0")
        used_cpu_sys=redis_info.get("used_cpu_sys","0")
        used_cpu_user=redis_info.get("used_cpu_user","0")
        used_cpu_sys_children=redis_info.get("used_cpu_sys_children","0")
        used_cpu_user_children=redis_info.get("used_cpu_user_children","0")
        sql="insert into redis_resource_status(server_id,application_id,max_memory,used_memory,used_memory_rss,used_memory_peak,mem_fragmentation_ratio,used_cpu_sys,used_cpu_user,used_cpu_sys_children,used_cpu_user_children,create_time) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        param=(server_id,application_id,max_memory,used_memory,used_memory_rss,used_memory_peak,mem_fragmentation_ratio,used_cpu_sys,used_cpu_user,used_cpu_sys_children,used_cpu_user_children,current_time)
        func.mysql_exec(sql,param)
    except Exception,e:
        log.error("ERROR: check_redis_resource_status failed %s" % (traceback.format_exc()))
        return

def check_redis_run_status(server_id,application_id,redis_info,current_config,current_time,frequency_monitor,log):
    try:
        max_clients=current_config.get("maxclients","10000")
        connected_clients=redis_info.get("connected_clients","0")
        blocked_clients=redis_info.get("blocked_clients","0")
        last_total_connections_received=redis_info.get("total_connections_received","0")
        last_total_commands_processed=redis_info.get("total_commands_processed","0")
        last_total_net_input_bytes=redis_info.get("total_net_input_bytes",'0')
        last_total_net_output_bytes=redis_info.get("total_net_output_bytes",'0')
        last_rejected_connections=redis_info.get("rejected_connections","0")
        last_expired_keys=redis_info.get("expired_keys","0")
        last_evicted_keys=redis_info.get("evicted_keys","0")
        last_keyspace_hits=redis_info.get("keyspace_hits","0")
        last_keyspace_misses=redis_info.get("keyspace_misses","0")
        sql="insert into redis_run_status(server_id,application_id,max_clients,connected_clients,blocked_clients,last_total_connections_received,last_total_commands_processed,last_total_net_input_bytes,last_total_net_output_bytes,last_rejected_connections,last_expired_keys,last_evicted_keys,last_keyspace_hits,last_keyspace_misses,create_time) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        param=(server_id,application_id,max_clients,connected_clients,blocked_clients,last_total_connections_received,last_total_commands_processed,last_total_net_input_bytes,last_total_net_output_bytes,last_rejected_connections,last_expired_keys,last_evicted_keys,last_keyspace_hits,last_keyspace_misses,current_time)
        func.mysql_exec(sql,param)
        #change time from s to min   --- /min  /min  k/s k/s /min /min /min /min
        frequency_monitor=int(frequency_monitor)
        frequency_monitor_min=int(frequency_monitor/60)
        sql="update redis_run_status a,redis_run_status_last b set a.total_connections_received=(a.last_total_connections_received-b.last_total_connections_received)/%s,a.total_commands_processed=(a.last_total_commands_processed-b.last_total_commands_processed)/%s,a.total_net_input_bytes=(a.last_total_net_input_bytes-b.last_total_net_input_bytes)/1024/%s,a.total_net_output_bytes=(a.last_total_net_output_bytes-b.last_total_net_output_bytes)/1024/%s,a.rejected_connections=(a.last_rejected_connections-b.last_rejected_connections)/%s,a.expired_keys=(a.last_expired_keys-b.last_expired_keys)/%s,a.evicted_keys=(a.last_evicted_keys-b.last_evicted_keys)/%s,a.keyspace_hits=(a.last_keyspace_hits-b.last_keyspace_hits)/%s,a.keyspace_misses=(a.last_keyspace_misses-b.last_keyspace_misses)/%s,a.keyspace_hit_rate=ifnull(100*a.keyspace_hits/(a.keyspace_hits+a.keyspace_misses),100)  where a.server_id=b.server_id and a.application_id=b.application_id"
        #print frequency_monitor
        #print frequency_monitor_min  
        param=(frequency_monitor_min,frequency_monitor_min,frequency_monitor,frequency_monitor,frequency_monitor_min,frequency_monitor_min,frequency_monitor_min,frequency_monitor_min,frequency_monitor_min)
        func.mysql_exec(sql,param)
    except Exception,e:
        log.error("ERROR: check_redis_run_status failed %s" % (traceback.format_exc()))
        return

def check_redis_replication(server_id,application_id,redis_info,current_time,log):
    try:
        role = redis_info.get("role","---")
        if(role == "master"):
            is_master="1"
            is_slave="0"
            master_host="---"
            master_port="---"
            master_link_status="---"
            master_last_io_seconds_ago="---"
            master_repl_offset=redis_info.get("master_repl_offset",'0')
            connected_slaves=redis_info.get("connected_slaves",'0')
            slave_repl_offset="---"
            slave_read_only="0"
            slave_priority="---"
            master_link_down_since_seconds="---"
            delay="---"
        else:
            is_slave="1"
            master_host=redis_info.get("master_host","---")
            master_port=redis_info.get("master_port","---")
            master_link_status=redis_info.get("master_link_status","---")
            master_last_io_seconds_ago=redis_info.get("master_last_io_seconds_ago","---")
            master_repl_offset=redis_info.get("master_repl_offset",'0')
            connected_slaves=redis_info.get("connected_slaves",'0')
            is_master="0"
            if int(connected_slaves)>0:
                is_master="1"
            slave_repl_offset=redis_info.get("slave_repl_offset","---")
            slave_read_only=redis_info.get("slave_read_only",'0')
            slave_priority=redis_info.get("slave_priority","---")
            if master_link_status=="down":
                master_link_down_since_seconds=redis_info.get("master_link_down_since_seconds","---")
            else:
                master_link_down_since_seconds="0"
            delay="0"
        sql="insert into redis_replication(server_id,application_id,is_master,is_slave,master_host,master_port,master_link_status,master_last_io_seconds_ago,master_repl_offset,connected_slaves,slave_repl_offset,slave_read_only,slave_priority,master_link_down_since_seconds,delay,create_time) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        param=(server_id,application_id,is_master,is_slave,master_host,master_port,master_link_status,master_last_io_seconds_ago,master_repl_offset,connected_slaves,slave_repl_offset,slave_read_only,slave_priority,master_link_down_since_seconds,delay,current_time)
        func.mysql_exec(sql,param)
    except Exception,e:
        log.error("ERROR: check_redis_replication failed %s" % (traceback.format_exc()))
        return

def check_redis_keyspace(server_id,application_id,redis_info,current_time,log):
    try:
        #eg: db0:keys=315,expires=0,avg_ttl=0
        #databases = []
        for key in sorted(redis_info.keys()):
            if key.startswith("db"):
                database = redis_info.get(key)
        #        database['name'] = key
        #        databases.append(database)
                keys=database.get("keys")
                expires=database.get("expires")
                avg_ttl=database.get("avg_ttl")
                persists=database.get("keys") - database.get("expires")
                sql="insert into redis_keyspace(server_id,application_id,db_name,db_keys,expires,persists,avg_ttl,create_time) values(%s,%s,%s,%s,%s,%s,%s,%s)"
                param=(server_id,application_id,key,keys,expires,persists,avg_ttl,current_time)
                func.mysql_exec(sql,param)

        ##sum
        #expires = 0
        #persists = 0
        #for database in databases:
        #    expires += database.get("expires")
        #    persists += database.get("keys") - database.get("expires")

       # for i in range(0,15):
       #    db_name="db"+str(i)
       #    try:
       #       db_info=redis_info.get(db_name)
       #       if db_info <> "":
       #          keys=db_info["keys"]
       #          expires=db_info["expires"]
       #          avg_ttl=db_info["avg_ttl"]
       #         # keys=db_info[db_info.find("keys=")+5 : db_info.find(",expires=")]
       #         # expires=db_info[db_info.find("expires=")+8 : db_info.find(",avg_ttl=")]
       #         # avg_ttl=db_info[db_info.find("avg_ttl=")+8 :]
       #          sql="insert into redis_keyspace(server_id,application_id,db_name,db_keys,expires,avg_ttl,create_time) values(%s,%s,%s,%s,%s,%s,%s)"
       #          param=(server_id,application_id,db_name,keys,expires,avg_ttl,current_time)
       #          func.mysql_exec(sql,param)
       #    except Exception,e:
       #       pass
    except Exception,e:
        log.error("ERROR: check_redis_keyspace failed %s" % (traceback.format_exc()))
        return

def init_table():
    sql="insert into redis_server_status_history select * from redis_server_status;"
    func.mysql_exec(sql,'')
    sql="delete from redis_server_status;"
    func.mysql_exec(sql,'')
#    sql="insert into redis_persistence_status_history(server_id,application_id,rdb_changes_since_last_save,rdb_last_save_time,rdb_last_bgsave_status,rdb_last_bgsave_time_sec,aof_enabled,aof_last_rewrite_time_sec,aof_last_bgrewrite_status,aof_last_write_status,aof_current_size,create_time,YmdHi) select server_id,application_id,rdb_changes_since_last_save,rdb_last_save_time,rdb_last_bgsave_status,rdb_last_bgsave_time_sec,aof_enabled,aof_last_rewrite_time_sec,aof_last_bgrewrite_status,aof_last_write_status,aof_current_size,create_time,LEFT(REPLACE(REPLACE(REPLACE(create_time,'-',''),' ',''),':',''),12) from redis_persistence_status;"
    sql="insert into redis_persistence_status_history select *,LEFT(REPLACE(REPLACE(REPLACE(create_time,'-',''),' ',''),':',''),12) from redis_persistence_status;"
    func.mysql_exec(sql,'')
    sql="delete from redis_persistence_status;"
    func.mysql_exec(sql,'')

    sql="insert into redis_resource_status_history select *,LEFT(REPLACE(REPLACE(REPLACE(create_time,'-',''),' ',''),':',''),12) from redis_resource_status;"
    func.mysql_exec(sql,'')
    sql="delete from redis_resource_status;"
    func.mysql_exec(sql,'')

    sql="insert into redis_run_status_history select *,LEFT(REPLACE(REPLACE(REPLACE(create_time,'-',''),' ',''),':',''),12) from redis_run_status;"
    func.mysql_exec(sql,'')
    sql="delete from redis_run_status_last;"
    func.mysql_exec(sql,'')
    sql="insert into redis_run_status_last select * from redis_run_status;"
    func.mysql_exec(sql,'')
    sql="delete from redis_run_status;"
    func.mysql_exec(sql,'')

    sql="insert into redis_keyspace_history select *,LEFT(REPLACE(REPLACE(REPLACE(create_time,'-',''),' ',''),':',''),12) from redis_keyspace;"
    func.mysql_exec(sql,'')
    sql="delete from redis_keyspace;"
    func.mysql_exec(sql,'')

    sql="insert into redis_replication_history select *,LEFT(REPLACE(REPLACE(REPLACE(create_time,'-',''),' ',''),':',''),12) from redis_replication;"
    func.mysql_exec(sql,'')
    sql="delete from redis_replication;"
    func.mysql_exec(sql,'')

def main():
    log_level="info"
    log_config=global_logs.CLogConfig(level=log_level)
    log_name="./log/check_redis_status.log"
    log_config._path="%s.%s"%(log_name,func_time.today())
    log=global_logs.initLog(log_config,logmodule="status")
    if log == None:
        print "init log error!"

    servers=func.mysql_query("select id,host,port,passwd,application_id,status from servers where is_delete=0;")
    frequency_monitor = func.get_option('frequency_monitor')
    if servers:
	 #print("servers %s" %(servers,));
         log.info("check_mysql_status controller started..." );
         init_table()
         plist = []
         for row in servers:
             server_id=row[0]
             host=row[1]
             port=row[2]
             passwd=row[3]
             application_id=row[4]
             status=row[5]
             if status <> 0:
		 p = Process(target = check_redis_status, args = (host,port,passwd,server_id,application_id,frequency_monitor,log))
                 plist.append(p)
                 p.start()

         for p in plist:
             p.join()
         #set replication delay
         sql="delete from redis_replication_last;"
         func.mysql_exec(sql,'')
         sql="insert into redis_replication_last select * from redis_replication;"
         func.mysql_exec(sql,'')
         sql="update redis_replication a,redis_replication_last b,servers c set a.delay=b.master_repl_offset-a.slave_repl_offset where a.master_host=c.host and a.master_port=c.port and b.server_id=c.id and a.is_slave=1 and b.is_master=1;"
         func.mysql_exec(sql,'')
         log.info("check_mysql_status controller finished..." )


if __name__=='__main__':
    main()
