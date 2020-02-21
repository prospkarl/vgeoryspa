<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery extends MY_Controller {
	public function index(){
		$data['header_right'] = array(
            array(
                'type' => 'button',
                'name' => 'Deliver Stocks',
                'modal' => 'transferMod',
                'icon' => 'fa fa-plus-circle'
            ),
        );
		$data['include_js'] = array(
			'name' => 'purchase_order'
		);
		$params['where'] = array("location_id !=" => 1);
		$data['location'] = $this->MY_Model->getRows('tbl_locations',$params);
		$params1['where'] = array("location_id" => 1);
		$data['location_from'] = $this->MY_Model->getRows('tbl_locations',$params1);
		$this->load_page("index", $data);
    }

	public function getTotalCost() {
		$where = '';

		if ($this->input->post('status') == 'all') {
			$where = "location_from = 1";
		}else{
			$where = "location_from = 1 AND status = " . $this->input->post('status');
		}

		$date_from = '';
		$date_to = '';

		if (!empty($this->input->post('fromDate')) && !empty($this->input->post('toDate'))) {
			$date_from = date('Y-m-d', strtotime($this->input->post('fromDate')));
			$date_to = date('Y-m-d', strtotime($this->input->post('toDate')));
		}

		if (!empty($date_from)) {
			$where .= " AND date_added BETWEEN '$date_from' AND '$date_to'";
		}

		$options['select'] = 'total_amount as total';
		$options['select_sum'] = 'total_amount';
		$options['where'] = $where;

		$total_cost = $this->MY_Model->getRows('tbl_stocktransfer', $options, 'row_array');

		echo "&#x20B1; " . number_format($total_cost['total_amount'], 2);
	}

	public function transferDatatable(){
		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$column_order = array('transfer_id');
		$join = array();

		$where = '';

		if ($this->input->post('status') == 'all') {
			$where = "location_from = 1";
		}else{
			$where = "location_from = 1 AND status = " . $this->input->post('status');
		}

		$date_from = '';
		$date_to = '';

		if (!empty($this->input->post('fromDate')) && !empty($this->input->post('toDate'))) {
			$date_from = date('Y-m-d', strtotime($this->input->post('fromDate')));
			$date_to = date('Y-m-d', strtotime($this->input->post('toDate')));
		}

		if (!empty($date_from)) {
			$where .= " AND date_added BETWEEN '$date_from' AND '$date_to'";
		}

		$select ="*, (SELECT name from tbl_locations where location_id = tbl_stocktransfer.location_to) as loc_to, (SELECT name from tbl_locations where location_id = tbl_stocktransfer.location_from) as loc_from, (SELECT name from tbl_locations where location_id = tbl_stocktransfer.location_from) as loc_from,(SELECT fname from tbl_user_details where user_id = tbl_stocktransfer.transfer_by) as fname,(SELECT lname from tbl_user_details where user_id = tbl_stocktransfer.transfer_by) as lname";
		$group = array();
		$list = $this->MY_Model->get_datatables('tbl_stocktransfer',$column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
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
