create database if not exists db_RedisMM;
use db_RedisMM;
DROP TABLE IF EXISTS admin_user;
CREATE TABLE `admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `permission` varchar(500) DEFAULT NULL,
  `realname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `login_count` int(11) DEFAULT '0',
  `last_login_ip` varchar(100) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=300 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS alarm;
CREATE TABLE `alarm` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` smallint(4) DEFAULT NULL,
  `server_id` smallint(4) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `db_type` varchar(30) DEFAULT NULL,
  `alarm_type` varchar(50) DEFAULT NULL,
  `alarm_value` varchar(50) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `send_mail` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4287 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS alarm_history;
CREATE TABLE `alarm_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` smallint(4) DEFAULT NULL,
  `server_id` smallint(4) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `db_type` varchar(30) DEFAULT NULL,
  `alarm_type` varchar(50) DEFAULT NULL,
  `alarm_value` varchar(50) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `send_mail` tinyint(2) DEFAULT NULL,
  `send_mail_status` tinyint(2) DEFAULT NULL,
  `send_mail_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_application_id` (`application_id`),
  KEY `idx_server_id` (`server_id`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS alarm_last_status;
CREATE TABLE `alarm_last_status` (
  `application_id` smallint(4) NOT NULL DEFAULT '0',
  `server_id` smallint(4) NOT NULL DEFAULT '0',
  `connect` varchar(255) DEFAULT 'normal',
  `connections` int(11) NOT NULL DEFAULT '0',
  `status_connections` varchar(255) DEFAULT 'normal',
  `used_memory` bigint(20) NOT NULL DEFAULT '0',
  `status_used_memory` varchar(255) DEFAULT 'normal',
  `binlog_count` int(11) NOT NULL DEFAULT '0',
  `status_binlog_count` varchar(255) DEFAULT 'normal',
  `innodb_space_free` int(11) NOT NULL DEFAULT '0',
  `status_innodb_space_free` varchar(255) DEFAULT 'normal',
  `replication_relay` int(11) NOT NULL DEFAULT '0',
  `status_replication_relay` varchar(255) DEFAULT 'normal',
  `error_level` varchar(50) DEFAULT 'normal',
  `send_alarm` tinyint(2) DEFAULT '0',
  `alarm_type` varchar(50) DEFAULT '0',
  `alarm_value` varchar(50) DEFAULT 'normal',
  `alarm_num` int(11) NOT NULL DEFAULT '0',
  `time_error_continue` int(11) NOT NULL DEFAULT '0',
  `modify_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `slow_querys` int(11) NOT NULL DEFAULT '0',
  `status_slow_querys` varchar(255) DEFAULT 'normal',
  `error_log` int(11) NOT NULL DEFAULT '0',
  `status_error_log` varchar(255) DEFAULT 'normal',
  PRIMARY KEY (`application_id`,`server_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS application;
CREATE TABLE `application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1',
  `is_delete` tinyint(2) DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `owner` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS linux_resource;
CREATE TABLE `linux_resource` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip` varchar(50) NOT NULL,
  `hostname` varchar(50) DEFAULT NULL,
  `kernel` varchar(50) DEFAULT NULL,
  `load1` varchar(10) DEFAULT NULL,
  `load5` varchar(10) DEFAULT NULL,
  `load15` varchar(10) DEFAULT NULL,
  `disk_use_root` varchar(50) DEFAULT NULL,
  `disk_use_home` varchar(50) DEFAULT NULL,
  `disk_use_data` varchar(50) DEFAULT NULL,
  `mem_use` varchar(20) DEFAULT NULL,
  `eth0_receive_bytes` int(11) NOT NULL DEFAULT '0',
  `eth0_receive_packets` int(11) NOT NULL DEFAULT '0',
  `eth0_transmit_bytes` int(11) NOT NULL DEFAULT '0',
  `eth0_transmit_packets` int(11) NOT NULL DEFAULT '0',
  `eth1_receive_bytes` int(11) NOT NULL DEFAULT '0',
  `eth1_receive_packets` int(11) NOT NULL DEFAULT '0',
  `eth1_transmit_bytes` int(11) NOT NULL DEFAULT '0',
  `eth1_transmit_packets` int(11) NOT NULL DEFAULT '0',
  `cpu_rate_all` int(11) NOT NULL DEFAULT '0',
  `cpu_loadavg_1` int(11) NOT NULL DEFAULT '0',
  `cpu_loadavg_5` int(11) NOT NULL DEFAULT '0',
  `cpu_loadavg_15` int(11) NOT NULL DEFAULT '0',
  `mem_total` int(11) NOT NULL DEFAULT '0',
  `allmem_used` int(11) NOT NULL DEFAULT '0',
  `program_usemem` int(11) NOT NULL DEFAULT '0',
  `disk_use_rate_max` int(11) NOT NULL DEFAULT '0',
  `disk_use_rate_avg` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `RecordTime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS options;
CREATE TABLE `options` (
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `group` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_coldback_config;
CREATE TABLE `redis_coldback_config` (
  `server_id` int(11) NOT NULL,
  `IP` varchar(20) NOT NULL,
  `ssh_port` varchar(10) NOT NULL,
  `ssh_user` varchar(20) NOT NULL,
  `ssh_passwd` varchar(10) NOT NULL,
  `back_IP` varchar(20) NOT NULL,
  `back_ssh_port` varchar(10) NOT NULL,
  `back_ssh_user` varchar(20) NOT NULL,
  `back_ssh_passwd` varchar(10) NOT NULL,
  `back_path` varchar(20) NOT NULL,
  `db_name` varchar(200) NOT NULL DEFAULT 'rdb;aof',
  `back_cycle` int(11) NOT NULL DEFAULT '1',
  `back_time` varchar(20) NOT NULL DEFAULT '08:00',
  `save_days` int(11) NOT NULL DEFAULT '1',
  `save_number` int(11) NOT NULL DEFAULT '1',
  `change_max` int(11) NOT NULL DEFAULT '40',
  `back_flag` varchar(10) NOT NULL DEFAULT 'y',
  `alarm_flag` varchar(10) NOT NULL DEFAULT 'y',
  `charge_person` varchar(100) NOT NULL DEFAULT '',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`server_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_coldback_info;
CREATE TABLE `redis_coldback_info` (
  `date` date NOT NULL DEFAULT '0000-00-00',
  `server_id` int(11) NOT NULL,
  `db_name` varchar(200) NOT NULL,
  `file_name` text NOT NULL,
  `check_file_name` text NOT NULL,
  `db_md5sum` varchar(100) NOT NULL DEFAULT '',
  `db_size` bigint(20) NOT NULL DEFAULT '1',
  `suc_flag` varchar(10) NOT NULL DEFAULT 'y',
  `del_flag` varchar(10) NOT NULL DEFAULT 'n',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`date`,`server_id`,`db_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_keyspace;
CREATE TABLE `redis_keyspace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` smallint(4) DEFAULT '0',
  `application_id` smallint(4) DEFAULT '0',
  `db_name` varchar(30) DEFAULT '0',
  `db_keys` int(11) DEFAULT '0' COMMENT '数据库的键数量',
  `expires` int(11) DEFAULT '0' COMMENT '数据库已经被删除的过期键数量',
  `persists` int(11) DEFAULT '0' COMMENT '数据库在用键数量',
  `avg_ttl` varchar(30) DEFAULT '0' COMMENT '平均生存时间值',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=806897602 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_keyspace_history;
CREATE TABLE `redis_keyspace_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` smallint(4) DEFAULT '0',
  `application_id` smallint(4) DEFAULT '0',
  `db_name` varchar(30) DEFAULT '0',
  `db_keys` int(11) DEFAULT '0' COMMENT '数据库的键数量',
  `expires` int(11) DEFAULT '0' COMMENT '数据库已经被删除的过期键数量',
  `persists` int(11) DEFAULT '0' COMMENT '数据库在用键数量',
  `avg_ttl` varchar(30) DEFAULT '0' COMMENT '平均生存时间值',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `YmdHi` bigint(18) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=806897527 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_persistence_status;
CREATE TABLE `redis_persistence_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `rdb_enabled` varchar(10) NOT NULL DEFAULT '0' COMMENT 'RDB是否处于打开状态',
  `rdb_dbfilename` text NOT NULL COMMENT 'rdb文件名',
  `rdb_changes_since_last_save` int(11) NOT NULL DEFAULT '0' COMMENT '距离最近一次成功创建持久化文件之后经过了多少秒',
  `rdb_last_save_time` int(11) NOT NULL DEFAULT '0' COMMENT '最近一次成功创建RDB 文件的UNIX时间戳,扩展信息',
  `rdb_last_bgsave_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次创建 RDB 文件的结果是成功还是失败',
  `rdb_last_bgsave_time_sec` int(11) NOT NULL DEFAULT '0' COMMENT '最近一次创建 RDB 文件耗费的秒数',
  `aof_enabled` varchar(10) NOT NULL DEFAULT '' COMMENT 'AOF是否处于打开状态',
  `aof_last_rewrite_time_sec` int(11) NOT NULL DEFAULT '0' COMMENT '最近一次创建 AOF 文件耗费的时长',
  `aof_last_bgrewrite_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次重写 AOF文件的结果是成功还是失败',
  `aof_last_write_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次创建AOF文件的结果是成功还是失败',
  `aof_current_size` bigint(20) NOT NULL DEFAULT '0' COMMENT 'AOF 文件目前的大小',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9198462 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_persistence_status_history;
CREATE TABLE `redis_persistence_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `rdb_enabled` varchar(10) NOT NULL DEFAULT '0' COMMENT 'RDB是否处于打开状态',
  `rdb_dbfilename` text NOT NULL COMMENT 'rdb文件名',
  `rdb_changes_since_last_save` int(11) NOT NULL DEFAULT '0' COMMENT '距离最近一次成功创建持久化文件之后经过了多少秒',
  `rdb_last_save_time` int(11) NOT NULL DEFAULT '0' COMMENT '最近一次成功创建RDB 文件的UNIX时间戳,扩展信息',
  `rdb_last_bgsave_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次创建 RDB 文件的结果是成功还是失败',
  `rdb_last_bgsave_time_sec` int(11) NOT NULL DEFAULT '0' COMMENT '最近一次创建 RDB 文件耗费的秒数',
  `aof_enabled` varchar(10) NOT NULL DEFAULT '' COMMENT 'AOF是否处于打开状态',
  `aof_last_rewrite_time_sec` int(11) NOT NULL DEFAULT '0' COMMENT '最近一次创建 AOF 文件耗费的时长',
  `aof_last_bgrewrite_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次重写 AOF文件的结果是成功还是失败',
  `aof_last_write_status` varchar(20) NOT NULL DEFAULT '' COMMENT '最近一次创建AOF文件的结果是成功还是失败',
  `aof_current_size` bigint(20) NOT NULL DEFAULT '0' COMMENT 'AOF 文件目前的大小',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `YmdHi` bigint(18) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_ymdhi` (`YmdHi`) USING BTREE,
  KEY `idx_union_1` (`server_id`,`YmdHi`) USING BTREE,
  KEY `idx_create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9198407 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_replication;
CREATE TABLE `redis_replication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` smallint(4) DEFAULT NULL,
  `application_id` smallint(4) DEFAULT NULL,
  `is_master` tinyint(2) DEFAULT '0',
  `is_slave` tinyint(2) unsigned DEFAULT '0',
  `master_host` varchar(30) NOT NULL DEFAULT '',
  `master_port` varchar(20) NOT NULL DEFAULT '0',
  `master_link_status` varchar(20) NOT NULL DEFAULT '' COMMENT 'up/down',
  `master_last_io_seconds_ago` varchar(20) NOT NULL DEFAULT '0' COMMENT '距离最近一次和master交互的时长',
  `master_repl_offset` bigint(20) NOT NULL DEFAULT '0',
  `connected_slaves` int(11) NOT NULL DEFAULT '0',
  `slave_repl_offset` varchar(20) NOT NULL DEFAULT '0',
  `slave_read_only` tinyint(2) unsigned DEFAULT '0',
  `slave_priority` varchar(20) NOT NULL DEFAULT '0',
  `master_link_down_since_seconds` varchar(20) NOT NULL DEFAULT '0' COMMENT 'master连接失败时长',
  `delay` varchar(20) NOT NULL DEFAULT '0' COMMENT '主备同步延迟 master_repl_offset-slave_repl_offset',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6535471 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_replication_history;
CREATE TABLE `redis_replication_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` smallint(4) DEFAULT NULL,
  `application_id` smallint(4) DEFAULT NULL,
  `is_master` tinyint(2) DEFAULT '0',
  `is_slave` tinyint(2) unsigned DEFAULT '0',
  `master_host` varchar(30) NOT NULL DEFAULT '',
  `master_port` varchar(20) NOT NULL DEFAULT '0',
  `master_link_status` varchar(20) NOT NULL DEFAULT '' COMMENT 'up/down',
  `master_last_io_seconds_ago` varchar(20) NOT NULL DEFAULT '0' COMMENT '距离最近一次和master交互的时长',
  `master_repl_offset` bigint(20) NOT NULL DEFAULT '0',
  `connected_slaves` int(11) NOT NULL DEFAULT '0',
  `slave_repl_offset` varchar(20) NOT NULL DEFAULT '0',
  `slave_read_only` tinyint(2) unsigned DEFAULT '0',
  `slave_priority` varchar(20) NOT NULL DEFAULT '0',
  `master_link_down_since_seconds` varchar(20) NOT NULL DEFAULT '0' COMMENT 'master连接失败时长',
  `delay` varchar(20) NOT NULL DEFAULT '0' COMMENT '主备同步延迟 master_repl_offset-slave_repl_offset',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `YmdHi` bigint(18) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6535416 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_replication_last;
CREATE TABLE `redis_replication_last` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` smallint(4) DEFAULT NULL,
  `application_id` smallint(4) DEFAULT NULL,
  `is_master` tinyint(2) DEFAULT '0',
  `is_slave` tinyint(2) unsigned DEFAULT '0',
  `master_host` varchar(30) NOT NULL DEFAULT '',
  `master_port` varchar(20) NOT NULL DEFAULT '0',
  `master_link_status` varchar(20) NOT NULL DEFAULT '' COMMENT 'up/down',
  `master_last_io_seconds_ago` varchar(20) NOT NULL DEFAULT '0' COMMENT '距离最近一次和master交互的时长',
  `master_repl_offset` bigint(20) NOT NULL DEFAULT '0',
  `connected_slaves` int(11) NOT NULL DEFAULT '0',
  `slave_repl_offset` varchar(20) NOT NULL DEFAULT '0',
  `slave_read_only` tinyint(2) unsigned DEFAULT '0',
  `slave_priority` varchar(20) NOT NULL DEFAULT '0',
  `master_link_down_since_seconds` varchar(20) NOT NULL DEFAULT '0' COMMENT 'master连接失败时长',
  `delay` varchar(20) NOT NULL DEFAULT '0' COMMENT '主备同步延迟 master_repl_offset-slave_repl_offset',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6535471 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_resource_status;
CREATE TABLE `redis_resource_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `max_memory` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Redis最大内存',
  `used_memory` bigint(20) NOT NULL DEFAULT '0' COMMENT '由Redis分配器分配的内存总量',
  `used_memory_rss` bigint(20) NOT NULL DEFAULT '0' COMMENT '从操作系统的角度，返回Redis已分配的内存总量',
  `used_memory_peak` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Redis的内存消耗峰值,扩展信息',
  `mem_fragmentation_ratio` double(15,2) NOT NULL DEFAULT '0.00' COMMENT 'used_memory_rss/used_memory,较大表示存在内存碎片,较小表示 Redis的部分内存被操作系统换出到交换空间了,操作可能会产生明显的延迟',
  `used_cpu_sys` double(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Redis主进程在核心态所占用的CPU时间求和累计',
  `used_cpu_user` double(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Redis主进程在用户态所占用的CPU时间求和累计',
  `used_cpu_sys_children` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '后台进程在核心态所占用的CPU时间求和累计',
  `used_cpu_user_children` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '后台进程在用户态所占用的CPU时间求和累计',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9613485 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_resource_status_history;
CREATE TABLE `redis_resource_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `max_memory` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Redis最大内存',
  `used_memory` bigint(20) NOT NULL DEFAULT '0' COMMENT '由Redis分配器分配的内存总量',
  `used_memory_rss` bigint(20) NOT NULL DEFAULT '0' COMMENT '从操作系统的角度，返回Redis已分配的内存总量',
  `used_memory_peak` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Redis的内存消耗峰值,扩展信息',
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
) ENGINE=InnoDB AUTO_INCREMENT=9613430 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_run_status;
CREATE TABLE `redis_run_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `max_clients` int(11) NOT NULL DEFAULT '0' COMMENT '最大客户端的数量',
  `connected_clients` int(11) NOT NULL DEFAULT '0' COMMENT '已连接客户端的数量',
  `blocked_clients` int(11) NOT NULL DEFAULT '0' COMMENT '正在等待阻塞命令BLPOP、BRPOP、BRPOPLPUSH的客户端的数量',
  `last_total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,',
  `last_total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量',
  `last_total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_rejected_connections` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量',
  `last_expired_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值',
  `last_evicted_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量',
  `last_keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数',
  `last_keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数',
  `total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,计算差值:次/分',
  `total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量,计算差值:次/分',
  `total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `rejected_connections` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量,计算差值:次/分',
  `expired_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值:次/分',
  `evicted_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量,计算差值:次/分',
  `keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数,计算差值:次/分',
  `keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数,计算差值:次/分',
  `keyspace_hit_rate` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的比率 %',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9607128 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_run_status_history;
CREATE TABLE `redis_run_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `max_clients` int(11) NOT NULL DEFAULT '0' COMMENT '最大客户端的数量',
  `connected_clients` int(11) NOT NULL DEFAULT '0' COMMENT '已连接客户端的数量',
  `blocked_clients` int(11) NOT NULL DEFAULT '0' COMMENT '正在等待阻塞命令BLPOP、BRPOP、BRPOPLPUSH的客户端的数量',
  `last_total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,',
  `last_total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量',
  `last_total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_rejected_connections` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量',
  `last_expired_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值',
  `last_evicted_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量',
  `last_keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数',
  `last_keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数',
  `total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,计算差值:次/分',
  `total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量,计算差值:次/分',
  `total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `rejected_connections` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量,计算差值:次/分',
  `expired_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值:次/分',
  `evicted_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量,计算差值:次/分',
  `keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数,计算差值:次/分',
  `keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数,计算差值:次/分',
  `keyspace_hit_rate` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的比率 %',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `YmdHi` bigint(18) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_ymdhi` (`YmdHi`) USING BTREE,
  KEY `idx_union_1` (`server_id`,`YmdHi`) USING BTREE,
  KEY `idx_create_time` (`create_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9607073 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_run_status_last;
CREATE TABLE `redis_run_status_last` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `max_clients` int(11) NOT NULL DEFAULT '0' COMMENT '最大客户端的数量',
  `connected_clients` int(11) NOT NULL DEFAULT '0' COMMENT '已连接客户端的数量',
  `blocked_clients` int(11) NOT NULL DEFAULT '0' COMMENT '正在等待阻塞命令BLPOP、BRPOP、BRPOPLPUSH的客户端的数量',
  `last_total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,',
  `last_total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量',
  `last_total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值',
  `last_rejected_connections` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量',
  `last_expired_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值',
  `last_evicted_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量',
  `last_keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数',
  `last_keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数',
  `total_connections_received` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已接受的连接请求数量,计算差值:次/分',
  `total_commands_processed` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '服务器已执行的命令数量,计算差值:次/分',
  `total_net_input_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `total_net_output_bytes` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '计算差值:k/s',
  `rejected_connections` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大客户端数量限制而被拒绝的连接请求数量,计算差值:次/分',
  `expired_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为过期而被自动删除的数据库键数量,计算差值:次/分',
  `evicted_keys` int(11) NOT NULL DEFAULT '0' COMMENT '因为最大内存容量限制而被驱逐（evict）的键数量,计算差值:次/分',
  `keyspace_hits` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的次数,计算差值:次/分',
  `keyspace_misses` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键失败的次数,计算差值:次/分',
  `keyspace_hit_rate` double(15,2) NOT NULL DEFAULT '0.00' COMMENT '查找数据库键成功的比率 %',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9607073 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_server_status;
CREATE TABLE `redis_server_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `connect` varchar(20) DEFAULT 'success',
  `redis_version` varchar(20) NOT NULL DEFAULT '' COMMENT '版本',
  `redis_mode` varchar(20) NOT NULL DEFAULT '' COMMENT '运行模式',
  `multiplexing_api` varchar(20) NOT NULL DEFAULT '' COMMENT '算法',
  `process_id` int(11) NOT NULL DEFAULT '0' COMMENT '进程PID',
  `run_id` varchar(100) NOT NULL DEFAULT '' COMMENT '随机标识符,用于sentinel和集群,扩展信息',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT 'uptime_in_seconds,前台展示时换算',
  `config_file` text NOT NULL COMMENT '配置文件',
  `dir` text NOT NULL COMMENT '程序路径',
  `info` text NOT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9198405 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS redis_server_status_history;
CREATE TABLE `redis_server_status_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` tinyint(4) NOT NULL,
  `application_id` smallint(4) NOT NULL,
  `connect` varchar(20) DEFAULT 'success',
  `redis_version` varchar(20) NOT NULL DEFAULT '' COMMENT '版本',
  `redis_mode` varchar(20) NOT NULL DEFAULT '' COMMENT '运行模式',
  `multiplexing_api` varchar(20) NOT NULL DEFAULT '' COMMENT '算法',
  `process_id` int(11) NOT NULL DEFAULT '0' COMMENT '进程PID',
  `run_id` varchar(100) NOT NULL DEFAULT '' COMMENT '随机标识符,用于sentinel和集群,扩展信息',
  `uptime` int(11) NOT NULL DEFAULT '0' COMMENT 'uptime_in_seconds,前台展示时换算',
  `config_file` text NOT NULL COMMENT '配置文件',
  `dir` text NOT NULL COMMENT '程序路径',
  `info` text NOT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9198350 DEFAULT CHARSET=utf8
;
DROP TABLE IF EXISTS servers;
CREATE TABLE `servers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `host` varchar(30) DEFAULT NULL,
  `port` varchar(10) DEFAULT NULL,
  `passwd` varchar(50) DEFAULT NULL,
  `status` tinyint(2) DEFAULT '1' COMMENT '1:监控 0：不监控',
  `application_id` smallint(4) DEFAULT NULL,
  `send_mail` tinyint(2) DEFAULT '1',
  `alarm_connections` tinyint(2) DEFAULT '1',
  `alarm_used_memory` tinyint(2) DEFAULT '1',
  `alarm_repl_status` tinyint(2) DEFAULT '1',
  `alarm_repl_delay` tinyint(2) DEFAULT '0',
  `threshold_connections` varchar(20) DEFAULT '90',
  `threshold_used_memory` varchar(20) DEFAULT '90',
  `threshold_repl_delay` varchar(20) DEFAULT '20480',
  `slow_query` tinyint(2) DEFAULT '0',
  `is_delete` tinyint(1) DEFAULT '0',
  `display_order` smallint(4) DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `alarm_interval` smallint(4) DEFAULT '300',
  `alarm_type` smallint(4) DEFAULT '0',
  `converge_type` smallint(4) DEFAULT '0',
  `alarm_person` varchar(255) DEFAULT '',
  `slow_query_log` varchar(500) DEFAULT '',
  `alarm_binlogs` tinyint(2) DEFAULT '1',
  `threshold_binlogs` varchar(20) DEFAULT NULL,
  `purge_binlogs` tinyint(2) DEFAULT '1',
  `max_binlog_num` varchar(20) DEFAULT '50',
  `alarm_slow_querys` tinyint(2) DEFAULT '1',
  `threshold_slow_querys` varchar(20) DEFAULT '100',
  `alarm_error_querys` tinyint(2) DEFAULT '1',
  `threshold_error_querys` varchar(20) DEFAULT '10',
  PRIMARY KEY (`id`),
  KEY `idx_host` (`host`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8
;
-- MySQL dump 10.13  Distrib 5.6.23, for Linux (x86_64)
--
-- Host: localhost    Database: redis_monitor
-- ------------------------------------------------------
-- Server version	5.6.23-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `options` (
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `group` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `options`
--

LOCK TABLES `options` WRITE;
/*!40000 ALTER TABLE `options` DISABLE KEYS */;
INSERT INTO `options` VALUES ('monitor','1','开启全局监控','redis'),('monitor_status','1','开启状态监控','redis'),('alarm','1','开启报警','redis'),('send_alarm_mail','1','发生报警邮件','redis'),('frequency_monitor','120','监控频率','redis'),('frequency_alarm','10','报警通知频率','redis'),('mail_to_list','admin','报警邮件通知人员','redis'),('data_back_status','1','冷备管理开关','redis'),('frequency_data_back','300','冷备管理频率','redis'),('history_clear_status','1','自动清理','redis'),('history_save_days','30','数据保存时长','redis');
/*!40000 ALTER TABLE `options` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-14 16:19:10
INSERT INTO admin_user VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'all', 'administrator', '', '', '1', '127.0.0.1', '2014-02-15 20:47:47', '1', '2013-12-25 15:58:34');
