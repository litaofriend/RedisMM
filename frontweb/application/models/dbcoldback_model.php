<?php 
class DbColdback_model extends CI_Model{
    protected $table='redis_coldback_config';
   
       /* function get_record_by_id($id){
                $query = $this->db->get_where($this->table, array('id' =>$id));
                if ($query->num_rows() > 0)
                {
                        return $query->row_array();
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
    }*/
 
    /*
    * 插入数据
    */
   	public function insert($data){		
		$this->db->insert($this->table, $data);
	}
    
    /*
	 * 更新信息
	*/
	public function update($data,$id){
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
	}
    
    /*
	 * 删除信息
	*/
	public function delete($id){
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}
	
    
}

/* End of file application_model.php */
/* Location: ./application/models/application_model.php */
