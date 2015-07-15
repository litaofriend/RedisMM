#!/usr/bin/env python
#-*-coding:utf-8-*-

import logging
import sys
from logging.handlers import *
import datetime


LOG_PATH="./log/redisMM.log"
LOG_FORMATTER='%(asctime)s %(filename)s[line:%(lineno)d] %(levelname)s %(message)s'
LEVELS={'noset':logging.NOTSET, 
    'debug':logging.DEBUG, 
    'info':logging.INFO, 
    'warning':logging.WARNING,
    'error':logging.ERROR, 
    'critical':logging.CRITICAL}

_handlerTYPE=set(['file', 'stream'])

class CLogConfig(object):
    def __init__(self,level='debug',formatter=LOG_FORMATTER,path=LOG_PATH,handler='file'):
        self._level=LEVELS[level]
        self._formatter=formatter
        self._path=path
        self._handler=handler
 
    def checkConfig(self):
        if self._level not in LEVELS.values() or self._handler not in _handlerTYPE:
            return False
        return True

def initLog(config,logmodule="root"):
    if not isinstance(config,CLogConfig):
        print("init parameter error,need type CLogConfig.")
        return None
    if not config.checkConfig():
        print("check log config failed.")
        return None

    logger=logging.getLogger(logmodule)
    logger.setLevel(config._level)
    handler=RotatingFileHandler(config._path, maxBytes=2 * 1024 * 1024 * 1024, backupCount=10)
    if  'file' != config._handler:
        handler=logging.StreamHandler()

    handler.setLevel(config._level)
    handler.setFormatter(logging.Formatter(config._formatter))
    logger.addHandler(handler)
    
    return logger

if '__main__' == __name__:
    log_level="debug"
    log_config=CLogConfig(level=log_level)
    log_name="test_log"
    log_config._path="%s.%s"%(log_name,datetime.datetime.today().strftime("%Y%m%d"))
    log=initLog(log_config,logmodule="test")
    if log == None:
        print "init log error!"
        sys.exit(-1)
    log.debug("hello world!")
    
