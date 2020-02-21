<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Top_sellers extends MY_Controller {
	public function index(){
		$this->load_page("index");
	}

	public function topseller(){
		$mon=''; $year='';
		$mon = ($this->input->post('mon') != '') ? (int)$this->input->post('mon') : date('m');
		$year = ($this->input->post('year') != '') ?(int)$this->input->post('year') : date('Y');
		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$column_order = array('user_id', 'user_id');
		$join = array();
		$where = array("position" => "Sales",);
		$select ="*, (SELECT SUM(total_amount) FROM tbl_sales WHERE issued_by = tbl_user_details.user_id AND MONTH(`date_issued`) = '$mon' AND YEAR(`date_issued`) = '$year') as total_sales,(SELECT SUM(total_items) FROM tbl_sales WHERE issued_by = tbl_user_details.user_id AND MONTH(`date_issued`) = '$mon' AND YEAR(`date_issued`) = '$year') as total_items";
		$group = array();
		$this->db->order_by("total_sales", "desc");
		$list = $this->MY_Model->get_datatables('tbl_user_details',$column_order, $select, $where, $join, $limit, $offset ,$search, $order="", $group);

		$output = array(
			   "draw" => $draw,
			   "recordsTotal" => $list['count_all'],
			   "recordsFiltered" => $list['count'],
			   "data" => $list['data'],
			   "limit" => $limit,
			   "offset" => $offset,
			   "query" => $list['query']
		);
		echo json_encode($output);
	}

	public function toplocation(){
		$mon=''; $year='';
		$mon = ($this->input->post('mon') != '') ? (int)$this->input->post('mon') : date('m');
		$year = ($this->input->post('year') != '') ?(int)$this->input->post('year') : date('Y');
		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$column_order = array('location_id');
		$join = array();
		$where = "";
		$select ="*, (SELECT SUM(total_amount) FROM tbl_sales WHERE location = tbl_locations.location_id AND MONTH(`date_issued`) = '$mon' AND YEAR(`date_issued`) = '$year') as total_sales, (SELECT SUM(total_items) FROM tbl_sales WHERE location = tbl_locations.location_id AND MONTH(`date_issued`) = '$mon' AND YEAR(`date_issued`) = '$year') as total_items";
		$group = array();
		$this->db->order_by("total_sales", "desc");
		$list = $this->MY_Model->get_datatables('tbl_locations',$column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
		$output = array(
			   "draw" => $draw,
			   "recordsTotal" => $list['count_all'],
			   "recordsFiltered" => $list['count'],
			   "data" => $list['data'],
			   "limit" => $limit,
			   "offset" => $offset
		);
		echo json_encode($output);
	}
}
?>
