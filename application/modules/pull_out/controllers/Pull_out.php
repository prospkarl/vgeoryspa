<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pull_out extends MY_Controller {
	function __construct(){
		 parent::__construct();
	}

    public function index() {
		$data['header_right'] = array(
			'action_button' => array(
				'type' => 'button',
				'name' => 'Pull Out Items',
				'modal' => 'pullOutModal',
				'icon' => 'fa fa-plus-circle',
			)
		);

		$options['select'] = 'name, location_id';
		$options['where'] = array(
			'status' => 0
		);

		$data['locations'] = $this->MY_Model->getRows('tbl_locations',$options);

        $this->load_page('index', $data);
    }

	public function submit() {
		$items = $this->input->post('items');
		$quantity = $this->input->post('quantity');
		$after_quantity = $this->input->post('after_quantity');
		$location = $this->input->post('location');

		if (empty($items)) {
			ajax_response('Please select an item first', 'error');
		}

		if (empty($this->input->post('remarks'))) {
			ajax_response('Remarks - required', 'error');
		}

		$parsed_items = array();
		$moved_items = array();

		for ($i=0; $i < count($items) ; $i++) {
			$parsed_items[] = array(
				'item_id' => $items[$i],
				'qty' => $quantity[$i]
			);

			$set_options = array(
				'qty' => $after_quantity[$i]
			);

			$where_options = array(
				'product_id' => $items[$i],
				'location' => $location
			);

			$this->MY_Model->update('tbl_stocks', $set_options, $where_options);


			$data_mov = array(
				"item_id" => $items[$i],
				"item_qty" => $quantity[$i],
				"type" => 1,
				"movement_type" => "Pull Out",
				"date_added" => date('Y-m-d H:i:s'),
				"location" =>$location
			);

			$movement = $this->MY_Model->insert('tbl_inventory_movement',$data_mov);
			$moved_items[] = $movement;
		}

		$insert_data = array(
			'items' => json_encode($parsed_items),
			'created_by' => $this->session->id,
			'location' => $location,
			'date_created' => date('Y-m-d'),
			'remarks' => $this->input->post('remarks')
		);

		$insert = $this->MY_Model->insert('tbl_pull_out', $insert_data);

		foreach ($moved_items as $movement_id) {
			$set = array( 'reference_id' => $insert );
			$where = array( 'movement_id' => $movement_id );
			$this->MY_Model->update('tbl_inventory_movement', $set, $where);
		}

		if ($insert) {
			ajax_response('Updated Successfully!', 'success');
		}else {
			ajax_response('Something went wrong', 'error');
		}
	}

	public function pulloutDatatable() {
		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');

		$select = "
			pull_out_id,
			tbl_user_details.fname as created_by,
			status,
			date_created
		";

		$join = array(
			'tbl_user_details' => 'tbl_user_details.user_id = tbl_pull_out.created_by'
		);

		$where = array();

		$group = array();

		$column_order = array('pull_out_id', 'tbl_user_details.fname', 'date_created');

		$list = $this->MY_Model->get_datatables('tbl_pull_out', $column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);

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

	public function getPullOutInfo() {
		$pull_out_id = $this->input->post('id');

		$options['select'] = 'tbl_pull_out.items, tbl_user_details.fname, tbl_user_details.lname, tbl_pull_out.remarks, tbl_pull_out.date_created, tbl_pull_out.date_created, tbl_locations.name as location';

		$options['where'] = array(
			'pull_out_id' => $pull_out_id
		);

		$options['join'] = array(
			'tbl_user_details' => 'tbl_user_details.user_id = tbl_pull_out.created_by',
			'tbl_locations' => 'tbl_pull_out.location = tbl_locations.location_id'
		);

		$pull_out_info = $this->MY_Model->getRows('tbl_pull_out', $options, 'row_array');

		$items = json_decode($pull_out_info['items']);

		$html = '';

		foreach ($items as $item_info) {
			$prod_options['select'] = 'name';
			$prod_options['where'] = array(
				'product_id' => $item_info->item_id
			);

			$product_info = $this->MY_Model->getRows('tbl_products', $prod_options, 'row_array');

			$html .= '<tr>';
			$html .= '<td>'.$product_info['name'].'</td>';
			$html .= '<td>-'.$item_info->qty.'</td>';
			$html .= '</tr>';
		}

		$data['html'] = $html;
		$data['created_by'] = ucwords($pull_out_info['fname'] . ' ' . $pull_out_info['lname']);
		$data['date_created'] = $pull_out_info['date_created'];
		$data['remarks'] = $pull_out_info['remarks'];
		$data['location'] = $pull_out_info['location'];

		ajax_response('success', 'success', $data);
	}

	public function revertPullOut($pull_out_id) {
		$reason = $this->input->post('reason');

		$pull_out_info_op['where'] = array('pull_out_id' => $pull_out_id);
		$pull_out_info = $this->MY_Model->getRows('tbl_pull_out', $pull_out_info_op, 'row_array');
		$pulled_out_items = json_decode($pull_out_info['items']);
		$pulled_out_loc = $pull_out_info['location'];

		foreach ($pulled_out_items as $item) {
			$get_current_qty_op['where'] = array('product_id' => $item->item_id, 'location' => $pulled_out_loc);
			$curr_stock_info = $this->MY_Model->getRows('tbl_stocks', $get_current_qty_op, 'row_array');

			// Add the qty of the item being pulled out
			$set_to_qty = (int)$curr_stock_info['qty'] + (int)$item->qty;

			$set_stock_op_set = array('qty' => $set_to_qty);
			$set_stock_op_where = array('product_id' => $item->item_id, 'location' => $pulled_out_loc);

			$this->MY_Model->update('tbl_stocks', $set_stock_op_set, $set_stock_op_where);
		}

		$del_mov_where = array(
			'movement_type' => 'Pull Out',
			'reference_id' => $pull_out_id,
		);
		$this->MY_Model->delete('tbl_inventory_movement', $del_mov_where);

		$upd_pull_out_set = array(
			'status' => 0,
			'remarks' => $reason,
		);
		$upd_pull_out_where = array(
			'pull_out_id' => $pull_out_id
		);
		$upd = $this->MY_Model->update('tbl_pull_out', $upd_pull_out_set, $upd_pull_out_where);

		ajax_response('Successfully Updated', 'success');
	}
}
