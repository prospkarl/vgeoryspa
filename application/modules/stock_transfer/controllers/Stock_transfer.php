<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_transfer extends MY_Controller {
	public function index(){
		$data['header_right'] = array(
            array(
                'type' => 'button',
                'name' => 'Transfer Stocks',
                'modal' => 'transferMod',
                'icon' => 'fa fa-plus-circle'
            ),
        );

		$data['include_js'] = array(
			'name' => 'purchase_order'
		);
		$params['where'] = array("location_id !=" => 1);
		$data['location'] = $this->MY_Model->getRows('tbl_locations',$params);
		$data['location_from'] = $this->MY_Model->getRows('tbl_locations',$params);

		$this->load_page("index",$data);
	}


	public function getSelItem($product_id = '') {
		$product_info_options['select'] = 'name, price';

		$product_info_options['where'] = array(
			'product_id' => $product_id
		);

		$product_info = $this->MY_Model->getRows('tbl_products', $product_info_options, 'row_array');

		$return['product_name'] = $product_info['name'];
		$return['price'] = $product_info['price'];

		if ($this->input->post('location_from')) {
			$search_location = $this->input->post('location_from');
		}else {
			$search_location = $this->session->location;
		}

		$get_loc_to['where'] = array(
			'location' => $this->input->post('location_to'),
			'product_id' => $product_id
		);
		$stock_info_loc = $this->MY_Model->getRows('tbl_stocks', $get_loc_to, 'row_array');
		$return['dest_qty'] = $stock_info_loc['qty'] ? $stock_info_loc['qty'] : '0';
		$stock_info_options['where'] = array(
			'location' => $search_location,
			'product_id' => $product_id
		);

		$stock_info = $this->MY_Model->getRows('tbl_stocks', $stock_info_options, 'row_array');
		$return['current_qty'] = $stock_info['qty'] ? $stock_info['qty'] : '0';

		echo json_encode($return);
	}


	public function transferItemsSubmit(){
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
			$result = $res;
		}

		return $result;
	}

	public function transferItems(){
		$transfer = $this->transferItemsSubmit();
	   	($transfer) ?	ajax_response('Added Successfully','success') :ajax_response('Add Successfully','success');
	}

	public function view_transfer(){
		$params['where'] = array(
			"transfer_id" => $this->input->post('id')
		);

		$params['select'] ="*,(SELECT name from tbl_locations where location_id = tbl_stocktransfer.location_to) as loc_to, (SELECT name from tbl_locations where location_id = tbl_stocktransfer.location_from) as loc_from";

		$result = $this->MY_Model->getRows('tbl_stocktransfer', $params, 'row_array');
		$str = '';$td=array();
		$items = json_decode($result['items']);

		foreach ($items as $info) {
			$para['select'] = "name, pass_on_cost, (SELECT qty from tbl_stocks where product_id =".$info->item_id." and location = 1) as warehouse_stock,(SELECT qty from tbl_stocks where product_id = ".$info->item_id." and location = ".$result['location_to'].") as dest_stock, ";
			$para['where'] = array("product_id" => $info->item_id);
			$res = $this->MY_Model->getRows('tbl_products', $para, 'row_array');
			$name = $res['name'];
			$after_ware = $res['warehouse_stock'] - $info->qty;
			$after_dest = $res['dest_stock'] + $info->qty;
			$dest = ($res['dest_stock'] == '') ? 0 : $res['dest_stock'];
			$td[] = array($name, $info->qty, $res['warehouse_stock'], $after_ware, $dest, $after_dest, number_format($info->qty * $res['pass_on_cost']));
		}

		$table_data = array(
			"header" => array("Product Name", "Quantity To Transfer", "Warehouse Stock", "After Transfer", "Destination Stock", "After Transfer", "Total Cost (₱)"),
			"data"=>$td
		);

		$data = array(
			"transId" =>$result['transfer_id'],
			"to" => $result['loc_to'],
			"from" => $result['loc_from'],
			"date" => $result['date_added'],
			"table" => $table_data
		);
		echo json_encode($data);
	}

	public function receivedView()
	{
		$params['where'] = array(
			"transfer_id" => $this->input->post('id')
		);

		$params['select'] ="*,(SELECT name from tbl_locations where location_id = tbl_stocktransfer.location_to) as loc_to, (SELECT name from tbl_locations where location_id = tbl_stocktransfer.location_from) as loc_from";

		$result = $this->MY_Model->getRows('tbl_stocktransfer', $params, 'row_array');
		$str = '';
		$i = 0;
		$items = json_decode($result['items']);
		$items_rec = json_decode($result['items_received']);
		foreach ($items_rec as $info) {
			$para['select'] = "name, pass_on_cost";
			$para['where'] = array("product_id" => $info->item_id);
			$product_info = $this->MY_Model->getRows('tbl_products',$para,'row_array');
			if (isset($items[$i])) {
				$td[] = array($product_info['name'],$items[$i]->qty,$info->qty, number_format($info->qty * $product_info['pass_on_cost']));
			}else {
				$td[] = array($product_info['name'],$info->qty, $info->qty, number_format($info->qty * $product_info['pass_on_cost']));
			}
			$i++;
		}

		$table_data = array(
			"header" => array("Product Name"," Quantity Transferred","Quantity Received By Sales", "Total Cost (₱)"),
			"data"=>$td
		);

		$data = array(
			"transId" =>$result['transfer_id'],
			"to" => $result['loc_to'],
			"from" => $result['loc_from'],
			"date" => $result['date_received'],
			"table" => $table_data
		);
		echo json_encode($data);
	}
	public function transferDatatable(){
		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$column_order = array('transfer_id');
		$join = array();
		if ($this->input->post('status') == 'all') {
			$where = array("location_from !=" => 1);
		}elseif ($this->input->post('status') == 0) {
			$where = array("location_from !=" => 1, "status" => $this->input->post('status'));
		}elseif ($this->input->post('status') == 1) {
			$where = array("location_from !=" => 1, "status" => $this->input->post('status'));
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
			   "offset" => $offset
		);
		echo json_encode($output);
	}
}



?>
