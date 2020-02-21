<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request_items extends MY_Controller {
	function __construct(){
		 parent::__construct();
	}

    public function index() {
		$data['header_right'] = array(
			'action_button' => array(
				'type' => 'button',
				'name' => 'Create Request',
				// 'modal' => 'underMaintenance',
				'modal' => 'createRequest',
				'icon' => 'fa fa-plus-circle',
			)
		);

        $this->load_page('index', $data);
    }

	public function submit() {
		$items = $this->input->post('items');
		$quantity = $this->input->post('quantity');

		if (empty($items)) {
			ajax_response('Please select an item first', 'error');
		}

		foreach ($items as $item) {
			if ($item == "") {
				ajax_response('Please select an item first', 'error');
			}
		}

		$parsed_items = array();
		$total_qty = 0;

		for ($i=0; $i < count($items); $i++) {
			$parsed_items[] = array(
				'item_id' => $items[$i],
				'qty' => $quantity[$i],
			);

			$total_qty = $total_qty + $quantity[$i];
		}

		$parsed_items = json_encode($parsed_items);

		$insert_data = array(
			'items' => $parsed_items,
			'total_quantity' => $total_qty,
			'requested_by' => $this->session->id,
			'requested_from' => $this->session->location,
			'date_requested' => date('Y-m-d')
		);

		$insert = $this->MY_Model->insert('tbl_request', $insert_data);

		if ($insert) {
			ajax_response('Requested successfully!', 'success');
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function getiteminfo($product_id = '') {
		$product_info_options['select'] = 'name';

		$product_info_options['where'] = array(
			'product_id' => $product_id
		);

		$product_info = $this->MY_Model->getRows('tbl_products', $product_info_options, 'row_array');

		$return['product_name'] = $product_info['name'];

		$stock_info_options['where'] = array(
			'location' => $this->session->location,
			'product_id' => $product_id
		);

		$stock_info = $this->MY_Model->getRows('tbl_stocks', $stock_info_options, 'row_array');

		$stock_info_warehouse_options['where'] = array(
			'location' => 1, //Warehouse
			'product_id' => $product_id
		);

		$stock_info_warehouse = $this->MY_Model->getRows('tbl_stocks', $stock_info_warehouse_options, 'row_array');

		$return['current_qty'] = $stock_info['qty'] ? $stock_info['qty'] : '0';
		$return['warehouse_qty'] = $stock_info_warehouse['qty'] ? $stock_info_warehouse['qty'] : '0';

		echo json_encode($return);
	}

	public function getItems(){
		$items_options['select'] = 'tbl_request.date_requested, tbl_request.items, tbl_request.requested_by, tbl_request.status, tbl_request.remarks, tbl_user_details.fname';

		$items_options['where'] = array(
			'request_id' => $this->input->post('request_id')
		);

		$items_options['join'] = array(
			'tbl_user_details' => 'tbl_request.requested_by = tbl_user_details.user_id'
		);

		$request_info = $this->MY_Model->getRows('tbl_request', $items_options, 'row_array');

		$html = '';

		foreach (json_decode($request_info['items']) as $item) {
			$product_info_options['select'] = 'name';
			$product_info_options['where'] = array(
				'product_id' => $item->item_id,
			);

			$product_info = $this->MY_Model->getRows('tbl_products', $product_info_options, 'row_array');

			$stock_info_options['select'] = 'qty';
			$stock_info_options['where'] = array(
				'product_id' => $item->item_id,
				'location' => $this->session->location,
			);

			$stock_info = $this->MY_Model->getRows('tbl_stocks', $stock_info_options,'row_array');

			$new_qty = (int)$item->qty + (int)$stock_info['qty'];

			$html .= '<tr>';
			$html .= '<td>'.$product_info['name'].'</td>';
			$html .= '<td>'.$item->qty.'</td>';
			$html .= '</tr>';
		}

		$data['html'] = $html;

		$data['status'] = $request_info['status'];
		$data['requested_by'] = ucwords($request_info['fname']);
		$data['date_created'] = $request_info['date_requested'];
		$data['remarks'] = empty($request_info['remarks']) ? 'None' : $request_info['remarks'];

		if (!empty($data)) {
			ajax_response('success','success', $data);
		}else {
			ajax_response('Something went wrong','error', $data);
		}
	}

	public function requestsDatatable() {
		$limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $search = $this->input->post('search');
        $order = $this->input->post('order');
        $draw = $this->input->post('draw');

		$select = "
			request_id,
			tbl_user_details.fname as requested_by,
			date_requested,
			tbl_request.status
		";

        $join = array(
			'tbl_user_details' => 'tbl_user_details.user_id = tbl_request.requested_by'
		);

        $where = array(
			'requested_from' => $this->session->location,
		);

        $group = array();

		$column_order = array('request_id', 'requested_by', 'date_requested');

        $list = $this->MY_Model->get_datatables('tbl_request', $column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);

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
}
