<?php
class Att_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
//查询某个表所有数据
        public function get_tab_all($tabname)
        {
                $query = $this->db->get($tabname);
                return $query->result_array();

        }
//查询最新n条数据
        public function get_tab_diy($sql)
        {
                $query = $this->db->query($sql);
                return $query->result_array();

        }
//查询某个表第几页数据(每页10条)
        public function get_tab_page($tabname,$page,$orderid,$ordertype)
        {
		$start = ($page-1)*10;
		$sql_select = "select * from $tabname order by $orderid $ordertype limit $start,10";
		$query = $this->db->query($sql_select);
                return $query->result_array();

        }
//查询单条
	public function get_tab_one($tabname,$key,$val)
	{
		return $this->db->get_where($tabname, array($key => $val))->row();
	}

}
