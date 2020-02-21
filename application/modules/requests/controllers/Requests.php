<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requests extends MY_Controller {
	public function __construct() {
		$this->load->library('../../common/controllers/Common');
	}

	public function index(){
		$params['where'] = array("status" => 0);
		$data['location'] = $this->MY_Model->getRows('tbl_locations',$params);

		$this->load_page('index', $data);
	}

	public function getApprovals()
	{
		$params['where'] = array(
			"request_id" => $this->input->post('id')
		);

		$result = $this->MY_Model->getRows('tbl_request', $params, 'row_array');
		$items = json_decode($result['items']);
		$str='';
		$str="<input name='location' type='hidden' value='".$result['requested_from']."'>";
		foreach ($items as $info) {
			$para['select'] = "name, (SELECT qty from tbl_stocks where product_id = ".$info->item_id." and location = ".$result['requested_from'].") as dest_stock,(SELECT qty from tbl_stocks where product_id = ".$info->item_id." and location = 1) as ware_stock";

			$para['where'] = array("product_id" => $info->item_id);
			$res = $this->MY_Model->getRows('tbl_products', $para, 'row_array');
			$name = $res['name'];

			$str .= "<tr><td><input name='items[]' type='hidden' value='$info->item_id'>".$name."</td>";
			$str .= "<td>".$info->qty."</td>";
			$str .= "<td><input type='number' name='quantity[]' min='0' max='$info->qty' class='form-control qty_num' value='$info->qty'></td>";
			$str .= "<td class='warehouse_stock' data-stock=".$res['ware_stock'].">".$res['ware_stock']."</td>";
			$str .= "<td class='afterWare'>".$res['ware_stock']."</td>";
			$str .= "<td class='dest_stock' data-stock=".$res['dest_stock'].">".$res['dest_stock']."</td>";
			$str .= "<td class='afterDest'>".$res['dest_stock']."</td>";
		}

		$data = array(
			"request" =>$result['request_id'],
			// "requested_by" => ucwords($result['fname'] . " " . $result['lname']),
			// "from" => $result['loc_from'],
			// "requested_date" => $result['date_requested'],
			// "remark" => $result['remarks'],
			// "status" =>$result['status'],
			"string" => $str
		);

		echo json_encode($data);
		// echo "<pre>";
		// print_r($this->input->post());
		// exit;
	}

	public function transferStock() {
		$transfer_id = $this->common->transferItems();

		if (!$transfer_id) {
			ajax_response('Something Went wrong', 'error');
		}

		$data = array(
			"approved_by" => $this->session->userdata('id'),
			"approved_date" => date('Y-m-d'),
			"status" => 1,
			'remarks' => ($this->input->post('remark') != '') ?$this->input->post('remark') : "-",
			"transfer_id" => $transfer_id
		);

		$where_arr = array(
			'request_id' => $this->input->post('req_id'),
		);

		$result = $this->MY_Model->update('tbl_request', $data, $where_arr);

	    ($transfer_id) ?ajax_response('Added Successfully','success') :ajax_response('Add Successfully','success');
	}

	public function viewRequest() {
		$params['where'] = array(
			"request_id" => $this->input->post('id')
		);

		$params['select'] ="*,
		 (SELECT name from tbl_locations where location_id = tbl_request.requested_from) as loc_from,
		 (SELECT fname from tbl_user_details where user_id = tbl_request.requested_by) as fname,
		 (SELECT lname from tbl_user_details where user_id = tbl_request.requested_by) as lname";

		$result = $this->MY_Model->getRows('tbl_request', $params, 'row_array');
		$td = array();
		$items = json_decode($result['items']);

		foreach ($items as $info) {
			$para['select'] = "name,
			 (SELECT qty from tbl_stocks where product_id = ".$info->item_id." and location = ".$result['requested_from'].") as dest_stock,
			 (SELECT qty from tbl_stocks where product_id = ".$info->item_id." and location = 1) as ware_stock,
			 (SELECT min_stock_kiosk from tbl_products where product_id = ".$info->item_id.") as Kiosk_min,
			 (SELECT min_stock_warehouse from tbl_products where product_id = ".$info->item_id.") as Warehouse_min
		 	";

			$para['where'] = array("product_id" => $info->item_id);
			$res = $this->MY_Model->getRows('tbl_products', $para, 'row_array');
			$name = $res['name'];

			$stat =''; $ware_stat='';

			if (intval($res['Warehouse_min']) > intval($res['ware_stock'])) {
				$ware_stat = "Low Stock";
			} elseif(intval($res['ware_stock']) == 0){
				$ware_stat = "Out of Stock";
			} else {
				$ware_stat = "In Stock";
			};
			if (intval($res['Kiosk_min']) > intval($res['dest_stock'])) {
				$stat = "Low Stock";
			} elseif(intval($res['dest_stock']) == 0){
				$stat = "Out of Stock";
			} else {
				$stat = "In Stock";
			};
			$after = $res['ware_stock'] - $info->qty;

			$td[] = array($name, $info->qty, $ware_stat, $res['ware_stock'], $after,($res['dest_stock'] != null)?$res['dest_stock']:0,$stat);
		}

		$table_data = array(
			"header" => array("Product Name", "Quantity Requested", "CSO Item Status", "CSO Stock", "Qty. After","Kiosk Stock","Kiosk Item Status"),
			"data"=>$td
		);

		$data = array(
			"request" =>$result['request_id'],
			"requested_by" => ucwords($result['fname'] . " " . $result['lname']),
			"requested_from_location" => $result['requested_from'],
			"from" => $result['loc_from'],
			"requested_date" => $result['date_requested'],
			"remark" => $result['remarks'],
			"status" =>$result['status'],
			"table" => $table_data,
			"items" => $items
		);

		echo json_encode($data);
	}


	public function declineRequest()
	{
		$data = array(
			"remarks" => $this->input->post('remark'),
			"status" => 3
		);
		$where = array(
			"request_id" => $this->input->post('id')
		);
		$res = $this->MY_Model->update('tbl_request', $data, $where);
		ajax_response('Submitted Successfully','success');
	}


	public function requestDataTable(){
		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$column_order = array('request_id', 'date_requested');
		$join = array();
		$where = array();


		if ($this->input->post('status') != 'all') {
			$where['status'] = $this->input->post('status');
		}

		$select ="*, (SELECT fname FROM tbl_user_details WHERE user_id = requested_by) as Fname, (SELECT lname FROM tbl_user_details WHERE user_id = requested_by) as Lname, (SELECT name FROM tbl_locations WHERE location_id = requested_from) as Location";
		$group = array();
		$list = $this->MY_Model->get_datatables('tbl_request',$column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
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
