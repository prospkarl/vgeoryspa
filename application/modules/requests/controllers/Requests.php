<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requests extends MY_Controller {
	public function index(){
		$params['where'] = array("status" => 0);
		$data['location'] = $this->MY_Model->getRows('tbl_locations',$params);

		$this->load_page('index', $data);
	}


	public function transferItems(){
		$data_arr = array();
		$type = $this->input->post('type');

		if (empty($this->input->post('loc_to'))) {
			ajax_response('Select a location', 'warning');
		}

		if (empty($this->input->post('items'))) {
			ajax_response('Please select an item', 'warning');
		}

		foreach ($this->input->post('quantity') as $value) {
			if ($value < 1) {
				ajax_response('Item Quantity is required', 'warning');
			}
		}

		$inserted_stock_transfer = array();
		$total_amount = 0;

		for ($i=0; $i < count($this->input->post('items')); $i++) {
			$product_options['select'] = 'pass_on_cost';
			$product_options['where'] = array('product_id' => $this->input->post('items')[$i]);
			$product_info = $this->MY_Model->getRows('tbl_products', $product_options, 'row_array');
			$total_pass_cost = $product_info['pass_on_cost'] * $this->input->post('quantity')[$i];
			$total_amount = $total_amount + $total_pass_cost;

			$data_arr[] = array(
				"item_id"=> $this->input->post('items')[$i],
				"qty"=> $this->input->post('quantity')[$i],
				"pass_on_cost" => $product_info['pass_on_cost'],
				"total_pass_cost" => $total_pass_cost
			);

			$data_mov = array(
					"item_id" => $this->input->post('items')[$i],
					"item_qty" => $this->input->post('quantity')[$i],
					"type" => 1,
					"location" => $this->input->post('loc_from'),
					"movement_type" => "Stock Transfer",
					"date_added" => date('Y-m-d H:i:s')
			);
			$movement = $this->MY_Model->insert('tbl_inventory_movement',$data_mov);

			$inserted_stock_transfer[] = $movement;

			// - UPDATE LOCATION TO QUANTITY & BEG BALANCE
			$tbl_stock_options['where'] = array(
				'location' => $this->input->post('loc_to'),
				'product_id' => $this->input->post('items')[$i]
			);

			$check_beg_balance = $this->MY_Model->getRows('tbl_beginning_bal', $tbl_stock_options, 'count');

			if (empty($check_beg_balance)) {
				$set_new_beg_bal = array(
					'location' => $this->input->post('loc_to'),
					'product_id' => $this->input->post('items')[$i],
					'beg_balance' => 0
				);
				$this->MY_Model->insert('tbl_beginning_bal', $set_new_beg_bal);
			}

			$loc_to_stock_info = $this->MY_Model->getRows('tbl_stocks', $tbl_stock_options, 'row_array');

			if (!empty($loc_to_stock_info)) {
				$set_stocks = array(
					'qty' => $loc_to_stock_info['qty'] + $this->input->post('quantity')[$i]
				);
				$this->MY_Model->update('tbl_stocks', $set_stocks, $tbl_stock_options['where']);
			}else {
				$insert_data = array(
					'product_id' => $this->input->post('items')[$i],
					'location' => $this->input->post('loc_to'),
					'qty' => $this->input->post('quantity')[$i]
				);
				$this->MY_Model->insert('tbl_stocks', $insert_data);
			}
			// END UPDATE LOCATION TO QUANTITY & BEG BALANCE

			// UPDATE LOC FROM QUANTITY (MINUS LOCATION FROM QUANTITY)
			$location_from_op['where'] = array(
				'location' => $this->input->post('loc_from'),
				'product_id' => $this->input->post('items')[$i]
			);

			$loc_from_stock_info = $this->MY_Model->getRows('tbl_stocks', $location_from_op, 'row_array');

			$location_from_set = array(
				'qty' => $loc_from_stock_info['qty'] - $this->input->post('quantity')[$i]
			);
			$this->MY_Model->update('tbl_stocks', $location_from_set, $location_from_op['where']);
			// END ::: UPDATE LOC FROM QUANTITY (MINUS LOCATION FROM QUANTITY)
		}

		$data = array(
			"items" => json_encode($data_arr),
			"location_to" => $this->input->post('loc_to'),
			"location_from" =>$this->input->post('loc_from'),
			"transfer_by" => $this->session->userdata('id'),
			"total_amount" => $total_amount,
			"date_added" => date('Y-m-d')
		);
		$res = $this->MY_Model->insert('tbl_stocktransfer', $data);

		foreach ($inserted_stock_transfer as $stock_transfer_id) {
			$set = array('reference_id' => $res);
			$where = array( 'movement_id' => $stock_transfer_id );

			$this->MY_Model->update('tbl_inventory_movement', $set, $where);
		}

		$result = false;

		if ($res) {
			$result = true;
		}

		return $result;
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
		$transfer_id = $this->transferItems();

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
