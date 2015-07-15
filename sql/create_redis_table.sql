/*
  RedisMM
  Type    : database table structure
  Date    : 2015-06-03
  author  : blythli
*/

create database if not exists redis_monitor;
use redis_monitor;
drop table if exists redis_server_status;
drop table if exists redis_run_status;
drop table if exists redis_run_status_history;
drop table if exists redis_persistence_status;
drop table if exists redis_persistence_status_history;
drop table if exists redis_resource_status;
drop table if exists redis_resource_status_history;
drop table if exists redis_replication;
drop table if exists redis_keyspace;

#版本信息
CREATE TABLE if not exists `redis_server_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `connect` varchar(20) DEFAULT 'success',
  `redis_version` varchar(20) NOT NULL DEFAULT '' COMMENT '版本',
  `redis_mode` varchar(20) NOT NULL DEFAULT '' COMMENT '运行模式',
  `multiplexing_api` varchar(20) NOT NULL DEFAULT '' COMMENT '算法',
  `process_id` int NOT NULL DEFAULT '0' COMMENT '进程PID',
  `run_id` varchar(100) NOT NULL DEFAULT '' COMMENT '随机标识符,用于sentinel和集群,扩展信息',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT 'uptime_in_seconds,前台展示时换算',
  `config_file` text NOT NULL DEFAULT '' COMMENT '配置文件',
  `dir` text NOT NULL DEFAULT '' COMMENT '程序路径',
  `info` text NOT NULL DEFAULT '',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8524736 DEFAULT CHARSET=utf8;

drop table if exists redis_server_status_history;
create table redis_server_status_history like redis_server_status;

#状态监控 需要绘图
CREATE TABLE if not exists `redis_run_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `max_clients` int NOT NULL DEFAULT '0' COMMENT '最大客户端的数量',
  `connected_clients` int NOT NULL DEFAULT '0' COMMENT '已连接客户端的数量',
  `blocked_clients` int NOT NULL DEFAULT '0' COMMENT '正在等待阻塞命令BLPOP、BRPOP、BRPOPLPUSH的客户端的数量',
  `last_total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,',
  `last_total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量',
  `last_total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_rejected_connections` int NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量',
  `last_expired_keys` int NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值',
  `last_evicted_keys` int NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量',
  `last_keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数',
  `last_keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数',
  `total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,计算差值:次/分',
  `total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量,计算差值:次/分',
  `total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `rejected_connections` int NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量,计算差值:次/分',
  `expired_keys` int NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值:次/分',
  `evicted_keys` int NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量,计算差值:次/分',
  `keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数,计算差值:次/分',
  `keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数,计算差值:次/分',
  `keyspace_hit_rate` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的比率 %',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8524736 DEFAULT CHARSET=utf8;

drop table if exists redis_run_status_last;
create table redis_run_status_last like redis_run_status;

CREATE TABLE if not exists `redis_run_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `max_clients` int NOT NULL DEFAULT '0' COMMENT '最大客户端的数量',
  `connected_clients` int NOT NULL DEFAULT '0' COMMENT '已连接客户端的数量',
  `blocked_clients` int NOT NULL DEFAULT '0' COMMENT '正在等待阻塞命令BLPOP、BRPOP、BRPOPLPUSH的客户端的数量',
  `last_total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,',
  `last_total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量',
  `last_total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_rejected_connections` int NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量',
  `last_expired_keys` int NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值',
  `last_evicted_keys` int NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量',
  `last_keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数',
  `last_keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数',
  `total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,计算差值:次/分',
  `total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量,计算差值:次/分',
  `total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `rejected_connections` int NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量,计算差值:次/分',
  `expired_keys` int NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值:次/分',
  `evicted_keys` int NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量,计算差值:次/分',
  `keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数,计算差值:次/分',
  `keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数,计算差值:次/分',
  `keyspace_hit_rate` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的比率 %',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `YmdHi` bigint(18) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_ymdhi` (`YmdHi`) USING BTREE,
  KEY `idx_union_1` (`server_id`,`YmdHi`) USING BTREE,
  KEY `idx_create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8524736 DEFAULT CHARSET=utf8;

#备份监控 需要绘图
CREATE TABLE if not exists `redis_persistence_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `rdb_enabled`  varchar(10) NOT NULL DEFAULT '0' COMMENT 'RDB是否处于打开状态',
  `rdb_dbfilename` text NOT NULL DEFAULT '' COMMENT 'rdb文件名',
  `rdb_changes_since_last_save` int NOT NULL DEFAULT '0' COMMENT '距离最近一次成功创建持久化文件之后经过了多少秒',
  `rdb_last_save_time` int NOT NULL DEFAULT '0' COMMENT '最近一次成功创建RDB 文件的UNIX时间戳,扩展信息',
  `rdb_last_bgsave_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次创建 RDB 文件的结果是成功还是失败',  
  `rdb_last_bgsave_time_sec` int NOT NULL DEFAULT '0' COMMENT '最近一次创建 RDB 文件耗费的秒数',
  `aof_enabled` varchar(10) NOT NULL DEFAULT '' COMMENT 'AOF是否处于打开状态',
  `aof_last_rewrite_time_sec` int NOT NULL DEFAULT '0' COMMENT '最近一次创建 AOF 文件耗费的时长',
  `aof_last_bgrewrite_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次重写 AOF文件的结果是成功还是失败',
  `aof_last_write_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次创建AOF文件的结果是成功还是失败',  
  `aof_current_size` bigint NOT NULL DEFAULT '0' COMMENT 'AOF 文件目前的大小',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8524736 DEFAULT CHARSET=utf8;  

CREATE TABLE if not exists `redis_persistence_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `rdb_enabled`  varchar(10) NOT NULL DEFAULT '0' COMMENT 'RDB是否处于打开状态',
  `rdb_dbfilename` text NOT NULL DEFAULT '' COMMENT 'rdb文件名',
  `rdb_changes_since_last_save` int NOT NULL DEFAULT '0' COMMENT '距离最近一次成功创建持久化文件之后经过了多少秒',
  `rdb_last_save_time` int NOT NULL DEFAULT '0' COMMENT '最近一次成功创建RDB 文件的UNIX时间戳,扩展信息',
  `rdb_last_bgsave_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次创建 RDB 文件的结果是成功还是失败',  
  `rdb_last_bgsave_time_sec` int NOT NULL DEFAULT '0' COMMENT '最近一次创建 RDB 文件耗费的秒数',
  `aof_enabled` varchar(10) NOT NULL DEFAULT '' COMMENT 'AOF是否处于打开状态',
  `aof_last_rewrite_time_sec` int NOT NULL DEFAULT '0' COMMENT '最近一次创建 AOF 文件耗费的时长',
  `aof_last_bgrewrite_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次重写 AOF文件的结果是成功还是失败',
  `aof_last_write_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次创建AOF文件的结果是成功还是失败',  
  `aof_current_size` bigint NOT NULL DEFAULT '0' COMMENT 'AOF 文件目前的大小',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `YmdHi` bigint(18) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_ymdhi` (`YmdHi`) USING BTREE,
  KEY `idx_union_1` (`server_id`,`YmdHi`) USING BTREE,
  KEY `idx_create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8524736 DEFAULT CHARSET=utf8;  

  #资源监控 需要绘图
CREATE TABLE if not exists `redis_resource_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `max_memory` bigint NOT NULL DEFAULT '0' COMMENT 'Redis最大内存',
  `used_memory` bigint NOT NULL DEFAULT '0' COMMENT '由Redis分配器分配的内存总量',
  `used_memory_rss` bigint NOT NULL DEFAULT '0' COMMENT '从操作系统的角度，返回Redis已分配的内存总量',
  `used_memory_peak` bigint NOT NULL DEFAULT '0' COMMENT 'Redis的内存消耗峰值,扩展信息',
  `mem_fragmentation_ratio` double(15,2) NOT NULL DEFAULT '0.00' COMMENT 'used_memory_rss/used_memory,较大表示存在内存碎片,较小表示 Redis的部分内存被操作系统换出到交换空间了,操作可能会产生明显的延迟',  
  `used_cpu_sys` double(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Redis主进程在核心态所占用的CPU时间求和累计',
  `used_cpu_user` double(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Redis主进程在用户态所占用的CPU时间求和累计',
  `used_cpu_sys_children` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '后台进程在核心态所占用的CPU时间求和累计',
  `used_cpu_user_children` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '后台进程在用户态所占用的CPU时间求和累计',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8524736 DEFAULT CHARSET=utf8;  
  
  CREATE TABLE if not exists `redis_resource_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `max_memory` bigint NOT NULL DEFAULT '0' COMMENT 'Redis最大内存',
  `used_memory` bigint NOT NULL DEFAULT '0' COMMENT '由Redis分配器分配的内存总量',
  `used_memory_rss` bigint NOT NULL DEFAULT '0' COMMENT '从操作系统的角度，返回Redis已分配的内存总量',
  `used_memory_peak` bigint NOT NULL DEFAULT '0' COMMENT 'Redis的内存消耗峰值,扩展信息',
  `mem_fragmentation_ratio` double(15,2) NOT NULL DEFAULT '0.00' COMMENT 'used_memory_rss/used_memory,较大表示存在内存碎片,较小表示 Redis的部分内存被操作系统换出到交换空间了,操作可能会产生明显的延迟',  
  `used_cpu_sys` double(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Redis主进程在核心态所占用的CPU时间求和累计',
  `used_cpu_user` double(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Redis主进程在用户态所占用的CPU时间求和累计',
  `used_cpu_sys_children` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '后台进程在核心态所占用的CPU时间求和累计',
  `used_cpu_user_children` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '后台进程在用户态所占用的CPU时间求和累计',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `YmdHi` bigint(18) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_ymdhi` (`YmdHi`) USING BTREE,
  KEY `idx_union_1` (`server_id`,`YmdHi`) USING BTREE,
  KEY `idx_create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8524736 DEFAULT CHARSET=utf8;  

#复制监控 需要绘图
 CREATE TABLE if not exists `redis_replication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` smallint(4) DEFAULT NULL,
  `application_id` smallint(4) DEFAULT NULL,
  `is_master` tinyint(2) DEFAULT '0',
  `is_slave` tinyint(2) unsigned DEFAULT '0',
  `master_host` varchar(30) NOT NULL DEFAULT '',
  `master_port` varchar(20) NOT NULL DEFAULT '0',
  `master_link_status` varchar(20) NOT NULL DEFAULT '' COMMENT 'up/down',
  `master_last_io_seconds_ago` varchar(20) NOT NULL DEFAULT '0' COMMENT '距离最近一次和master交互的时长',
  `master_repl_offset` bigint NOT NULL DEFAULT '0',
  `connected_slaves` int NOT NULL DEFAULT '0',
  `slave_repl_offset` varchar(20) NOT NULL DEFAULT '0',
  `slave_read_only` tinyint(2) unsigned DEFAULT '0',
  `slave_priority` varchar(20) NOT NULL DEFAULT '0',
  `master_link_down_since_seconds` varchar(20) NOT NULL DEFAULT '0' COMMENT 'master连接失败时长',
  `delay` varchar(20) NOT NULL DEFAULT '0' COMMENT '主备同步延迟 master_repl_offset-slave_repl_offset',
  `create_time` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5448324 DEFAULT CHARSET=utf8;

drop table if exists redis_replication_last;
create table redis_replication_last like redis_replication;
drop table if exists redis_replication_history;
create table redis_replication_history like redis_replication;
alter table redis_replication_history add `YmdHi` bigint(18) NOT NULL DEFAULT '0';

#数据库信息 
#db0:keys=3101,expires=0,avg_ttl=0   ###keyspace 部分记录了数据库相关的统计信息，比如数据库的键数量、数据库已经被删除的过期键数量等。对于每个数据库，这个部分都会添加一行以下格式的信息
CREATE TABLE if not exists `redis_keyspace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` smallint(4) DEFAULT '0',
  `application_id` smallint(4) DEFAULT '0',
  `db_name` varchar(30) DEFAULT '0',
  `db_keys` int DEFAULT '0' COMMENT '数据库的键数量',
  `expires` int DEFAULT '0' COMMENT '数据库已经被删除的过期键数量',
  `persists` int DEFAULT '0' COMMENT '数据库在用键数量',
  `avg_ttl` varchar(30) DEFAULT '0' COMMENT '平均生存时间值',
  `create_time` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=805544713 DEFAULT CHARSET=utf8;

drop table if exists redis_keyspace_history;
create table redis_keyspace_history like redis_keyspace;
alter table redis_keyspace_history add `YmdHi` bigint(18) NOT NULL DEFAULT '0';

