<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_order extends MY_Controller {
	function __construct(){
		 parent::__construct();
	}

    public function index() {
		$data['header_right'] = array(
			'action_button' => array(
				'type' => 'button',
				'name' => 'Make a purchase',
				'link' => base_url('sales_order/makeapurchase'),
				'icon' => 'fa fa-plus-circle',
			)
		);

		if ($this->session->type == 2) {
			unset($data['header_right']);
		}
        $this->load_page('index', $data);
    }

	public function salesDatatable() {
		$status = $this->input->post('status');
		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$select ="
			display_id,
			CONCAT(tbl_user_details.fname, ' ', tbl_user_details.lname) as issued_by,
			customer_name,
			date_issued,
			total_items,
			FORMAT(total_amount,'c','en-PH') AS 'total_amount',
			tbl_sales.status,
			tbl_sales.sales_id,
			tbl_locations.name as location
		";
		$column_order = array('sales_id', 'location', 'issued_by', 'customer_name', 'date_issued', 'total_items', 'total_amount', 'tbl_sales.status');

		$join = array(
			'tbl_user_details' => 'tbl_sales.issued_by = tbl_user_details.user_id',
			'tbl_locations' => 'tbl_locations.location_id = tbl_sales.location'
		);

		$where = array();

		if ($this->session->location != 1) {
			$where['location'] = $this->session->location;
		}

		if ($status != 'all') {
			$where['tbl_sales.status'] = $status;
		}

		$group = array();
		$list = $this->MY_Model->get_datatables('tbl_sales', $column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);

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

	public function sales_today() {
		$options['select'] = '
			tbl_sales.sales_id,
			tbl_sales.display_id,
			tbl_sales.issued_by,
			tbl_sales.customer_name,
			tbl_sales.date_issued,
			tbl_sales.total_items,
			tbl_sales.total_amount,
			tbl_user_details.fname,
			tbl_user_details.lname,
		';

		$options['join'] = array(
			'tbl_user_details' => 'tbl_sales.issued_by = tbl_user_details.user_id'
		);

		$options['where'] = array(
			'tbl_sales.location' => $this->session->location,
			'status' => 1,
			'date_issued' => date('Y-m-d'),
		);

		$data['list'] = $this->MY_Model->getRows('tbl_sales', $options);

		$data['hide_breadcrumbs'] = true;
		$this->load_page('salestoday', $data);
	}

    public function makeapurchase() {
		$user_options['select'] = 'fname, lname';
		$user_options['where'] = array(
			'user_id' => $this->session->id
		);

		$user_info = $this->MY_Model->getRows('tbl_user_details', $user_options, 'row_array');

		$data['issued_by_name'] = ucwords($user_info['fname'] . ' ' . $user_info['lname']);

		$data['display_id'] = sprintf("SO%04d", $this->MY_Model->getRows('tbl_sales', [], 'count') + 1);

		$data['hide_breadcrumbs'] = true;
        $this->load_page('makeapurchase', $data);
    }

	public function view($sales_id) {
		$options['select'] = '
			tbl_sales.display_id,
			tbl_sales.customer_name,
			tbl_sales.customer_contact,
			tbl_sales.customer_email,
			tbl_sales.items,
			tbl_sales.date_issued,
			tbl_sales.payment_method,
			tbl_sales.total_items,
			tbl_sales.total_discount,
			tbl_sales.total_amount,
			tbl_sales.remarks,
			tbl_sales.status,
			tbl_sales.void_to,
			tbl_user_details.fname,
			tbl_user_details.lname,
			tbl_locations.name as location,
			(SELECT CONCAT(fname, " ", lname) from tbl_user_details where user_id = `tbl_sales`.`voided_by`) as voided_by
		';

		$options['where'] = array(
			'sales_id' => $sales_id
		);

		$options['join'] = array(
			'tbl_user_details' => 'tbl_sales.issued_by = tbl_user_details.user_id',
			'tbl_locations' => 'tbl_sales.location = tbl_locations.location_id',
		);

		$invoice = $this->MY_Model->getRows('tbl_sales', $options, 'row_array');

		$items_parsed = json_decode($invoice['items']);

		foreach ($items_parsed as $key => $item) {
			$item_options['select'] = 'name';

			$item_options['where'] = array(
				'product_id' => $item->item_id
			);

			$item_info = $this->MY_Model->getRows('tbl_products', $item_options, 'row_array');

			$items_parsed[$key]->item_name = $item_info['name'];
		}

		$invoice['items_parsed'] = $items_parsed;

		switch ($invoice['status']) {
			case 0:
				$invoice['status'] = 'VOID PENDING';
				$invoice['status_color'] = 'warning';
				break;
			case 1:
				$invoice['status'] = 'COMPLETED';
				$invoice['status_color'] = 'info';
				break;
			case 2:
				$invoice['status'] = 'VOID';
				$invoice['status_color'] = 'danger';
				break;
		}

		$data['invoice'] = $invoice;

		if (empty($data['invoice'])) {
			die('Invoice does not exist');
		}

		$data['hide_breadcrumbs'] = true;
        $this->load_page('invoice', $data);
	}

	public function submit($from_edit = false){
		$post = $this->input->post();

		if (empty($post['items']) || !isset($post['items']) || empty($post['items'][0])) {
			ajax_response('Please select an Item', 'error');
		}

		$parsed_items = array();

		$stock_movements = array();

		for ($i=0; $i < count($post['items']); $i++) {
			$parsed_items[] = array(
				'item_id' => $post['items'][$i],
				'qty' => $post['quantity'][$i],
				'discount' => $post['discount'][$i],
				'total' => $post['total'][$i]
			);

			//record movement
			$data_mov = array(
					"item_id" => $post['items'][$i],
					"item_qty" => $post['quantity'][$i],
					"type" => 1,
					"location" => $this->session->location,
					"movement_type" => "Sales",
					"date_added" => date('Y-m-d H:i:s')
			);
			$movement = $this->MY_Model->insert('tbl_inventory_movement',$data_mov);

			$stock_movements[] = $movement;

			# Update TBL_STOCKS
			$where = array(
				'product_id' => $post['items'][$i],
				'location' => $this->session->location
			);

			$option['where'] = $where;

			$item_info = $this->MY_Model->getRows('tbl_stocks', $option, 'row_array');

			$set = array(
				'qty' => $item_info['qty'] - $post['quantity'][$i]
			);

			$this->MY_Model->update('tbl_stocks', $set, $where);
		}

		if ($from_edit) {
			$post['display_id'] = sprintf("SO%04d", $post['void_invoice'] + 1);
			$post['remarks'] = 'Void From: <a href="' . base_url('sales_order/view/' . $post['void_invoice']) . '" >' . sprintf("SO%04d", $post['void_invoice']) . '</a>';
		}

		//Update TBL_SALES
		$insert_data = array(
			'location' => $this->session->location,
			'issued_by' => $this->session->id,
			'date_issued' => date('Y-m-d'),
			'payment_method' => $post['payment'],
			'customer_name' => $post['sold_to'],
			'customer_contact' => $post['contact_no'],
			'customer_email' => $post['email'],
			'items' => json_encode($parsed_items),
			'remarks' => $post['remarks'],
			'total_discount' => $post['total-discount'],
			'total_items' => $post['total-items'],
			'total_amount' => $post['total-amount']
		);

		$insert = $this->MY_Model->insert('tbl_sales', $insert_data);

		if ($insert) {
			$display_id = sprintf("SO%04d", $insert);
			$set_sale_info = array('display_id' => $display_id);
			$where_sale_info = array('sales_id' => $insert);
			$this->MY_Model->update('tbl_sales', $set_sale_info, $where_sale_info);


			//Update Stock Movement Reference ID
			foreach ($stock_movements as $stock_movement_id) {
				$inventory_movement_set = array('reference_id' => $insert);
				$inventory_movement_where = array('movement_id' => $stock_movement_id);
				$this->MY_Model->update('tbl_inventory_movement', $inventory_movement_set, $inventory_movement_where);
			}

			foreach ($parsed_items as $item) {
				$get_cat['select'] = 'category';
				$get_cat['where'] = array(
					'product_id' => $item['item_id']
				);

				$item_info = $this->MY_Model->getRows('tbl_products', $get_cat, 'row_array');

				$insert_purchased = array(
					'purchase_id' => $insert,
					'product_id' => $item['item_id'],
					'category_id' => $item_info['category'],
					'qty' => $item['qty'],
				);

				$this->MY_Model->insert('tbl_purchased_items', $insert_purchased);
			}


			$data['invoice_id'] = $insert;

			$html = '<table class="table table-bordered" style="width:70%; margin:0 auto;">';
			$html .= '<tr>';
			$html .= 	'<td class="text-left">Total Quantity:</td>';
			$html .= 	'<td class="text-right text-themecolor font-weight-bold">'.$insert_data['total_items'].'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= 	'<td class="text-left">Total Amount:</td>';
			$html .= 	'<td class="text-right text-themecolor font-weight-bold">₱ '.$insert_data['total_amount'].'</td>';
			$html .= '</tr>';
			$html .= '</table>';

			$data['totals'] = $html;

			if ($from_edit) {
				return $data;
			}else {
				ajax_response('Purchase Successful!', 'success', $data);
			}
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function editsubmit(){
		$post = $this->input->post();

		$added_invoice = $this->submit(true);

		if (!empty($added_invoice)) {
			$set = array( 'status' => 0, 'void_to' => $added_invoice['invoice_id'], 'remarks' => $post['edit_remarks']);
			$where = array( 'sales_id' => $post['void_invoice'] );

			$this->MY_Model->update('tbl_sales', $set, $where);

			$added_invoice['totals'] .= '<div class="row" style="width: 80%; margin: 1em auto 0; text-align: left;">';
			$added_invoice['totals'] .= 	'<div class="alert alert-warning">';
            $added_invoice['totals'] .=  		'<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>';
            $added_invoice['totals'] .=  		'<h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Warning</h3> Please contact administrator to approve your request.';
            $added_invoice['totals'] .= 	'</div>';
            $added_invoice['totals'] .= '</div>';

			ajax_response('Requested Successfully, Please contact administrator to approve request', 'success', $added_invoice);
		}else {
			ajax_response('Something went wrong!', 'error');
		}
	}

	public function edit($sales_id) {
		$options['select'] = '
			tbl_sales.display_id,
			tbl_sales.customer_name,
			tbl_sales.customer_contact,
			tbl_sales.customer_email,
			tbl_sales.items,
			tbl_sales.date_issued,
			tbl_sales.payment_method,
			tbl_sales.total_items,
			tbl_sales.total_discount,
			tbl_sales.total_amount,
			tbl_sales.remarks,
			tbl_sales.status,
			tbl_user_details.fname,
			tbl_user_details.lname,
			tbl_locations.location_id as location
		';

		$options['where'] = array(
			'sales_id' => $sales_id
		);

		$options['join'] = array(
			'tbl_user_details' => 'tbl_sales.issued_by = tbl_user_details.user_id',
			'tbl_locations' => 'tbl_sales.location = tbl_locations.location_id',
		);

		$invoice = $this->MY_Model->getRows('tbl_sales', $options, 'row_array');

		if (empty($invoice)) {
			die('Invoice does not exist.');
		}

		$items_parsed = json_decode($invoice['items']);

		foreach ($items_parsed as $key => $item) {
			$item_options['select'] = 'name';

			$item_options['where'] = array(
				'product_id' => $item->item_id
			);

			$item_info = $this->MY_Model->getRows('tbl_products', $item_options, 'row_array');

			$items_parsed[$key]->item_name = $item_info['name'];
		}

		$invoice['items_parsed'] = $items_parsed;

		$data['invoice'] = $invoice;

		if (empty($data['invoice'])) {
			die('Invoice does not exist');
		}

		if ($invoice['status'] != 1) {
			redirect(base_url().'sales_order/makeapurchase');
		}

		$data['hide_breadcrumbs'] = true;

		$data['locations'] = $this->MY_Model->getRows('tbl_locations', array('where' => array('status' => 0)));

        $this->load_page('edit', $data);
	}
}
