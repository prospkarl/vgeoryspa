<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller {

	public function index(){
		$locations = $this->MY_Model->getRows('tbl_locations', array('where' => array('status' => 0)));

		$options = array();

		foreach ($locations as $loc) {
			if ($this->session->type != 2) {
				$options[$loc['location_id']] = $loc['name'];
			}else {
				if ($this->session->location == $loc['location_id']) {
					$options[$loc['location_id']] = $loc['name'];
				}
			}
		}

		$data['header_right'] = array(
			'stock_level' => array(
				'type' => 'select',
				'name' => 'stock_level',
				'options' => array(
					'all' => 'All',
					'low' => 'Low Stock',
					'in' => 'In Stock',
					'out' => 'Out of Stock',
				)
			),
			'location' => array(
				'type' => 'select',
				'name' => 'location',
				'options' => $options,
			),
			'action_button' => array(
				'type' => 'button',
				'name' => 'Add Product',
				// 'modal' => 'underMaintenance',
				'modal' => 'addProduct',
				'icon' => 'fa fa-plus-circle',
			)
		);

		if ($this->input->get('loc')) {
			$data['header_right']['location']['selected'] = $this->input->get('loc');
			$data['header_right']['stock_level']['selected'] = 'low';
		}

		if ($this->session->type == 2) {
			unset($data['header_right']['action_button']);
			$data['header_right']['location']['selected'] = $this->session->location;
		}

		$data['locations'] = $this->MY_Model->getRows('tbl_locations');
		$cat_options = array('order' => 'name');
		$cat_options['where'] = array('status' => 0);
		$data['categories'] = $this->MY_Model->getRows('tbl_categories', $cat_options);

		$this->load_page('index', $data);
	}

	public function update() {
		$post = $this->input->post();

		$required = array('category', 'product_name', 'price', 'min_stock_warehouse', 'min_stock_kiosk');

		$errors = check_empty_fields($required, $post);

		if (!empty($errors)) {
			$return_error = '';

			foreach ($errors as $key => $error) {
				$return_error .= '<p><strong>"'.ucwords(str_replace('_', ' ', $key)).'"</strong> is required</p>';
			}

			ajax_response($return_error, 'error');
		}

		$update_product = array (
			'barcode' => $post['barcode_no'],
			'sku' => $post['sku'],
			'name' => $post['product_name'],
			'category' => $post['category'],
			'description' => $post['description'],
			'min_stock_warehouse' => $post['min_stock_warehouse'],
			'min_stock_kiosk' => $post['min_stock_kiosk'],
			'category' => $post['category'],
			'supplier_price' => str_replace(',', '', $post['supplier_price']),
			'price' => str_replace(',', '', $post['price']),
			'date_modified' => date("Y-m-d H:i:s")
		);

		$where = array(
			'product_id' => $post['product_id']
		);

		$check_product = $this->MY_Model->getRows('tbl_products', array('where'=>$where), 'row_array');

		$check_existing = array('barcode', 'sku');

		foreach ($check_existing as $check) {
			if ($check_product[$check] != $update_product[$check]) {
				$chk_op = array(
					$check => $update_product[$check]
				);

				$exist = $this->MY_Model->getRows('tbl_products', array('where' => $chk_op), 'count');

				if ($exist) {
					ajax_response('"'.ucwords($check).'" already used', 'error');
				}
			}
		}

		$update = $this->MY_Model->update('tbl_products', $update_product, $where);

		if ($update) {
			$check_op['where'] = array('product_id' => $post['product_id'], 'location' => 1);
			$check_qty = $this->MY_Model->getRows('tbl_stocks', $check_op, 'row_array');

			if ($check_qty['qty'] != $post['quantity']) {

				if ($check_qty['qty'] != $post['quantity']) {
					$log = 'Quantity changed from '.$check_qty['qty'].' to '. $post['quantity'];

					$log_info = array(
						'referrer_id' => $post['product_id'],
						'log' => $log,
						'logged_by' => $this->session->id
					);
					$this->create_log('tbl_products', $log_info);
				}

				$set = array(
					'qty' => $post['quantity']
				);

				$where = array(
					'location' => 1,
					'product_id' => $post['product_id']
				);

				$this->MY_Model->update('tbl_stocks', $set, $where);
			}

			ajax_response('Product Updated!', 'success');
		}else {
			ajax_response('Something went wrong!', 'success');
		}
	}

	public function add() {
		$post = $this->input->post();

		$required = array('category', 'product_name', 'price', 'min_stock_warehouse', 'min_stock_kiosk');

		$errors = check_empty_fields($required, $post);

		if (!empty($errors)) {
			$return_error = '';

			foreach ($errors as $key => $error) {
				$return_error .= '<p><strong>"'.ucwords(str_replace('_', ' ', $key)).'"</strong> is required</p>';
			}

			ajax_response($return_error, 'error');
		}

		$insert_product = array (
			'barcode' => $post['barcode_no'],
			'sku' => $post['sku'],
			'name' => $post['product_name'],
			'category' => $post['category'],
			'description' => $post['description'],
			'min_stock_warehouse' => $post['min_stock_warehouse'],
			'min_stock_kiosk' => $post['min_stock_kiosk'],
			'category' => $post['category'],
			'price' => str_replace(',', '', $post['price']),
			'supplier_price' => str_replace(',', '', $post['supplier_price']),
			'beg_bal' => $post['initial_quantity'],
			'added_by' => $this->session->id,
			'date_modified' => date("Y-m-d H:i:s"),
			'date_added' => date("Y-m-d")

		);

		$check_existing = array('barcode', 'sku');

		foreach ($check_existing as $check) {
			$check_options['where'] = array($check => $insert_product[$check]);
			$check_valid = $this->MY_Model->getRows('tbl_products', $check_options, 'count');

			if ($check_valid && !empty($insert_product[$check])) {
				ajax_response('"'.ucwords($check).'" already used', 'error');
			}
		}

		$insert_id = $this->MY_Model->insert('tbl_products', $insert_product);

		if ($insert_id) {
			$insert_stock = array (
				'product_id' => $insert_id,
				'location' => 1, //Warehouse = 1
				'qty' => $post['initial_quantity']
			);

			$this->MY_Model->insert('tbl_stocks', $insert_stock);

			ajax_response('Product added successfully', 'success');
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function productDataTable() {
        $limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $search = $this->input->post('search');
        $order = $this->input->post('order');
        $draw = $this->input->post('draw');

		$select = "
			tbl_products.min_stock_warehouse,
			tbl_products.min_stock_kiosk,
			tbl_products.product_id,
			tbl_products.name,
			tbl_categories.name as category_name,
			FORMAT(tbl_products.price,'c','en-PH') AS 'price',
			DATE(tbl_products.date_modified) as date_modified,
			tbl_stocks.qty,
		";

		if ($this->session->type != 2) {
			$select .= "tbl_products.product_id as editable,";
		}

		if ($this->input->post('location') == 1) {
			$select .= " tbl_products.beg_bal as beg_balance";
		}else {
			$select .= " (SELECT beg_balance from tbl_beginning_bal where location = " . $this->input->post('location'). " and product_id = `tbl_products`.`product_id`) as beg_balance";
		}

        $join = array(
			'tbl_products' => 'tbl_stocks.product_id = tbl_products.product_id',
			'tbl_categories' => 'tbl_categories.category_id = tbl_products.category',
			'tbl_locations' => 'tbl_stocks.location = tbl_locations.location_id',
		);

        $where = array('tbl_products.status' => 1);
		$where_sub = array();

		if ($this->input->post('location') != 'all') {
			$where['tbl_stocks.location'] = $this->input->post('location');
		}


		if ($this->input->post('stock_level') != 'all') {
			$operation = '';

			switch ($this->input->post('stock_level')) {
				case 'low':
					$operation = '<=';
					break;
				case 'in':
					$operation = '>';
					break;
				case 'out':
					$operation = null;
					break;
			}

			if ($operation != null) {
				if ($this->input->post('location') == 1) {
					$where_sub = '`tbl_stocks`.`qty` '.$operation . ' (SELECT tbl_products.min_stock_warehouse from tbl_products WHERE tbl_products.product_id LIKE tbl_stocks.product_id) ';
				}else {
					$where_sub = '`tbl_stocks`.`qty` '.$operation . ' (SELECT tbl_products.min_stock_kiosk from tbl_products WHERE tbl_products.product_id LIKE tbl_stocks.product_id)';
				}

				if ($operation == '<=') {
					$where['tbl_stocks.qty <>'] = 0;
				}
			}else {
				$where['tbl_stocks.qty'] = 0;
			}
		}

        $group = array();
		$column_order = array('tbl_categories.name','tbl_products.name' ,'tbl_products.price','tbl_stocks.qty', 'tbl_products.sku', 'tbl_products.date_modified');

        $list = $this->MY_Model->get_datatables('tbl_stocks', $column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group, $where_sub);

		$output = array(
               "draw" => $draw,
               "recordsTotal" => $list['count_all'],
               "recordsFiltered" => $list['count'],
               "data" => $list['data'],
               "limit" => $limit,
               "offset" => $offset,
        );

        echo json_encode($output);
    }

	public function getproductinfo() {
		$options['select'] = "
			tbl_products.product_id,
			tbl_products.barcode,
			tbl_products.sku,
			tbl_products.name,
			tbl_products.description,
			tbl_products.category as category_id,
			tbl_categories.name as category,
			tbl_locations.name as location,
			tbl_stocks.qty,
			tbl_products.min_stock_warehouse,
			tbl_products.min_stock_kiosk,
			FORMAT(tbl_products.supplier_price,'c','en-US') AS 'supplier_price',
			FORMAT(tbl_products.price,'c','en-US') AS 'price',
			tbl_user_details.fname as added_by,
			tbl_products.date_modified
		";

        $options['join'] = array(
			'tbl_products' => 'tbl_stocks.product_id = tbl_products.product_id',
			'tbl_locations' => 'tbl_locations.location_id = tbl_stocks.location',
			'tbl_categories' => 'tbl_categories.category_id = tbl_products.category',
			'tbl_user_details' => 'tbl_user_details.user_id = tbl_products.added_by',
		);

        $options['where'] = array(
			'tbl_products.product_id' => $this->input->post('product_id'),
			'tbl_stocks.location' => $this->input->post('location_id')
		);

    	$data = $this->MY_Model->getRows('tbl_stocks', $options, 'row_array');

        echo json_encode($data);
	}

	public function update_beg_bal() {
		$curr_loc = $this->session->location;

		$location_products_op['select'] = 'product_id, qty';
		$location_products_op['where'] = array(
			'location' => $this->session->location
		);

		$list_prod = $this->MY_Model->getRows('tbl_stocks', $location_products_op);

		foreach ($list_prod as $product) {
			$where = array('location' => $curr_loc, 'product_id' => $product['product_id']);

			$if_exist = $this->MY_Model->getRows('tbl_beginning_bal', array('where'=>$where), 'count');
			$set = array('beg_balance' => $product['qty']);

			if ($if_exist) {
				$this->MY_Model->update('tbl_beginning_bal', $set, $where);
			}else {
				$insert = array(
					'product_id' => $product['product_id'],
					'location' => $curr_loc,
					'beg_balance' => $product['qty']
				);
				$this->MY_Model->insert('tbl_beginning_bal', $insert);
			}

			$where = array(
				'item_id' => $product['product_id'],
				'location' => $curr_loc,
			);

			$this->MY_Model->delete('tbl_inventory_movement', $where);
		}

		ajax_response('Beginning Balance Calibrated', 'success');
	}

	public function delete(){
		$product_id = $this->input->post('product_id');
		$set = array( 'status' => 0 );
		$where = array( 'product_id' => $product_id );

		// $this->MY_Model->delete('tbl_stocks', $where);
		$this->MY_Model->update('tbl_products', $set, $where);

		ajax_response('Item deleted successfully!', 'success');
	}
}
