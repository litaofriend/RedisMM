#!/bin/bash

SHELLLOCATION=`dirname $0`
SHELLLOCATION=`cd "${SHELLLOCATION}" ; pwd`
basedir=$SHELLLOCATION
if [ ! -d log ];then
  mkdir log
fi
arg=$1

checkrun(){
  redis_process=`ps -ef|grep redisMM_main |grep -v grep|wc -l`
  return $redis_process 
}

status(){
  checkrun
  if [ $redis_process -ge 1 ];then
    echo "redisMM_main is running!"
  else
    echo "redisMM_main is stopped!"
  fi
}

start(){
  checkrun
  if [ $redis_process -eq 0 ];then
    echo "begin to start..."
    cd $basedir && ./redisMM_main.py > ./log/redisMM_main.log 2>&1 &
    sleep 1
  fi
  status
}

stop(){
  checkrun
  if [ $redis_process -gt 0 ];then
    echo "begin to stop..."
    ps -ef |grep "redisMM_main"|grep -v -E "vim|grep"|awk '{print $2}' |while read line; do kill $line; echo "redisMM processes id $line been stop"; done
    sleep 2
  fi
  status
}

case "$arg" in
start)
        start
        ;;
stop)
        stop
        ;;
restart)
        stop
        start
        ;;
status)
        status
        ;;
*)
        echo "Usage: $0 {start|stop|restart|status}"  
esac

