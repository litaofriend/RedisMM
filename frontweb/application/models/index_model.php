<?php
class Index_model extends CI_Model{

	function get_count_redis_status($table,$condition,$value)
	{
		$this->db->select('count(*) as num');
                $this->db->from($table);
                if ($table != 'servers')
                {
                	$this->db->join('servers', "$table.server_id=servers.id", 'left');
                }
                $this->db->where('servers.is_delete', '0');
                if (!empty($condition) && !empty($value))
                {
                	 $this->db->where($condition,$value);
                }
                else if (!empty($condition) && empty($value)) 
                {
                	$this->db->where($condition);
                }
                !empty($_GET["application_id"]) && $this->db->where("servers.application_id", $_GET["application_id"]);

                	$query = $this->db->get();
                if ($query->num_rows() > 0)
		{
			return $query->row()->num;
		}
	}

	function exe_num_sql_table($num_sql,$table,$where/*,$condition,$value*/)
	{
	     $this->db->select($num_sql);
             $this->db->from($table);
             if ($table != 'servers')
             {
             	$this->db->join('servers', "$table.server_id=servers.id", 'left');
             }
             !empty($where) && $this->db->where($where);
             $this->db->where('servers.is_delete', '0'); 
             //!empty($condition) && $this->db->where($condition,$value);
             !empty($_GET["application_id"]) && $this->db->where("servers.application_id", $_GET["application_id"]);

             	$query = $this->db->get();
             if ($query->num_rows() > 0)
		{
			return $query->row()->num;
		}
	}
	
	function get_alarm_last_status()
	{
		$sql="select alarm_history.*,servers.host,servers.port,servers.alarm_person,application.display_name application from alarm_history,servers,application where alarm_history.server_id=servers.id and servers.application_id=application.id and alarm_history.send_mail_status='1'";
		!empty($_GET["application_id"]) && $sql=$sql." and servers.application_id=".$_GET["application_id"];
		$sql=$sql." order by alarm_history.id desc limit 7";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
    }

    function get_redis_versions()
	{
		$sql="select redis_version as versions, count(*) as num from redis_server_status,servers where redis_server_status.server_id=servers.id and redis_version !='---' ";
		!empty($_GET["application_id"]) && $sql=$sql." and servers.application_id=".$_GET["application_id"];
		$sql=$sql." GROUP BY versions";
		$query=$this->db->query($sql);
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }

    }
    
}
