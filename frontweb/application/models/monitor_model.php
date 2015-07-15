<?php 
class Monitor_model extends CI_Model{
	function insert($table,$data){		
		$this->db->insert($table, $data);
	}   

	function get_total_rows($table){
		$this->db->from($table);
		return $this->db->count_all_results();
	}

        function get_total_replication_rows($table,$application_id,$server_id,$role){
                $this->db->from($table);
                if($application_id!=""){
                   $this->db->where("application_id",$application_id);
                }
                if($server_id!=""){
                   $this->db->where("server_id",$server_id);
                }
                if($role!=""){
                   $this->db->where("role",$role);
                }
                return $this->db->count_all_results();
        }
    
    function get_total_record($table){
        $query = $this->db->get($table);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
    
    function get_total_record_paging($table,$limit,$offset){
        $query = $this->db->get($table,$limit,$offset);
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
    
    function get_total_record_sql($sql){
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
		{
			$result['datalist']=$query->result_array();
            $result['datacount']=$query->num_rows();
            return $result;
		}
    }

    function get_redis_server_status()
    {
	$this->db->select('redis_server_status.*,servers.host,servers.port,application.display_name application');
        $this->db->from('redis_server_status');
        $this->db->join('servers', 'redis_server_status.server_id=servers.id', 'left');
        $this->db->join('application', 'servers.application_id=application.id', 'left');

	!empty($_GET["application_id"]) && $this->db->where("redis_server_status.application_id",$_GET["application_id"]);
	!empty($_GET["server_id"]) && $this->db->where("redis_server_status.server_id",$_GET["server_id"]);

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }	


    function get_redis_server_history_status($limit,$offset)
    {
        $this->db->select('redis_server_status_history.*,servers.host,servers.port,application.display_name application');
        $this->db->from('redis_server_status_history');
        $this->db->join('servers', 'redis_server_status_history.server_id=servers.id', 'left');
        $this->db->join('application', 'servers.application_id=application.id', 'left');

#        !empty($_GET["application_id"]) && $this->db->where("redis_server_status_history.application_id",$_GET["application_id"]);
#        !empty($_GET["server_id"]) && $this->db->where("redis_server_status_history.server_id",$_GET["server_id"]);
        $where="(redis_server_status_history.connect<>'success' or (redis_server_status_history.connect='success' and right(redis_server_status_history.uptime,2)='00'))";
        !empty($_GET["application_id"]) && $where=$where." and redis_server_status_history.application_id=".$_GET['application_id'];
        !empty($_GET["server_id"]) &&  $where=$where." and redis_server_status_history.server_id=".$_GET['server_id'];
        $this->db->where($where);
        $this->db->order_by('redis_server_status_history.create_time','desc');
        $this->db->limit($limit,$offset);

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }

    function get_redis_run_status()
    {
        $this->db->select('redis_run_status.*,servers.host,servers.port,application.display_name application');
        $this->db->from('redis_run_status');
        $this->db->join('servers', 'redis_run_status.server_id=servers.id', 'left');
        $this->db->join('application', 'servers.application_id=application.id', 'left');

        !empty($_GET["application_id"]) && $this->db->where("redis_run_status.application_id",$_GET["application_id"]);
        !empty($_GET["server_id"]) && $this->db->where("redis_run_status.server_id",$_GET["server_id"]);
        if(!empty($_GET["order"]) && !empty($_GET["order_type"]))
        {
          $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else
	{
	  $this->db->order_by('application_id,host,port desc');
	}
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }

    function get_redis_resource_status()
    {
        $this->db->select('redis_resource_status.*,servers.host,servers.port,application.display_name application');
        $this->db->from('redis_resource_status');
        $this->db->join('servers', 'redis_resource_status.server_id=servers.id', 'left');
        $this->db->join('application', 'servers.application_id=application.id', 'left');

        !empty($_GET["application_id"]) && $this->db->where("redis_resource_status.application_id",$_GET["application_id"]);
        !empty($_GET["server_id"]) && $this->db->where("redis_resource_status.server_id",$_GET["server_id"]);
        if(!empty($_GET["order"]) && !empty($_GET["order_type"]))
        {
          $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else
        {
          $this->db->order_by('application_id,host,port desc');
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }

    function get_redis_persistence_status()
    {
        $this->db->select('redis_persistence_status.*,servers.host,servers.port,application.display_name application');
        $this->db->from('redis_persistence_status');
        $this->db->join('servers', 'redis_persistence_status.server_id=servers.id', 'left');
        $this->db->join('application', 'servers.application_id=application.id', 'left');
    
        !empty($_GET["application_id"]) && $this->db->where("redis_persistence_status.application_id",$_GET["application_id"]);
        !empty($_GET["server_id"]) && $this->db->where("redis_persistence_status.server_id",$_GET["server_id"]);
        if(!empty($_GET["order"]) && !empty($_GET["order_type"]))
        {
          $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else
        {
          $this->db->order_by('application_id,host,port desc');
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }

    function get_redis_keyspace()
    {
        $this->db->select('redis_keyspace.*,servers.host,servers.port,application.display_name application');
        $this->db->from('redis_keyspace');
        $this->db->join('servers', 'redis_keyspace.server_id=servers.id', 'left');
        $this->db->join('application', 'servers.application_id=application.id', 'left');

        !empty($_GET["application_id"]) && $this->db->where("redis_keyspace.application_id",$_GET["application_id"]);
        !empty($_GET["server_id"]) && $this->db->where("redis_keyspace.server_id",$_GET["server_id"]);
        if(!empty($_GET["order"]) && !empty($_GET["order_type"]))
        {
          $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else
        {
          $this->db->order_by('application_id,host,port desc');
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
           return $query->result_array();
        }
    }
    
    function get_total_record_replication(){
        
        $this->db->select('repl.*,servers.host,servers.port,application.display_name application');
        $this->db->from('redis_replication repl');
        $this->db->join('servers', 'repl.server_id=servers.id', 'left');
        $this->db->join('application', 'servers.application_id=application.id', 'left');
        
        !empty($_GET["application_id"]) && $this->db->where("repl.application_id", $_GET["application_id"]);
        !empty($_GET["server_id"]) && $this->db->where("repl.server_id", $_GET["server_id"]);
        if(!empty($_GET["role"]) ){
            $this->db->where($_GET["role"], 1);
        }
        //!empty($_GET["delay"]) && $this->db->where("delay >", (int)$_GET["delay"]);
        /*if(!empty($_GET["order"]) && !empty($_GET["order_type"])){
            $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else{
        	$this->db->order_by('servers.application_id');
        }*/
        
        $query = $this->db->get();
        if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
    }

    function get_total_record_paging_replication($limit,$offset){

        $this->db->select('repl.*,servers.host,servers.port,application.display_name application');
        $this->db->from('redis_replication repl');
        $this->db->join('servers', 'repl.server_id=servers.id', 'left');
        $this->db->join('application', 'servers.application_id=application.id', 'left');

        !empty($_GET["application_id"]) && $this->db->where("repl.application_id", $_GET["application_id"]);
        !empty($_GET["server_id"]) && $this->db->where("repl.server_id", $_GET["server_id"]);
        if(!empty($_GET["role"]) ){
            $this->db->where($_GET["role"], 1);
        }
        //!empty($_GET["delay"]) && $this->db->where("delay >", (int)$_GET["delay"]);
        /*if(!empty($_GET["order"]) && !empty($_GET["order_type"])){
            $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else{
                $this->db->order_by('servers.application_id');
        }*/

        $this->db->limit($limit,$offset);

        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
                return $query->result_array();
        }
    }

	function get_data_by_id($id){
		$query = $this->db->get_where($this->table, array('id' =>$id));
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
	}
	
	function update_view_count($id){
		$this->db->set('view_count', 'view_count+1',FALSE);
		$this->db->where('id', $id);
		$this->db->update($this->table);
	}

}

/* End of file */
