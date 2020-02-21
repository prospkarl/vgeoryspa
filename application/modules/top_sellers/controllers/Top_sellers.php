<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Top_sellers extends MY_Controller {
	public function index(){
		$this->load_page("index");
	}

	public function topseller(){
		$start_date = ($this->input->post('start_date') != '') ? date('Y-m-d', strtotime($this->input->post('start_date'))) : date('Y-m-d', strtotime('first day of' . date('M Y')));
		$end_date = ($this->input->post('end_date') != '') ? date('Y-m-d', strtotime($this->input->post('end_date'))) : date('Y-m-d', strtotime('last day of' . date('M Y')));

		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$column_order = array('user_id', 'user_id');
		$join = array();
		$where = array("position" => "Sales",);
		$select ="
			*,
			(SELECT SUM(total_amount) FROM tbl_sales WHERE issued_by = tbl_user_details.user_id AND date_issued BETWEEN '$start_date' AND '$end_date' AND status = 1) as total_sales,
			(SELECT SUM(total_items) FROM tbl_sales WHERE issued_by = tbl_user_details.user_id AND date_issued BETWEEN '$start_date' AND '$end_date' AND status = 1) as total_items
			";
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
		$start_date = ($this->input->post('start_date') != '') ? date('Y-m-d', strtotime($this->input->post('start_date'))) : date('Y-m-d', strtotime('first day of' . date('M Y')));
		$end_date = ($this->input->post('end_date') != '') ? date('Y-m-d', strtotime($this->input->post('end_date'))) : date('Y-m-d', strtotime('last day of' . date('M Y')));

		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$column_order = array('location_id');
		$join = array();
		$where = "";
		$select ="*,
			(SELECT SUM(total_amount) FROM tbl_sales WHERE location = tbl_locations.location_id AND date_issued BETWEEN '$start_date' AND '$end_date' AND status = 1) as total_sales,
			(SELECT SUM(total_items) FROM tbl_sales WHERE location = tbl_locations.location_id AND date_issued BETWEEN '$start_date' AND '$end_date' AND status = 1) as total_items
		";

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
