<?php 
class Chart_model extends CI_Model{
    function get_redis_run_status($server_id,$time){
        $query=$this->db->query("select unix_timestamp(create_time)*1000 as time,connected_clients,blocked_clients,total_connections_received,rejected_connections,total_commands_processed,total_net_input_bytes,total_net_output_bytes,expired_keys,evicted_keys,keyspace_hits,keyspace_misses,keyspace_hit_rate from redis_run_status_history where server_id=$server_id and YmdHi>=$time order by  create_time;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }   
    function get_redis_resource_status($server_id,$time){
        $query=$this->db->query("select unix_timestamp(create_time)*1000 as time,round(used_memory/1024/1024,2) as used_memory,round(used_memory_rss/1024/1024,2) as used_memory_rss,round(used_memory_peak/1024/1024,2) as used_memory_peak,mem_fragmentation_ratio,used_cpu_sys,used_cpu_user,used_cpu_sys_children,used_cpu_user_children from redis_resource_status_history where server_id=$server_id and YmdHi>=$time order by create_time;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }   
    function get_redis_persistence_status($server_id,$time){
        $query=$this->db->query("select unix_timestamp(create_time)*1000 as time,rdb_changes_since_last_save,rdb_last_bgsave_time_sec,aof_last_rewrite_time_sec,round(aof_current_size/1024/1024,2) as aof_current_size from redis_persistence_status_history where server_id=$server_id and YmdHi>=$time order by create_time;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }   

    function get_redis_keyspace_status($server_id,$time){
        //$query=$this->db->query("select left(create_time,16) as time,db_keys,expires,persists,avg_ttl from redis_keyspace_history where server_id=$server_id and YmdHi>=$time order by create_time;");
        $query=$this->db->query("select unix_timestamp(create_time)*1000 as time,db_keys,expires,persists,avg_ttl from redis_keyspace_history where server_id=$server_id and YmdHi>=$time order by create_time;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }   
        
    function get_replication_delay($server_id,$time){
        $sql="select unix_timestamp(create_time)*1000 as time,delay as repl_delay from redis_replication_history where server_id=$server_id and YmdHi>=$time and delay<>'---' and delay not like '-%' order by create_time; ";
        $query=$this->db->query($sql);
        if ($query->num_rows() > 0)
        {
           //return $query->row_array(); 
           return $query->result_array(); 
        }
    }

}

/* End of file chart_model.php */
/* Location: ./application/models/chart_model.php */
