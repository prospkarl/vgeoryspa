<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receive extends MY_Controller {

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
			array(
				'type' => 'select',
				'name' => 'status',
				'options' => array(
                    '0' => 'Pending',
					'1' => 'Received',
				)
			)
		);

		$data['locations'] = $this->MY_Model->getRows('tbl_locations');
		$cat_options = array('order' => 'name');
		$data['categories'] = $this->MY_Model->getRows('tbl_categories', $cat_options);

		$this->load_page('index', $data);
	}

	public function receiveItems() {
		//Parse Items
		$items = array();
		$items_raw = $this->input->post('item_id');
		$items_qty = $this->input->post('qty_rec');

		for ($i=0; $i < count($items_raw); $i++) {
			if ($items_raw[$i] != '') {
				$items[$items_raw[$i]] = $items_qty[$i];
			}else {
				ajax_response('Please select items', 'error');
			}
		}

		$total_pass_cost = 0;

		// Update TBL_STOCKTRANSFER
		foreach ($items as $id => $qty) {
			$options['where'] = array('product_id' => $id);
			$price = $this->MY_Model->getRows('tbl_products', $options, 'row_array');

			$pass_on_cost =  $price['pass_on_cost'] * $qty;

			$qty_received[] = array(
				'item_id' => $id,
				'qty' => $qty,
				'pass_on_cost' => $price['pass_on_cost'],
				'total_pass_cost' => $pass_on_cost
			);

			$total_pass_cost = $total_pass_cost + $pass_on_cost;
		}

		$set = array(
			'items_received' => json_encode($qty_received),
			'total_amount' => $total_pass_cost,
			'date_received' => date('Y-m-d'),
			'received_by' => $this->session->id,
			'status' => 1,
			'reason_for_disc' => $this->input->post('reason') ? $this->input->post('reason') : null
		);

		$where = array(
			'transfer_id' => $this->input->post('transfer_id')
		);

		// Transfer Info
		$transfer = $this->MY_Model->getRows('tbl_stocktransfer', array('where' => $where), 'row_array');

		$expected_items = array();
		$with_descripancy = false;

		foreach (json_decode($transfer['items']) as $expected) {
			$expected_items[$expected->item_id] = $expected->qty;
		}

		// Update TBL_STOCKS
		foreach ($items as $id => $qty) {
			// + LOCATION "TO"
			$tbl_stock_options['where'] = array(
				'location' => $transfer['location_to'],
				'product_id' => $id
			);

			$stock_info = $this->MY_Model->getRows('tbl_stocks', $tbl_stock_options, 'row_array');

			//record to movement
			$data_mov = array(
				"reference_id" => $this->input->post('transfer_id'),
				"item_id" => $id,
				"item_qty" => $qty,
				"type" => 0,
				"location" => $transfer['location_to'],
				"movement_type" => "Stock Transfer",
				"date_added" => date('Y-m-d H:i:s')
			);
			$movement = $this->MY_Model->insert('tbl_inventory_movement',$data_mov);

			if (!empty($stock_info)) {
				$set_stocks = array(
					'qty' => $stock_info['qty'] + $qty
				);
				$this->MY_Model->update('tbl_stocks', $set_stocks, $tbl_stock_options['where']);

			}else {
				$insert_data = array(
					'product_id' => $id,
					'location' => $transfer['location_to'],
					'qty' => $qty
				);

				$this->MY_Model->insert('tbl_stocks', $insert_data);
			}

			$tbl_product_options['where'] = array(
				'location' => $transfer['location_to'],
				'product_id' => $id
			);

			$begbal = $this->MY_Model->getRows('tbl_beginning_bal', $tbl_product_options, 'row_array');

			if (empty($begbal)) {
				$insert_data = array(
					'product_id' => $id,
					'location' => $transfer['location_to'],
					'beg_balance' => 0
				);
				$this->MY_Model->insert('tbl_beginning_bal', $insert_data);
			}

			$qty_received[] = array(
				'item_id' => $id,
				'qty' => $qty,
			);

			if (!isset($expected_items[$id]) || $expected_items[$id] != $qty) {
				$with_descripancy = true;
			}
		}

		$set['with_discrepancy'] = $with_descripancy;

		$update = $this->MY_Model->update('tbl_stocktransfer', $set, $where);

		if ($update) {
			$set_requests = array(
				'status' => 2
			);

			$where_requests = array(
				'transfer_id' => $this->input->post('transfer_id')
			);

			$check_tbl_request = $this->MY_Model->update('tbl_request', $set_requests, $where_requests);

			ajax_response('Successfully Received', 'success');
		}else {
			ajax_response('Something went wrong', 'error');
		}
	}

	public function returnSector1(){
		$params['where'] = array(
			"transfer_id" => $this->input->post('id')
		);
		$result = $this->MY_Model->getRows('tbl_stocktransfer', $params);
		$str = '';
		// echo "<pre>";
		// print_r($result);
		// exit;
		$itemarray = json_decode($result[0]['items']);

		foreach($itemarray as $info) {
			$para['select'] = "name";
			$para['where'] = array("product_id" => $info->item_id);
			$name = $this->MY_Model->getRows('tbl_products',$para,'row_array')['name'];
			$str .= "<tr>";
				$str .= "<td><div class='autocomplete_drp'>
							  <input required class='autocomplete form-control' type='text' placeholder='Select Product' value='" . $name ."'>
							  <input class='autocomplete_holder' type='hidden' name='item_id[]' value='".$info->item_id."'>
							  <div class='autocomplete_drp-content'></div>
						</div></td>";
				$str .= "<td class=''>". $info->qty."</td>";
				$str .= "<td><input required class='form-control qty_num' min='0' type='number' name='qty_rec[]' value='" . $info->qty . "'></td>";

			$str .= "</tr>";
		}

		$str .= '<tr class="escapetr" style="text-align:center">
			<td colspan="6"><b><a class="text-info PO_addmore_sec1" name="button">
			   <i class=" fas fa-plus"></i> Add More</a></b></td>
		</tr>';

		$data = array(
			"transfer_id" => $result[0]['transfer_id'],
			"string" => $str
		);
		echo json_encode($data);
	}

	public function show_desc(){
		$this->load_page("descrip_view");
	}

	public function getItems(){
		$options['select'] = 'transfer_id, items, transfer_by, received_by, date_added, status, items_received';
		$options['where'] = array(
			'transfer_id' => $this->input->post('transfer_id')
		);

		$stock_info = $this->MY_Model->getRows('tbl_stocktransfer', $options, 'row_array');
		$request_info = $this->MY_Model->getRows('tbl_request', array('where' => $options['where']), 'row_array');

		$requested_items = json_decode($request_info['items']);
		$received_items = json_decode($stock_info['items_received']);
		$html = '';

		foreach (json_decode($stock_info['items']) as $key => $info) {
			$prod_options['select'] = 'name';
			$prod_options['where'] = array(
				'product_id' => $info->item_id
			);
			$name = $this->MY_Model->getRows('tbl_products', $prod_options, 'row_array')['name'];

			$html .= '<tr>';
			$html .=	 '<td>'.$name.'</td>';

			$qty = $info->qty;

			if (!empty($requested_items)) {
				$qty = $requested_items[$key]->qty;
			}

			$html .=	 '<td>'.$qty.'</td>';

			$html .=	 '<td>';

			if ($stock_info['status'] == 0) {
				$html .=	 '<input type="number" min="0" class="form-control rec_qty" name="items['.$info->item_id.']" value="'.$info->qty.'" />';
			}else {
				$html .= 	 '<div class="edit-row">';
				$html .= 	 	'<div class="d-flex justify-content-between">';
				$html .= 	 		'<span>'.$received_items[$key]->qty.'</span> ';
				$html .= 	 		'<a href="javascript:;" class="edit-row-btn m-l-5"><i class="fa fa-edit"></i></a>';
				$html .= 	 	'</div>';
				$html .= 	 '</div>';
				$html .= 	 '<form action="'.base_url('').'" class="edit-form" style="display:none">';
				$html .= 		'<div class="input-group">';
				$html .=	    	'<input type="number" class="form-control form-control-sm" name="quantity" value="'.$received_items[$key]->qty.'" />';
				$html .=	    	'<input type="hidden" name="item_id" value="'.$info->item_id.'" />';
				$html .=	    	'<input type="hidden" name="transfer_id" value="'.$stock_info['transfer_id'].'" />';
				$html .=	    	'<div class="input-group-append">';
                $html .=      			'<button class="btn btn-xs btn-info action-submit" type="submit"><i class="mdi mdi-check" /></button>';
                $html .=      			'<button class="btn btn-xs btn-danger action-cancel" type="button"><i class="mdi mdi-close" /></button>';
                $html .=            '</div>';
				$html .= 		'</div>';
				$html .= 	 '</form>';
			}

			$html .= 	 '</td>';

			$html .= '</tr>';
		}

		$data['html'] = $html;


		$names = array('transfer_by', 'received_by');

		foreach ($names as $name) {
			$name_options['select'] = 'fname, lname';
			$name_options['where'] = array(
				'user_id' => $stock_info[$name]
			);

			$user_info = $this->MY_Model->getRows('tbl_user_details', $name_options, 'row_array');
			$data[$name] = $user_info['fname'] . ' '. $user_info['lname'];
		}

		$data['date_created'] = $stock_info['date_added'];
		$data['remarks'] = $request_info['remarks'] != null ? $request_info['remarks'] : '-';

		if (!empty($html)) {
			ajax_response('success', 'success', $data);
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function updateItems() {
		$post = $this->input->post();

		$transfer_info_options['where'] = array('transfer_id' => $post['transfer_id']);
		$transfer_info = $this->MY_Model->getRows('tbl_stocktransfer', $transfer_info_options, 'row_array');

		$items_received = json_decode($transfer_info['items_received']);
		$total_amount = 0;

		foreach ($items_received as $key => $received) {
			if ($received->item_id == $post['item_id']) {
				//Create Log
				$product_info_op['where'] = array(
					'product_id' => $post['item_id']
				);
				$product_info = $this->MY_Model->getRows('tbl_products', $product_info_op, 'row_array');

				$log = '"' . $product_info['name'] . '" item changed quantity from '.$received->qty.' to '. $post['quantity'];

				$log_info = array(
					'referrer_id' => $post['transfer_id'],
					'log' => $log,
					'logged_by' => $this->session->id
				);
				$this->create_log('tbl_stocktransfer', $log_info);

				$current_qty_options['where'] = array(
					'location' => $this->session->location,
					'product_id' => $post['item_id']
				);
				$current_qty = $this->MY_Model->getRows('tbl_stocks', $current_qty_options, 'row_array')['qty'];
				$set_quantity = ($current_qty - $items_received[$key]->qty) + $post['quantity'];

				$set = array(
					'qty' => $set_quantity
				);
				$this->MY_Model->update('tbl_stocks', $set, $current_qty_options['where']);

				$total_pass_cost = $post['quantity'] * $items_received[$key]->pass_on_cost;

				$items_received[$key]->qty = $post['quantity'];
				$items_received[$key]->total_pass_cost = $total_pass_cost;

				$total_amount = $total_amount + $total_pass_cost;
			}
		}

		$where = array(
			'transfer_id' => $post['transfer_id']
		);

		$set = array(
			'items_received' => json_encode($items_received),
			'total_amount' => $total_amount
		);

		$update = $this->MY_Model->update('tbl_stocktransfer', $set, $where);

		if ($update) {
			$inv_movement_set = array('item_qty' => $this->input->post('quantity'));
			$inv_movement_where = array(
				'reference_id' => $this->input->post('transfer_id'),
				'item_id' => $this->input->post('item_id'),
				'type' => 0,
			);

			$this->MY_Model->update('tbl_inventory_movement', $inv_movement_set, $inv_movement_where);

			ajax_response('Updated Successfully', 'success', $post['transfer_id']);
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function getName($prod_id){
		$params['select'] = "*, (SELECT name from tbl_products where product_id = tbl_stocks.product_id) as name";
		$params['where'] = array("product_id"=> $prod_id);
		$res = $this->MY_Model->getRows("tbl_stocks", $params);

		// $res = $this->MY_Model->getRows("tbl_products", $params);
		return json_encode($res[0]['name']);
	}


	public function getItemsPo(){
		$params['where'] = array('transfer_id' => $this->input->post('id'));
		$res = $this->MY_Model->getRows("tbl_stocktransfer", $params, 'row_array');

		$items = json_decode($res['items_received']);
		$req_items = json_decode($res['items']);

		$with_discrepancies = array();

		foreach ($items as $key => $value) {
			$received_item = $this->get_array_info($req_items, array('item_id' => $value->item_id));
			if ($received_item->qty != $value->qty) {
				$value->prod_name = $this->getName($value->item_id);
				$with_discrepancies[] = $value;
			}
		}

		$data = array(
			"recieved_items" => $with_discrepancies,
			"items" => json_decode($res['items']),
			"reason" => $res['reason_for_disc'] ? $res['reason_for_disc'] : 'None'
		);

		echo json_encode($data);
	}

	public function get_array_info($array, $find) {
		foreach ($array as $key => $info) {
			foreach ($find as $key => $value) {
				if ($info->{$key} == $value) {
					return $info;
				}
			}
		}
	}

	public function receiveDatatable() {
        $limit = $this->input->post('length');
        $offset = $this->input->post('start');
        $search = $this->input->post('search');
        $order = $this->input->post('order');
        $draw = $this->input->post('draw');

		$select = "
			tbl_stocktransfer.transfer_id,
			tbl_user_details.fname as transfer_by,
			tbl_stocktransfer.date_added,
			tbl_stocktransfer.status
		";

        $join = array(
			'tbl_user_details' => 'tbl_user_details.user_id = tbl_stocktransfer.transfer_by'
		);

        $where = array(
			'tbl_stocktransfer.status' => $this->input->post('status'),
			'tbl_stocktransfer.location_to' => $this->session->location
		);

		if (!empty($this->input->post('with_discrepancy'))) {
			$where['with_discrepancy'] = true;
		}

        $group = array();

		$column_order = array('tbl_stocktransfer.transfer_id', 'tbl_stocktransfer.transfer_by', 'tbl_stocktransfer.date_added');

        $list = $this->MY_Model->get_datatables('tbl_stocktransfer', $column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
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
