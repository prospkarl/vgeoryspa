<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends MY_Controller {
	function __construct(){
		 parent::__construct();
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

    public function autocomplete() {
        $inputted = str_replace('\'', '', $this->input->post('input'));
        $this->db->distinct();
        $this->db->limit(5);
        $params['select'] = '*';

		if (strpos($inputted, ' ')) {
			$keywords = explode(' ', $inputted);

			foreach ($keywords as $key) {
				if (!empty($key)) {
					$params['locate'][] = array(
						'tbl_products.name' => $key
					);
				}
			}
		}else {
			$params['or_like'] = array(
				'tbl_products.barcode' => $inputted,
				'tbl_products.sku' => $inputted,
				'tbl_products.name' => $inputted,
			);
		}

		$params['where'] = array('status' => 1);

        $res = $this->MY_Model->getRows('tbl_products', $params);
        echo json_encode($res);
    }

	public function getiteminfo($product_id = '') {
		$product_info_options['select'] = 'name, price';

		$product_info_options['where'] = array(
			'product_id' => $product_id
		);

		$product_info = $this->MY_Model->getRows('tbl_products', $product_info_options, 'row_array');

		$return['product_name'] = $product_info['name'];
		$return['price'] = $product_info['price'];


		if ($this->input->post('location')) {
			$search_location = $this->input->post('location');
		}else {
			$search_location = $this->session->location;
		}

		$stock_info_options['where'] = array(
			'location' => $search_location,
			'product_id' => $product_id
		);

		$stock_info = $this->MY_Model->getRows('tbl_stocks', $stock_info_options, 'row_array');

		$return['current_qty'] = $stock_info['qty'] ? $stock_info['qty'] : '0';

		echo json_encode($return);
	}

	public function changepassword() {
		$post = $this->input->post();

		if ($post['password'] != $post['con_password']) {
			ajax_response('Please confirm your password', 'warning');
		}

		$required = array('password', 'con_password');
		$errors = check_empty_fields($required, $post);

		if (!empty($errors)) {
			ajax_response('Please input your password', 'warning');
		}

		$set = array(
			'password' => $post['password']
		);

		$where = array(
			'user_id' => $this->session->id
		);

		$update = $this->MY_Model->update('tbl_users', $set, $where);

		if ($update) {
			ajax_response('Successfully changed password!', 'success');
		}else {
			ajax_response('Something went wrong!', 'danger');
		}
	}


	public function switch_account() {
		$post = $this->input->post();

		$check_user_options['where'] = array(
			'username' => $post['username']
		);
		$check_user = $this->MY_Model->getRows('tbl_users', $check_user_options, 'row_array');

		if ($check_user['password'] == $post['password']) {
			$set_session = array(
				'username' => $check_user['username'],
				'id' => $check_user['user_id'],
				'type' => $check_user['user_type'],
			);

			$this->session->set_userdata($set_session);

			ajax_response('success', 'success');
		}else {
			ajax_response('Incorrect password!', 'error');
		}
	}

	public function getlogs() {
		$options['select'] = '
			tbl_logs.content,
			tbl_logs.date as date,
			CONCAT(tbl_user_details.fname , " " , tbl_user_details.lname) as logged_by,
		';

		$options['where'] = array(
			'referrer_id' => $this->input->post('referrer_id'),
			'table_name' => $this->input->post('tablename'),
		);
		$options['join'] = array(
			'tbl_user_details' => 'tbl_logs.logged_by = tbl_user_details.user_id'
		);

		$logs = $this->MY_Model->getRows('tbl_logs', $options);

		echo json_encode($logs);
	}
}
