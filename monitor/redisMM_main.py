#!/usr/bin/env python
#coding:utf-8
import os, sys, string, time, datetime, traceback;
from multiprocessing import Process;
import global_functions as func  


def job_run(script_name,times):
    while True:
        os.system("python "+script_name)
        time.sleep(int(times))

def main():
    print("%s: controller started." % (time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()),))
    ##全局配置--全局监控
    monitor = func.get_option('monitor')
    ##全局配置--状态监控
    monitor_status = func.get_option('monitor_status')
    ##全局配置--状态监控频率
    frequency_monitor = func.get_option('frequency_monitor')
    ##全局配置--开启告警
    alarm = func.get_option('alarm')
    ###全局配置--报警检测频率
    frequency_alarm = func.get_option('frequency_alarm')
    ##全局配置--冷备管理
    data_back_status = func.get_option('data_back_status')
    ##全局配置--冷备管理频率
    frequency_data_back = func.get_option('frequency_data_back')
    ##全局配置--历史数据自动清理
    history_clear_status = func.get_option('history_clear_status')

    joblist = []
    if monitor=="1":
        if monitor_status=="1":
            job = Process(target = job_run, args = ('check_redis_status.py',frequency_monitor))
            joblist.append(job)
            job.start()
        if alarm=="1":
            job = Process(target = job_run, args = ('alarm_redis.py',frequency_alarm))
            joblist.append(job)
            job.start()    
        if data_back_status=="1":
	    job = Process(target = job_run, args = ('redis_data_back.py',frequency_data_back))
	    joblist.append(job)
	    job.start()

        if history_clear_status=="1":
            job = Process(target = job_run, args = ('redis_history_clear.py',36000))
            joblist.append(job)
            job.start()
	
	for job in joblist:
            job.join();
    print("%s: controller finished." % (time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()),))

  
if __name__ == '__main__':  
    main()

