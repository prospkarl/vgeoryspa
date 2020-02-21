<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {
	public function index(){

		$data['location'] = $this->MY_Model->getRows('tbl_locations');

		$this->load_page("index", $data);
	}

	public function getSalesItems(){
		$params['where'] = array(
			"sales_id" => $this->input->post('id')
		);
		$res = $this->MY_Model->getRows('tbl_sales',$params);
		$items = json_decode($res[0]['items']);
		$str= '';
		foreach ($items as $key) {
			$para_name['select'] = 'name, price';
			$para_name['where'] = array("product_id" => $key->item_id);
			$rest = $this->MY_Model->getRows('tbl_products', $para_name,"row_array");
			$str .= '<tr>';
			$str .= '<td>'.$rest['name'].'</td>';
			$str .= '<td>'.$key->qty.'</td>';
			$str .= '<td>'.$rest['price'].'</td>';
			$str .= '<td>'.$key->total.'</td>';
			$str .= '</tr>';
		};
		$rem = "No remarks";
		if ($res[0]['remarks'] != null) {
			$rem = $res[0]['remarks'];
		};
		$data = array(
			"sales" => $res[0]['display_id'],
			"total_items" => $res[0]['total_items'],
			"total_amount" => $res[0]['total_amount'],
			"remark" => $rem,
			"string" => $str
		);
		echo json_encode($data);
	}

	public function getTotalSales(){
		$date_to = date("Y-m-d");
		$date_from = date("Y-m-d");
		if ($this->input->post('dates_to') != '' && $this->input->post('dates_from') != '') {
			$date_to = date("Y-m-d", strtotime($this->input->post('dates_to')));
			$date_from = date("Y-m-d", strtotime($this->input->post('dates_from')));
			$loc = $this->input->post('loc');
			if ($loc == 0) {
				$params['where'] = "date_issued between '$date_to' and '$date_from'";
			}else {
				$params['where'] = "date_issued between '$date_to' and '$date_from' and location = '$loc'";
			}
		}elseif ($this->input->post('dates_to') == '' && $this->input->post('dates_from') == '' ){
			$date_to = date("Y-m-d");
			$date_from = date("Y-m-d");
			$loc = $this->input->post('loc');
			if ($loc == 0) {
				$params['where'] = array();
			}else {
				$params['where'] = "location = '$loc'";
			}
		}else {
			$params['where'] = array();
		}

		if (is_array($params['where'])) {
			$params['where']['status'] = 1;
		}else {
			$params['where'] .= "and status = 1";
		}

		$params['select'] ="SUM(total_amount) as all_sales";
		$sales = $this->MY_Model->getRows('tbl_sales', $params)[0]['all_sales'];

		echo json_encode(number_format($sales, 2));
	}

	public function reportDatatable(){
		$date_to = date("Y-m-d");
		$date_from = date("Y-m-d");

		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$column_order = array('sales_id');
		$join = array();

		if ($this->input->post('dates_to') != '' && $this->input->post('dates_from') != '') {
			$date_to = date("Y-m-d", strtotime($this->input->post('dates_to')));
			$date_from = date("Y-m-d", strtotime($this->input->post('dates_from')));
			$loc = $this->input->post('loc');
			if ($loc == 0) {
				$where = "date_issued between '$date_to' and '$date_from'";
			}else {
				$where = "date_issued between '$date_to' and '$date_from' and location = '$loc'";
			}
		}elseif ($this->input->post('dates_to') == '' && $this->input->post('dates_from') == '' ){
			$date_to = date("Y-m-d");
			$date_from = date("Y-m-d");
			$loc = $this->input->post('loc');
			if ($loc == 0) {
				$where = array();
			}else {
				$where = "location = '$loc'";
			}
		}else {
			$where = array();
		}

		//Trap void
		if (is_array($where)) {
			$where['status'] = 1;
		}else {
			$where .= 'and status = 1';
		}

		$select ="*, (SELECT name from tbl_locations where location_id = tbl_sales.location) as loc, (SELECT fname from tbl_user_details where user_id = tbl_sales.issued_by) as fname,(SELECT lname from tbl_user_details where user_id = tbl_sales.issued_by) as lname,";
		$group = array();
		$list = $this->MY_Model->get_datatables('tbl_sales',$column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
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
}
