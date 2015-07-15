<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Index extends Front_Controller {
    function __construct(){
		parent::__construct();
		$this->load->model('application_model','app');
		$this->load->model('index_model','index');
	}
    
    public function index(){
        
        $redis_status = array();
      
	$redis_status["all_redis_server"] = $this->index->exe_num_sql_table('count(distinct(host)) as num','servers','');
        $redis_status["all_redis_instance"] = $this->index->exe_num_sql_table('count(*) as num','servers','');
        $redis_status["master_redis_instance"] = $this->index->get_count_redis_status('redis_replication','is_master =','1');
        $redis_status["slave_redis_instance"] = $this->index->get_count_redis_status('redis_replication','is_slave =','1');
        
        $redis_status["normal_redis_instance"] = $this->index->get_count_redis_status('redis_server_status','connect =',"success");
        $redis_status["exception_redis_instance"] = $this->index->get_count_redis_status('redis_server_status','connect !=','success');
        
        $redis_status["redis_connections_100"] = $this->index->get_count_redis_status('redis_run_status','connected_clients >100','');
        $redis_status["redis_connections_200"] = $this->index->get_count_redis_status('redis_run_status','connected_clients >200','');
        $redis_status["redis_connections_500"] = $this->index->get_count_redis_status('redis_run_status','connected_clients >500','');
        $redis_status["redis_connections_1000"] = $this->index->get_count_redis_status('redis_run_status','connected_clients >1000','');
        $redis_status["redis_connections_5000"] = $this->index->get_count_redis_status('redis_run_status','connected_clients >5000','');

        $redis_status["redis_mem_use_ratio_10"] = $this->index->get_count_redis_status('redis_resource_status','ifnull(round(100*used_memory/max_memory,2),0) >10','');
        $redis_status["redis_mem_use_ratio_20"] = $this->index->get_count_redis_status('redis_resource_status','ifnull(round(100*used_memory/max_memory,2),0) >20','');
        $redis_status["redis_mem_use_ratio_50"] = $this->index->get_count_redis_status('redis_resource_status','ifnull(round(100*used_memory/max_memory,2),0) >50','');
        $redis_status["redis_mem_use_ratio_70"] = $this->index->get_count_redis_status('redis_resource_status','ifnull(round(100*used_memory/max_memory,2),0) >70','');
        $redis_status["redis_mem_use_ratio_90"] = $this->index->get_count_redis_status('redis_resource_status','ifnull(round(100*used_memory/max_memory,2),0) >90','');

        $redis_status["redis_keyspace_hit_rate_90"] = $this->index->get_count_redis_status('redis_run_status','keyspace_hit_rate <90','');
        $redis_status["redis_keyspace_hit_rate_70"] = $this->index->get_count_redis_status('redis_run_status','keyspace_hit_rate <70','');
        $redis_status["redis_keyspace_hit_rate_50"] = $this->index->get_count_redis_status('redis_run_status','keyspace_hit_rate <50','');
        $redis_status["redis_keyspace_hit_rate_20"] = $this->index->get_count_redis_status('redis_run_status','keyspace_hit_rate <20','');
        $redis_status["redis_keyspace_hit_rate_10"] = $this->index->get_count_redis_status('redis_run_status','keyspace_hit_rate <10','');

        $redis_status["redis_total_commands_processed_100"] = $this->index->get_count_redis_status('redis_run_status','total_commands_processed >100','');
        $redis_status["redis_total_commands_processed_1000"] = $this->index->get_count_redis_status('redis_run_status','total_commands_processed >1000','');
        $redis_status["redis_total_commands_processed_10000"] = $this->index->get_count_redis_status('redis_run_status','total_commands_processed >10000','');
        $redis_status["redis_total_commands_processed_100000"] = $this->index->get_count_redis_status('redis_run_status','total_commands_processed >100000','');
        $redis_status["redis_total_commands_processed_1000000"] = $this->index->get_count_redis_status('redis_run_status','total_commands_processed >1000000','');

        $redis_status["normal_redis_replication"] = $this->index->exe_num_sql_table('count(*) as num','redis_replication',"(redis_replication.is_slave='1' and redis_replication.master_link_status='up')");
        $redis_status["exception_redis_replication"] = $this->index->exe_num_sql_table('count(*) as num','redis_replication',"(redis_replication.is_slave='1' and (redis_replication.master_link_status!='up'))");
        
        $data["redis_status"] = $redis_status;
        
        $data["redis_versions"] = $this->index->get_redis_versions();
   	$data["last_alarm"] = $this->index->get_alarm_last_status();

        $data["cur_nav"]="index_index";
        
        $data["application"]=$this->app->get_total_record_usage();
        $setval["application_id"]=isset($_GET["application_id"]) ? $_GET["application_id"] : "";
        $data["setval"]=$setval;
        $this->layout->view("index/index",$data);
    }
    
}

/* End of file */
