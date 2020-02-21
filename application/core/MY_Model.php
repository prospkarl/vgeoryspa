<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public function __construct(){
        parent::__construct();
    }


    public function raw($sql,$resut = 'array'){
        $query = $this->db->get($sql);

        switch ($result) {
            case 'array':
                return $query->result_array();
            break;
            case 'obj':
                return $query->result();
            break;
            case 'row':
            return $query->row();
                break;
            case 'count':
                return $query->num_rows();
            break;
            default:
                return $query->result_array();
            break;
        }
    }

    public function getRows($table,$options = array(),$result = 'array'){
        (!empty($options['select'])) ? $this->db->select($options['select']) : $this->db->select("*");

        (!empty($options['select_sum'])) ? $this->db->select_sum($options['select_sum']) : null;

        (!empty($options['where'])) ? $this->db->where($options['where']) : null;

        (!empty($options['where_sub'])) ? $this->db->where($options['where_sub']) : null;

        (!empty($options['or_where'])) ? $this->db->or_where($options['or_where']) : null;

        (!empty($options['like'])) ? $this->db->like($options['like']) : null;

        (!empty($options['or_like'])) ? $this->db->or_like($options['or_like']) : null;

        if (!empty($options['locate'])) {
            foreach ($options['locate'] as $or_like_and) {
                foreach ($or_like_and as $key => $like_query) {
                    $this->db->where("Locate('$like_query',$key) !=" , 0);
                }
            }
        }

        (!empty($options['or_like'])) ? $this->db->or_like($options['or_like']) : null;

        (!empty($options['where_not_in'])) ? $this->db->where_not_in($options['where_not_in']['col'],$options['where_not_in']['value']) : null;

        (!empty($options['where_in'])) ? $this->db->where_in($options['where_in']['col'],$options['where_in']['value']) : null;

        if(!empty($options['join'])){
            foreach($options['join'] as $key => $value){
                if(strpos($value,':') !== false){
                    $_join = explode(":",$value);
                    $this->db->join($key,$_join[0],$_join[1]);
                } else {
                    $this->db->join($key,$value);
                }
            }
        }

        (!empty($options['group'])) ? $this->db->group_by($options['group']) : null;

        (!empty($options['limit'])) ? $this->db->limit($options['limit'][0],$options['limit'][1]) : null;


        if (!empty($options['order_op'])) {
            (!empty($options['order'])) ? $this->db->order_by($options['order'], $options['order_op']) : null;
        }else {
            (!empty($options['order'])) ? $this->db->order_by($options['order']) : null;
        }

        $query = $this->db->get($table);

        switch ($result) {
            case 'array':
                return $query->result_array();
            break;
            case 'obj':
                return $query->result();
            break;
            case 'row':
                return $query->row();
            break;
            case 'row_array':
                return $query->row_array();
            break;
            case 'count':
                return $query->num_rows();
            break;
            default:
                return $query->result_array();
            break;
        }

    }

    public function get_datatables($table, $column_order, $select = "*", $where = "", $join = array(), $limit, $offset, $search, $order,$group = '', $where_sub = ''){
	  	$this->db->from($table);
	  	$columns = $this->db->list_fields($table);
	  	if($select){
	  		$this->db->select($select);
	  	}

	  	if($where){
	  		$this->db->where($where);
	  	}

        if ($where_sub) {
            $this->db->where($where_sub);
        }


	  	if($join){
	  		foreach ($join as $key => $value) {
				$this->db->join($key,$value,'left');
	  		}
	  	}
	  	if($search){
	  		$this->db->group_start();
	  		foreach ($column_order as $item)
	  		{
	  			$this->db->or_like($item, $search['value']);
	  		}
	  		$this->db->group_end();
	  	}
	  	if($group)
	  		$this->db->group_by($group);

	  	if($order)
	  		$this->db->order_by($column_order[$order['0']['column']], $order['0']['dir']);

	    	$temp = clone $this->db;
	    	$data['count'] = $temp->count_all_results();

	  	if($limit != -1)
	  		$this->db->limit($limit, $offset);

	  	$query = $this->db->get();
	  	$data['data'] = $query->result();

        $data['query'] = $this->db->last_query();

	  	$this->db->from($table);
	  	$data['count_all'] = $this->db->count_all_results();
	  	return $data;
	}

    public function insert($table,$data){
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }

    public function batch_insert($table,$set){
        $this->db->batch_insert($table,$set);
        return true;
     }

    public function update($table,$set,$where = array()){
        $this->db->where($where);
        $this->db->update($table,$set);
        return true;
    }

    public function delete($table,$where = array()){
        $this->db->where($where);
        $this->db->delete($table);
        return true;
    }
}
