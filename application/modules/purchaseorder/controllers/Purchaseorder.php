<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchaseorder extends MY_Controller {
	public function index(){
		$data['header_right'] = array(
            array(
                'type' => 'button',
                'name' => 'New Purchase Order',
                'modal' => 'purchaseModal',
                'icon' => 'fa fa-plus-circle'
            ),
        );
		$this->load_page("index",$data);
	}

	public function show_desc(){
		$this->load_page("descrip_view");
	}

	public function getItemsPo(){

		$params['where'] = array('poid' => $this->input->post('id'));
		$res = $this->MY_Model->getRows("tbl_purchase_order", $params);

		$data = array(
			"recieved_items" => json_decode($res[0]['quantity_received']),
			"items" => json_decode($res[0]['items'])
		);
		echo json_encode($data);
	}
	// ========================== Display Ret
	public function returnSector1(){
		$params['where'] = array(
			"poid" => $this->input->post('id')
		);
		$result = $this->MY_Model->getRows('tbl_purchase_order', $params);
		$str = '';

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
				$str .= "<td><input required class='form-control qty_num' min='0' type='number' name='qty_rec[]' value='".$info->qty."'></td>";
				$str .= "<td>&#8369;<span class='costProd'>" . $info->cost_per ."</span> <input class='costper' name='costper[]' type='hidden' value='" .$info->cost_per ."'></td>";
				$str .= "<td>&#8369;<span class='totalPri'>". $info->total_price ."</span> <input class='totPrice' name='totPrice[]' type='hidden' value='" . $info->total_price ."'></td>";
			$str .= "</tr>";
		}

		$str .= '<tr class="escapetr" style="text-align:center">
			<td colspan="6"><b><a class="text-info PO_addmore_sec1" name="button">
			   <i class=" fas fa-plus"></i> Add More</a></b></td>
		</tr>';

		$data = array(
			"poid" => $result[0]['poid'],
			"total_unit" =>$result[0]['total_cost'],
			"total_amount" =>$result[0]['total_qty'],
			"supplier" => $result[0]['supplier'],
			"string" => $str
		);
		echo json_encode($data);
}
// ======================== display editable content
	public function editret(){
		$params['where'] = array(
			"poid" => $this->input->post('id')
		);
		$result = $this->MY_Model->getRows('tbl_purchase_order', $params);
		$str = '';

		$itemarray = json_decode($result[0]['items']);
		foreach($itemarray as $info) {
			$para['select'] = "name";
			$para['where'] = array("product_id" => $info->item_id);
			$name = $this->MY_Model->getRows('tbl_products',$para,'row_array')['name'];
			$str .= "<tr>";
				$str .= "<td><div class='autocomplete_drp'>
							  <input required class='autocomplete form-control' type='text' placeholder='Select Product' value='" . $name ."'>
							  <input class='autocomplete_holder' type='hidden' name='items[]' value='".$info->item_id."'>
							  <div class='autocomplete_drp-content'></div>
						</div></td>";
				$str .= "<td><input required class='form-control qty_num' min='0' type='number' name='quantity[]' value='" . $info->qty  ."'></td>";
				$str .= "<td class='stockAfter' data-stock='12'></td>";
				$str .= "<td>&#8369;<span class='costProd'>" . $info->cost_per ."</span> <input class='costper' name='costper[]' type='hidden' value='" .$info->cost_per ."'></td>";
				$str .= "<td>&#8369;<span class='totalPri'>". $info->total_price ."</span> <input class='totPrice' name='totPrice[]' type='hidden' value='" . $info->total_price ."'></td><td><a class='delBut text-info'><i class='fas fa-trash'></i></a></td>";
			$str .= "</tr>";
		}

		$str .= '<tr style="text-align:center">
			<td colspan="6"><b><a class="text-info PO_addmore" name="button">
			   <i class=" fas fa-plus"></i> Add More</a></b></td>
		</tr>';

		$data = array(
			"poid" => $result[0]['poid'],
			"total_unit" =>$result[0]['total_cost'],
			"total_amount" =>$result[0]['total_qty'],
			"supplier" => $result[0]['supplier'],
			"string" => $str
		);
		echo json_encode($data);
	}
// =============== edit purchase
	public function editPurchase(){
		$total_qty_edit = 0;$total_price_edit = 0;$item_list =array();
		for ($i=0; $i < count($this->input->post('quantity')); $i++) {
			$total_qty_edit = $total_qty_edit + $this->input->post('quantity')[$i];
		}
		for ($i=0; $i < count($this->input->post('totPrice')); $i++) {
			$total_price_edit = $total_price_edit + $this->input->post('totPrice')[$i];
		}
		$where = array("poid" => $this->input->post('poid'));
		for ($i=0; $i < count($this->input->post('items')); $i++) {
			$item_list[] = array(
				"item_id"=>$this->input->post('items')[$i],
				"qty"=>$this->input->post('quantity')[$i],
				"cost_per" => $this->input->post('costper')[$i],
				"total_price" => $this->input->post('totPrice')[$i]
			);
		}
		$data_po = array(
			"supplier" => $this->input->post('supplier'),
			"total_cost" => $total_price_edit,
			"total_qty" => $total_qty_edit,
			"items" => json_encode($item_list)
		);
		$po = $this->MY_Model->update('tbl_purchase_order', $data_po, $where);

		$data_echo = array();

		if ($po) {
			ajax_response('Updated Successfully','success' );
		}else {
			ajax_response('Update Unsuccessful','error' );
		}
	}

// main function autocomplete
	public function autocomplete(){
		$inputted = $this->input->post('input');
		$this->db->distinct();
		$this->db->limit(4);
		$params['select'] = '*';
		$params['where_in'] = array(
			"col" => "tbl_stocks.location",
			"value" => "1"
		);
		$params['join'] = array(
			"tbl_products" => "tbl_products.product_id = tbl_stocks.product_id"
		);
		$params['or_like'] = array(
			'tbl_products.barcode' => $inputted,
			'tbl_products.sku' => $inputted,
			'tbl_products.name' => $inputted,
		);
		$res = $this->MY_Model->getRows('tbl_stocks', $params);
		echo json_encode($res);
	}
// ===========  for auto complete function
	public function getSelItem(){
		$params['order'] = "product_id desc";
		$params['select'] = "tbl_products.product_id, tbl_products.name, tbl_products.barcode, tbl_products.sku, tbl_products.supplier_price, SUM(tbl_stocks.qty) as total";
		$params['where'] = array(
			"tbl_products.product_id" => $this->input->post('id'),
			"tbl_stocks.location" => 1
		);
		$params['join'] = array(
			"tbl_stocks" => "tbl_products.product_id = tbl_stocks.product_id"
		);
		$res = $this->MY_Model->getRows('tbl_products',$params, 'row_array');
		echo json_encode($res);
	}
// ================================== add purchase
	public function addPurchaseOrder() {
		if (!empty($this->input->post('quantity'))) {
			foreach ($this->input->post('quantity') as $key => $value) {
				if ($value == '0' || $value == '') {
					ajax_response('Please select an item', 'error');
				}
			}
		}

		$total_qty = 0; $total_price = 0; $item_list = array();
		if (is_array($this->input->post('totPrice')) ) {
			for ($i=0; $i < count($this->input->post('quantity')); $i++) {
				$total_qty = $total_qty + $this->input->post('quantity')[$i];
			}
			for ($i=0; $i < count($this->input->post('totPrice')); $i++) {
				$total_price = $total_price + (int)$this->input->post('totPrice')[$i];
			}
			for ($i=0; $i < count($this->input->post('items')); $i++) {
				$item_list[] = array(
					"item_id"=>$this->input->post('items')[$i],
					"qty"=>$this->input->post('quantity')[$i],
					"cost_per" => $this->input->post('costper')[$i],
					"total_price" => $this->input->post('totPrice')[$i]
				);
			}
			$data_po = array(
				"supplier" => $this->input->post('supplier'),
				"created_by" => $this->session->userdata('id'),
			    "created_date" => date("Y-m-d"),
				"total_cost" => $total_price,
				"total_qty" => $total_qty,
				"items" => json_encode($item_list)
			);
			$res_po = $this->MY_Model->insert('tbl_purchase_order', $data_po);
			if ($res_po) {
				ajax_response('Added Successfully','success');
			}else {
				ajax_response('Add Unsuccessful','error');
			}
		}else {
			ajax_response('Please fill all Fields','warning',array("data" => "error"));
		}

	}
//==================================== view Purchase
	public function viewPurchaseOrder(){
		$params['where'] = array(
			"poid" => $this->input->post('id')
		);
		$result = $this->MY_Model->getRows('tbl_purchase_order', $params);
		$td = array();
		$table_data = array();

		if ($result[0]['quantity_received'] != null) {
			$itemarray = json_decode($result[0]['quantity_received']);
			$itemarray_og = json_decode($result[0]['items']);
			$i = 0;
			foreach($itemarray as $info) {
				$para['select'] = "name";
				$para['where'] = array("product_id" => $info->item_id);
				$name = $this->MY_Model->getRows('tbl_products',$para,'row_array')['name'];
				$td[] = array($info->item_id, $name,$info->qty, (count($itemarray_og) > $i)? $itemarray_og[$i]->qty : 0,$info->cost_per,$info->total_price);
				$i++;
			}
			$table_data = array(
				"header" => array("ID", "Item Name", "Qty Requested", "Item Received", "Cost per Product", "Total Price"),
				"data" => $td
			);
		}else {
			$itemarray = json_decode($result[0]['items']);

			foreach($itemarray as $key => $info) {
				$para['select'] = "name";
				$para['where'] = array("product_id" => $info->item_id);
				$name = $this->MY_Model->getRows('tbl_products',$para,'row_array')['name'];
				$td[] = array($info->item_id, $name,$info->qty, $info->qty,$info->cost_per,$info->total_price);
			}
			$table_data = array(
				"header" => array("ID", "Item Name", "Quantity", "To Receive", "Cost per Product", "Total Price"),
				"data" => $td
			);
		}



			$data = array(
				"poid" =>$result[0]['poid'],
				"total_unit" =>$result[0]['total_cost'],
				"total_amount" =>$result[0]['total_qty'],
				"supplier" => $result[0]['supplier'],
				"table" => $table_data
			);
		echo json_encode($data);
	}

//  ======================  receive items
	public function receiveItem(){
		$params['where'] = array(
			"poid" => $this->input->post('id')
		);
		$result = $this->MY_Model->getRows('tbl_purchase_order', $params);
		$itemarray = json_decode($result[0]['items']);
		$str = '';
        $str .= '<input name="poid" type="hidden" value="'.$this->input->post('id') .'">';
		foreach($itemarray as $info) {
			$para['select'] = "name";
			$para['where'] = array("product_id" => $info->item_id);
			$name = $this->MY_Model->getRows('tbl_products',$para,'row_array')['name'];
			$str .= "<tr>";
			$str .= "<td><input name='item_id[]' type='hidden' value='". $info->item_id ."'/>" . $name ."</td>";
			$str .= "<td>" . $info->qty ."</td>";
			$str .= "<td><input required class='form-control rec_qty' type='number' name='qty_rec[]' min='0' value='".$info->qty."'></td>";
			$str .= "<td>" .  $info->cost_per ."</td>";
			$str .= "<td>" .  $info->total_price ."</td>";
			$str .= "</tr>";
		}

		$data = array(
			"poid" =>$result[0]['poid'],
			"total_unit" =>$result[0]['total_cost'],
			"total_amount" =>$result[0]['total_qty'],
			"supplier" => $result[0]['supplier'],
			"string" => $str
		);
		echo json_encode($data);
	}

	public function removepurchase(){
		$data = array("is_trash" => 1);
		$where = array("poid" => $this->input->post('id'));
		$res = $this->MY_Model->update('tbl_purchase_order',$data,$where);
		echo json_encode($res);
	}

	public function updateQty(){
		$data_arr = array();
		$price= 0;
		$unit = 0;

		$where = array(
			"poid" => $this->input->post('poid')
		);

		$po_info = $this->MY_Model->getRows('tbl_purchase_order', array('where' => $where), 'row_array');

		$expected_items = json_decode($po_info['items']);

		$with_discrepancy = false;


		for ($i=0; $i < count($this->input->post('item_id')); $i++) {
			$data_arr[] = array(
				"item_id" => $this->input->post('item_id')[$i],
				"qty"	  => $this->input->post('qty_rec')[$i],
				"cost_per" => $this->input->post('costper')[$i],
				"total_price" => $this->input->post('totPrice')[$i]
			);
			$price = $price+ $this->input->post('totPrice')[$i];
			$unit++;

			if (isset($expected_items[$i])) {
				if ($expected_items[$i]->qty != $this->input->post('qty_rec')[$i]) {
					$with_discrepancy = true;
				}
			}else {
				$with_discrepancy = true;
			}
		}

		$data = array(
			'total_cost' => $price,
			'total_qty' =>$unit,
			'received_by' => $this->session->userdata('id'),
			'received_date' => date('Y-m-d'),
			'quantity_received' => json_encode($data_arr),
			'status' => 3,
			'with_discrepancy' => $with_discrepancy
		);

		$res = $this->MY_Model->update('tbl_purchase_order', $data, $where);

		foreach ($data_arr as $key) {
			$params_sel['select'] = "qty";
			$params_sel['where'] = array("product_id" => $key['item_id'], "location" => 1);
			$prevQty = $this->MY_Model->getRows('tbl_stocks', $params_sel)[0]['qty'];
			$stock_where = array(
				"product_id" => $key['item_id'],
				"location" => 1
			);
			$stock_data = array(
				"qty" => $prevQty + $key['qty']
			);
			$stocks = $this->MY_Model->update('tbl_stocks', $stock_data, $stock_where);
			$data_inv = array(
				"item_id" =>$key['item_id'],
				"item_qty" =>$key['qty'],
				"type" =>0,
				"movement_type" =>'Purchase Order',
				"date_added" => date('Y-m-d H:i:s'),
				"location" => 1
			);
			$inventory = $this->MY_Model->insert('tbl_inventory_movement', $data_inv);
		}

		if ($res) {
			ajax_response('Received Successfully', 'success');
		}else {
			ajax_response('Receive Unsuccessful', 'error');
		}
	}

	public function getName(){
		$params['where'] = array("product_id"=> $this->input->post('id'));
		$res = $this->MY_Model->getRows("tbl_products", $params);
		echo json_encode($res[0]['name']);
	}

// =================================== datatable Purchase
	public function purchaseDataTable(){
		$limit = $this->input->post('length');
		$offset = $this->input->post('start');
		$search = $this->input->post('search');
		$order = $this->input->post('order');
		$draw = $this->input->post('draw');
		$column_order = array('poid','supplier','total_qty', 'total_cost', 'status');
		$join = array();

		if ($this->input->post('status') == 'all') {
			$where = array("is_trash" => 0);
		}elseif ($this->input->post('status') == 0) {
			$where = array("is_trash" => 0, "status" => $this->input->post('status'));
		}elseif ($this->input->post('status') == 3) {
			$where = array("is_trash" => 0, "status" => $this->input->post('status'));
		}

		if (!empty($this->input->post('with_discrepancy'))) {
			$where['with_discrepancy'] = 1;
		}

		$select ="
			poid,
			supplier,
			total_qty,
			total_cost,
			IFNULL(received_date, created_date) as last_updated,
			status,
			(SELECT concat(fname, ' ', lname) from tbl_user_details where user_id = tbl_purchase_order.received_by) as name, FORMAT(total_cost,'c','en-PH') AS 'total_cost'
		";

		$group = array();
		$list = $this->MY_Model->get_datatables('tbl_purchase_order',$column_order, $select, $where, $join, $limit, $offset ,$search, $order, $group);
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

	public function runtest($number_of_items = 10) {
		$options['select'] = 'product_id';
		$options['where'] = array('status' => 1);
		$products = $this->MY_Model->getRows('tbl_products', $options);

		foreach ($products as $key) {
			$params_sel['select'] = "qty";
			$params_sel['where'] = array("product_id" => $key['product_id'], "location" => 1);
			$prevQty = $this->MY_Model->getRows('tbl_stocks', $params_sel)[0]['qty'];
			$stock_where = array(
				"product_id" => $key['product_id'],
				"location" => 1
			);
			$stock_data = array(
				"qty" => $prevQty + $number_of_items
			);
			$stocks = $this->MY_Model->update('tbl_stocks', $stock_data, $stock_where);
			$data_inv = array(
				"item_id" =>$key['product_id'],
				"item_qty" => $number_of_items,
				"type" =>0,
				"movement_type" =>'Purchase Order',
				"date_added" => date('Y-m-d H:i:s'),
				"location" => 1
			);
			$inventory = $this->MY_Model->insert('tbl_inventory_movement', $data_inv);
		}
		die('done!');
	}

	public function listofitems() {
		$options['select'] = 'product_id, name';
		$items = $this->MY_Model->getRows('tbl_products', $options);
		$html = '<table>';
		foreach ($items as $item_info) {
			$html .= '<tr>';
			$html .= '<td>'.$item_info['product_id'].'</td>';
			$html .= '<td>'.$item_info['name'].'</td>';
			$html .= '</tr>';
		}
		$html .= '</table>';

		die($html);
	}
}

// $('td.variance.text_red').each(function(){
//     $(this).parents('tr').find('.count_qty_mon').val($(this).html().replace('-',''))
// 	 $(this).parents('tr').find('.count_qty_mon').trigger('change')
// })
